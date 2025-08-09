<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'monitoring_storage.php';

$action = $_GET['action'] ?? 'status';
$storage = new MonitoringDataStorage();

try {
    switch ($action) {
        case 'status':
            // Get current status of all services
            $stats = $storage->getAllServicesStats(1); // Last hour
            echo json_encode([
                'success' => true,
                'data' => $stats,
                'timestamp' => time()
            ]);
            break;
            
        case 'realtime':
            // Get real-time data for charts
            $minutes = (int)($_GET['minutes'] ?? 60);
            $data = $storage->getRealTimeData($minutes);
            echo json_encode([
                'success' => true,
                'data' => $data,
                'timestamp' => time()
            ]);
            break;
            
        case 'history':
            // Get historical data
            $service = $_GET['service'] ?? 'peserta';
            $startDate = $_GET['start_date'] ?? date('Y-m-d');
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            $limit = (int)($_GET['limit'] ?? 100);
            
            $data = $storage->getMonitoringData($service, $startDate, $endDate, $limit);
            echo json_encode([
                'success' => true,
                'data' => $data,
                'service' => $service,
                'date_range' => [$startDate, $endDate]
            ]);
            break;
            
        case 'export':
            // Export data to CSV
            $service = $_GET['service'] ?? 'peserta';
            $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            
            $filename = $storage->exportToCSV($service, $startDate, $endDate);
            
            if ($filename && file_exists($filename)) {
                header('Content-Type: application/csv');
                header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
                header('Content-Length: ' . filesize($filename));
                readfile($filename);
                unlink($filename); // Delete temp file
                exit;
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Failed to export data'
                ]);
            }
            break;
            
        case 'cleanup':
            // Clean old data
            $daysToKeep = (int)($_GET['days'] ?? 30);
            $deletedFiles = $storage->cleanOldData($daysToKeep);
            echo json_encode([
                'success' => true,
                'deleted_files' => $deletedFiles,
                'message' => "Cleaned {$deletedFiles} old data files"
            ]);
            break;
            
        case 'summary':
            // Get summary statistics
            $hours = (int)($_GET['hours'] ?? 24);
            $services = ['peserta', 'rujukan', 'antrol', 'sep'];
            $summary = [];
            
            foreach ($services as $service) {
                $summary[$service] = $storage->getServiceStats($service, $hours);
            }
            
            // Calculate overall stats
            $totalRequests = array_sum(array_column($summary, 'total_requests'));
            $totalSuccess = array_sum(array_column($summary, 'success_requests'));
            $totalFailed = array_sum(array_column($summary, 'failed_requests'));
            
            $responseTimes = [];
            foreach ($summary as $stats) {
                if ($stats['avg_response_time'] > 0) {
                    $responseTimes[] = $stats['avg_response_time'];
                }
            }
            
            $overallStats = [
                'total_requests' => $totalRequests,
                'success_requests' => $totalSuccess,
                'failed_requests' => $totalFailed,
                'overall_success_rate' => $totalRequests > 0 ? round(($totalSuccess / $totalRequests) * 100, 2) : 0,
                'avg_response_time' => !empty($responseTimes) ? round(array_sum($responseTimes) / count($responseTimes), 2) : 0,
                'uptime_percentage' => $totalRequests > 0 ? round(($totalSuccess / $totalRequests) * 100, 2) : 0
            ];
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'services' => $summary,
                    'overall' => $overallStats,
                    'period_hours' => $hours
                ]
            ]);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'error' => 'Invalid action'
            ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
