<?php
/**
 * Data Storage Handler for BPJS Monitoring
 * Mengelola penyimpanan data monitoring menggunakan file JSON
 */

class MonitoringDataStorage {
    
    private $dataDir;
    private $maxRecords;
    private $maxFileSize;
    
    public function __construct($dataDir = 'data', $maxRecords = 1000, $maxFileSize = 5242880) {
        $this->dataDir = __DIR__ . '/' . $dataDir;
        $this->maxRecords = $maxRecords;
        $this->maxFileSize = $maxFileSize; // 5MB default
        
        // Create data directory if not exists
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
    }
    
    /**
     * Save monitoring data
     */
    public function saveMonitoringData($service, $data) {
        $filename = $this->dataDir . "/monitoring_{$service}_" . date('Y-m-d') . '.json';
        
        // Load existing data
        $existingData = $this->loadDataFromFile($filename);
        
        // Add new data
        $newEntry = [
            'timestamp' => time(),
            'datetime' => date('Y-m-d H:i:s'),
            'service' => $service,
            'status' => $data['status'],
            'response_time' => $data['response_time'],
            'endpoint_type' => $data['endpoint_type'],
            'success' => $data['success'] ?? true,
            'error_message' => $data['error_message'] ?? null,
            'http_code' => $data['http_code'] ?? 200
        ];
        
        $existingData[] = $newEntry;
        
        // Keep only recent records
        if (count($existingData) > $this->maxRecords) {
            $existingData = array_slice($existingData, -$this->maxRecords);
        }
        
        // Save back to file
        return $this->saveDataToFile($filename, $existingData);
    }
    
    /**
     * Get monitoring data for specific service and date range
     */
    public function getMonitoringData($service, $startDate = null, $endDate = null, $limit = 100) {
        if (!$startDate) {
            $startDate = date('Y-m-d');
        }
        if (!$endDate) {
            $endDate = date('Y-m-d');
        }
        
        $allData = [];
        
        // Iterate through date range
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $filename = $this->dataDir . "/monitoring_{$service}_{$currentDate}.json";
            
            if (file_exists($filename)) {
                $dayData = $this->loadDataFromFile($filename);
                $allData = array_merge($allData, $dayData);
            }
            
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        
        // Sort by timestamp descending
        usort($allData, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        // Apply limit
        if ($limit > 0) {
            $allData = array_slice($allData, 0, $limit);
        }
        
        return $allData;
    }
    
    /**
     * Get statistics for specific service
     */
    public function getServiceStats($service, $hours = 24) {
        $data = $this->getMonitoringData($service, date('Y-m-d', strtotime("-1 day")), date('Y-m-d'), 0);
        
        // Filter by time range
        $cutoffTime = time() - ($hours * 3600);
        $filteredData = array_filter($data, function($item) use ($cutoffTime) {
            return $item['timestamp'] >= $cutoffTime;
        });
        
        if (empty($filteredData)) {
            return [
                'total_requests' => 0,
                'success_requests' => 0,
                'failed_requests' => 0,
                'success_rate' => 0,
                'avg_response_time' => 0,
                'min_response_time' => 0,
                'max_response_time' => 0,
                'last_check' => null
            ];
        }
        
        $totalRequests = count($filteredData);
        $successRequests = count(array_filter($filteredData, function($item) {
            return $item['success'] === true;
        }));
        $failedRequests = $totalRequests - $successRequests;
        
        $responseTimes = array_column($filteredData, 'response_time');
        $responseTimes = array_filter($responseTimes, function($time) {
            return $time !== null && $time > 0;
        });
        
        return [
            'total_requests' => $totalRequests,
            'success_requests' => $successRequests,
            'failed_requests' => $failedRequests,
            'success_rate' => $totalRequests > 0 ? round(($successRequests / $totalRequests) * 100, 2) : 0,
            'avg_response_time' => !empty($responseTimes) ? round(array_sum($responseTimes) / count($responseTimes), 2) : 0,
            'min_response_time' => !empty($responseTimes) ? min($responseTimes) : 0,
            'max_response_time' => !empty($responseTimes) ? max($responseTimes) : 0,
            'last_check' => $filteredData[0]['datetime'] ?? null
        ];
    }
    
    /**
     * Get aggregated stats for all services
     */
    public function getAllServicesStats($hours = 24) {
        $services = ['peserta', 'rujukan', 'antrol', 'sep'];
        $stats = [];
        
        foreach ($services as $service) {
            $stats[$service] = $this->getServiceStats($service, $hours);
        }
        
        return $stats;
    }
    
    /**
     * Clean old data files
     */
    public function cleanOldData($daysToKeep = 30) {
        $cutoffDate = date('Y-m-d', strtotime("-{$daysToKeep} days"));
        $pattern = $this->dataDir . '/monitoring_*_*.json';
        $files = glob($pattern);
        
        $deletedFiles = 0;
        foreach ($files as $file) {
            $filename = basename($file);
            if (preg_match('/monitoring_(.+)_(\d{4}-\d{2}-\d{2})\.json/', $filename, $matches)) {
                $fileDate = $matches[2];
                if ($fileDate < $cutoffDate) {
                    unlink($file);
                    $deletedFiles++;
                }
            }
        }
        
        return $deletedFiles;
    }
    
    /**
     * Export data to CSV
     */
    public function exportToCSV($service, $startDate, $endDate, $outputFile = null) {
        $data = $this->getMonitoringData($service, $startDate, $endDate, 0);
        
        if (!$outputFile) {
            $outputFile = $this->dataDir . "/export_{$service}_{$startDate}_to_{$endDate}.csv";
        }
        
        $fp = fopen($outputFile, 'w');
        
        // Header
        fputcsv($fp, ['timestamp', 'datetime', 'service', 'status', 'response_time', 'endpoint_type', 'success', 'error_message', 'http_code']);
        
        // Data
        foreach ($data as $row) {
            fputcsv($fp, $row);
        }
        
        fclose($fp);
        return $outputFile;
    }
    
    /**
     * Load data from JSON file
     */
    private function loadDataFromFile($filename) {
        if (!file_exists($filename)) {
            return [];
        }
        
        $content = file_get_contents($filename);
        if ($content === false) {
            return [];
        }
        
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }
    
    /**
     * Save data to JSON file
     */
    private function saveDataToFile($filename, $data) {
        // Check file size before writing
        if (file_exists($filename) && filesize($filename) > $this->maxFileSize) {
            // Archive old file
            $archiveFile = $filename . '.archive.' . time();
            rename($filename, $archiveFile);
        }
        
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // Atomic write
        $tempFile = $filename . '.tmp';
        if (file_put_contents($tempFile, $json, LOCK_EX) !== false) {
            return rename($tempFile, $filename);
        }
        
        return false;
    }
    
    /**
     * Get real-time data for dashboard
     */
    public function getRealTimeData($minutes = 60) {
        $services = ['peserta', 'rujukan', 'antrol', 'sep'];
        $result = [];
        
        foreach ($services as $service) {
            $data = $this->getMonitoringData($service, date('Y-m-d'), date('Y-m-d'), 0);
            
            // Filter last X minutes
            $cutoffTime = time() - ($minutes * 60);
            $recentData = array_filter($data, function($item) use ($cutoffTime) {
                return $item['timestamp'] >= $cutoffTime;
            });
            
            // Group by minute for chart data
            $chartData = [];
            foreach ($recentData as $item) {
                $minute = date('Y-m-d H:i', $item['timestamp']);
                if (!isset($chartData[$minute])) {
                    $chartData[$minute] = [];
                }
                $chartData[$minute][] = $item['response_time'];
            }
            
            // Calculate average per minute
            $timeSeriesData = [];
            foreach ($chartData as $minute => $responseTimes) {
                $responseTimes = array_filter($responseTimes, function($time) {
                    return $time !== null && $time > 0;
                });
                
                if (!empty($responseTimes)) {
                    $timeSeriesData[] = [
                        'time' => strtotime($minute) * 1000, // JavaScript timestamp
                        'value' => round(array_sum($responseTimes) / count($responseTimes), 2)
                    ];
                }
            }
            
            $result[$service] = [
                'stats' => $this->getServiceStats($service, 1), // Last hour stats
                'chart_data' => $timeSeriesData
            ];
        }
        
        return $result;
    }
}

/**
 * Simple logging function
 */
function logMonitoringEvent($message, $level = 'INFO') {
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/monitoring_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}
