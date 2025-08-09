<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPJS API Monitoring Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .dashboard-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .status-card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: linear-gradient(45deg, #fff, #f8f9fa);
        }
        
        .status-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .status-online {
            border-left: 4px solid #28a745;
        }
        
        .status-offline {
            border-left: 4px solid #dc3545;
        }
        
        .status-warning {
            border-left: 4px solid #ffc107;
        }
        
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .control-panel {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .metrics-card {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            color: white;
            border-radius: 10px;
            text-align: center;
        }
        
        .endpoint-switch {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 10px;
            margin: 5px 0;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .btn-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
        }
        
        .btn-custom:hover {
            background: linear-gradient(45deg, #764ba2, #667eea);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="dashboard-container p-4">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold text-primary">
                        <i class="bi bi-activity"></i>
                        BPJS API Monitoring Dashboard
                    </h1>
                    <p class="lead text-muted">Real-time monitoring untuk VCLAIM dan ANTROL API</p>
                </div>
            </div>

            <!-- Control Panel -->
            <div class="control-panel">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label for="intervalSelect" class="form-label fw-semibold">
                            <i class="bi bi-clock-history"></i> Interval Monitoring:
                        </label>
                        <select id="intervalSelect" class="form-select">
                            <option value="5000" selected>5 detik</option>
                            <option value="10000">10 detik</option>
                            <option value="30000">30 detik</option>
                            <option value="60000">1 menit</option>
                            <option value="300000">5 menit</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="endpointSelect" class="form-label fw-semibold">
                            <i class="bi bi-server"></i> Endpoint Type:
                        </label>
                        <select id="endpointSelect" class="form-select">
                            <option value="production" selected>Production</option>
                            <option value="cdn">CDN</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-gear"></i> Kontrol:
                        </label>
                        <div class="d-flex gap-2">
                            <button id="startBtn" class="btn btn-success btn-sm">
                                <i class="bi bi-play-fill"></i> Start
                            </button>
                            <button id="stopBtn" class="btn btn-danger btn-sm">
                                <i class="bi bi-stop-fill"></i> Stop
                            </button>
                            <button id="clearBtn" class="btn btn-warning btn-sm">
                                <i class="bi bi-trash"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div id="monitoringStatus" class="fw-bold fs-5">
                                <i class="bi bi-circle-fill text-success"></i> MONITORING AKTIF
                            </div>
                            <small id="lastUpdate">Last update: -</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card metrics-card">
                        <div class="card-body">
                            <h5><i class="bi bi-speedometer2"></i> Avg Response</h5>
                            <h2 id="avgResponse">0 ms</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metrics-card">
                        <div class="card-body">
                            <h5><i class="bi bi-check-circle"></i> Success Rate</h5>
                            <h2 id="successRate">0%</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metrics-card">
                        <div class="card-body">
                            <h5><i class="bi bi-graph-up"></i> Total Requests</h5>
                            <h2 id="totalRequests">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metrics-card">
                        <div class="card-body">
                            <h5><i class="bi bi-exclamation-triangle"></i> Errors</h5>
                            <h2 id="totalErrors">0</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Status Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card status-card status-offline" id="card-peserta">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title fw-semibold">Peserta VCLAIM</h6>
                                    <p class="text-muted small mb-1">Cek data peserta by NIK/NOKA</p>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-person-check fs-3 text-primary"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div id="status-peserta" class="fw-semibold text-secondary">
                                        <i class="bi bi-circle-fill"></i> OFFLINE
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="response-peserta" class="fw-semibold">- ms</div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <small id="lastCheck-peserta" class="text-muted">Never checked</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card status-card status-offline" id="card-rujukan">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title fw-semibold">Rujukan VCLAIM</h6>
                                    <p class="text-muted small mb-1">Cek data rujukan</p>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-file-medical fs-3 text-success"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div id="status-rujukan" class="fw-semibold text-secondary">
                                        <i class="bi bi-circle-fill"></i> OFFLINE
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="response-rujukan" class="fw-semibold">- ms</div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <small id="lastCheck-rujukan" class="text-muted">Never checked</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card status-card status-offline" id="card-antrol">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title fw-semibold">Antrol/HFIS</h6>
                                    <p class="text-muted small mb-1">Sistem antrian online</p>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-list-ol fs-3 text-warning"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div id="status-antrol" class="fw-semibold text-secondary">
                                        <i class="bi bi-circle-fill"></i> OFFLINE
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="response-antrol" class="fw-semibold">- ms</div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <small id="lastCheck-antrol" class="text-muted">Never checked</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card status-card status-offline" id="card-sep">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title fw-semibold">SEP VCLAIM</h6>
                                    <p class="text-muted small mb-1">Monitoring SEP</p>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-clipboard-check fs-3 text-info"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div id="status-sep" class="fw-semibold text-secondary">
                                        <i class="bi bi-circle-fill"></i> OFFLINE
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="response-sep" class="fw-semibold">- ms</div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <small id="lastCheck-sep" class="text-muted">Never checked</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="chart-container">
                <div id="chartContainer" style="height: 400px;"></div>
            </div>

            <!-- Data History -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="bi bi-table"></i> History Log (Last 50 Records)</h5>
                        </div>
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <div id="historyLog" class="font-monospace small">
                                <div class="text-muted text-center">Monitoring belum dimulai...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
        // Global variables
        let monitoringInterval;
        let isMonitoring = false;
        let currentInterval = 5000;
        let chart;
        let historyData = [];
        let totalRequests = 0;
        let totalErrors = 0;
        let responseSum = 0;

        // API endpoints configuration
        const endpoints = {
            production: {
                peserta: "monitoring_controller.php?param=nik&noka=6201052510750001",
                rujukan: "monitoring_controller.php?param=rujukan",
                antrol: "monitoring_controller.php?param=antrol",
                sep: "monitoring_controller.php?param=sep"
            },
            cdn: {
                peserta: "monitoring_controller.php?param=nik&noka=6201052510750001&cdn=1",
                rujukan: "monitoring_controller.php?param=rujukan&cdn=1", 
                antrol: "monitoring_controller.php?param=antrol&cdn=1",
                sep: "monitoring_controller.php?param=sep&cdn=1"
            }
        };

        // Initialize Highcharts
        Highcharts.setOptions({
            time: {
                timezone: 'Asia/Jakarta',
                useUTC: false
            }
        });

        // Initialize chart
        function initChart() {
            chart = Highcharts.chart('chartContainer', {
                chart: {
                    type: 'spline',
                    animation: Highcharts.svg,
                    backgroundColor: 'transparent'
                },
                title: {
                    text: 'BPJS API Response Time Monitoring',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Real-time monitoring VCLAIM dan ANTROL API'
                },
                xAxis: {
                    type: 'datetime',
                    labels: {
                        format: '{value:%H:%M:%S}'
                    },
                    title: {
                        text: 'Waktu (WIB)'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Response Time (ms)'
                    },
                    min: 0
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br/>',
                    pointFormat: '{point.x:%H:%M:%S}: <b>{point.y}</b> ms'
                },
                legend: {
                    enabled: true
                },
                exporting: {
                    enabled: true
                },
                series: [{
                    name: 'Peserta VCLAIM',
                    data: [],
                    color: '#28a745'
                }, {
                    name: 'Rujukan VCLAIM', 
                    data: [],
                    color: '#007bff'
                }, {
                    name: 'Antrol/HFIS',
                    data: [],
                    color: '#ffc107'
                }, {
                    name: 'SEP VCLAIM',
                    data: [],
                    color: '#17a2b8'
                }]
            });
        }

        // Update status card
        function updateStatusCard(service, status, responseTime, lastCheck) {
            const statusElement = document.getElementById(`status-${service}`);
            const responseElement = document.getElementById(`response-${service}`);
            const lastCheckElement = document.getElementById(`lastCheck-${service}`);
            const cardElement = document.getElementById(`card-${service}`);

            // Update status
            if (status === 'online') {
                statusElement.innerHTML = '<i class="bi bi-circle-fill text-success"></i> ONLINE';
                cardElement.className = 'card status-card status-online';
            } else if (status === 'warning') {
                statusElement.innerHTML = '<i class="bi bi-circle-fill text-warning"></i> SLOW';
                cardElement.className = 'card status-card status-warning';
            } else {
                statusElement.innerHTML = '<i class="bi bi-circle-fill text-danger"></i> OFFLINE';
                cardElement.className = 'card status-card status-offline';
            }

            // Update response time
            responseElement.textContent = responseTime !== null ? `${responseTime} ms` : '- ms';
            
            // Update last check
            lastCheckElement.textContent = lastCheck;
        }

        // Check API endpoint
        function checkEndpoint(service, url, seriesIndex) {
            const startTime = performance.now();
            
            return new Promise((resolve) => {
                $.ajax({
                    url: url,
                    type: 'GET',
                    timeout: 10000,
                    success: function(data, textStatus, xhr) {
                        const endTime = performance.now();
                        const responseTime = Math.round(endTime - startTime);
                        const currentTime = new Date().getTime();
                        
                        // Determine status based on response and response time
                        let status = 'offline';
                        if (data && data.status === 'success') {
                            if (responseTime < 2000) {
                                status = 'online';
                            } else if (responseTime < 5000) {
                                status = 'warning';
                            }
                        } else if (xhr.status === 200 && responseTime < 3000) {
                            status = 'warning'; // Response received but might have API errors
                        }
                        
                        // Add to chart
                        if (chart && chart.series[seriesIndex]) {
                            chart.series[seriesIndex].addPoint([currentTime, responseTime], true, 
                                chart.series[seriesIndex].data.length >= 50);
                        }
                        
                        // Update metrics
                        totalRequests++;
                        responseSum += responseTime;
                        
                        // Save to storage via API
                        saveMonitoringData(service, {
                            status: status,
                            response_time: responseTime,
                            endpoint_type: document.getElementById('endpointSelect').value,
                            success: true,
                            http_code: xhr.status
                        });
                        
                        const result = {
                            service: service,
                            status: status,
                            responseTime: responseTime,
                            timestamp: new Date(),
                            success: true,
                            data: data
                        };
                        
                        resolve(result);
                    },
                    error: function(xhr, status, error) {
                        const endTime = performance.now();
                        const responseTime = Math.round(endTime - startTime);
                        
                        totalRequests++;
                        totalErrors++;
                        
                        // Save error to storage
                        saveMonitoringData(service, {
                            status: 'offline',
                            response_time: responseTime,
                            endpoint_type: document.getElementById('endpointSelect').value,
                            success: false,
                            error_message: error,
                            http_code: xhr.status || 0
                        });
                        
                        const result = {
                            service: service,
                            status: 'offline',
                            responseTime: null,
                            timestamp: new Date(),
                            success: false,
                            error: error
                        };
                        
                        resolve(result);
                    }
                });
            });
        }

        // Save monitoring data to storage
        function saveMonitoringData(service, data) {
            // This could be enhanced to send data to monitoring storage
            // For now, we'll just store in localStorage for persistence
            const key = `monitoring_${service}_${new Date().toDateString()}`;
            let storedData = JSON.parse(localStorage.getItem(key) || '[]');
            
            const entry = {
                ...data,
                timestamp: Date.now(),
                datetime: new Date().toISOString()
            };
            
            storedData.push(entry);
            
            // Keep only last 100 entries per service per day
            if (storedData.length > 100) {
                storedData = storedData.slice(-100);
            }
            
            localStorage.setItem(key, JSON.stringify(storedData));
        }

        // Run monitoring cycle
        async function runMonitoringCycle() {
            if (!isMonitoring) return;

            const endpointType = document.getElementById('endpointSelect').value;
            const currentEndpoints = endpoints[endpointType];

            const promises = [
                checkEndpoint('peserta', currentEndpoints.peserta, 0),
                checkEndpoint('rujukan', currentEndpoints.rujukan, 1),
                checkEndpoint('antrol', currentEndpoints.antrol, 2),
                checkEndpoint('sep', currentEndpoints.sep, 3)
            ];

            try {
                const results = await Promise.all(promises);
                
                results.forEach(result => {
                    // Update status card
                    updateStatusCard(
                        result.service,
                        result.status,
                        result.responseTime,
                        result.timestamp.toLocaleTimeString('id-ID')
                    );

                    // Add to history
                    addToHistory(result);
                });

                // Update metrics
                updateMetrics();
                
                // Update last update time
                document.getElementById('lastUpdate').textContent = 
                    `Last update: ${new Date().toLocaleTimeString('id-ID')}`;

            } catch (error) {
                console.error('Error in monitoring cycle:', error);
            }
        }

        // Add to history log
        function addToHistory(result) {
            historyData.unshift(result);
            if (historyData.length > 50) {
                historyData.pop();
            }

            const historyLog = document.getElementById('historyLog');
            const statusClass = result.status === 'online' ? 'text-success' : 
                               result.status === 'warning' ? 'text-warning' : 'text-danger';
            
            const logEntry = document.createElement('div');
            logEntry.className = 'border-bottom pb-1 mb-1';
            logEntry.innerHTML = `
                <span class="text-muted">${result.timestamp.toLocaleTimeString('id-ID')}</span> - 
                <span class="fw-semibold">${result.service.toUpperCase()}</span>: 
                <span class="${statusClass}">${result.status.toUpperCase()}</span>
                ${result.responseTime !== null ? `(${result.responseTime}ms)` : '(ERROR)'}
            `;

            if (historyLog.firstChild && historyLog.firstChild.textContent.includes('Monitoring belum dimulai')) {
                historyLog.innerHTML = '';
            }
            
            historyLog.insertBefore(logEntry, historyLog.firstChild);
        }

        // Update metrics
        function updateMetrics() {
            const avgResponse = totalRequests > 0 ? Math.round(responseSum / (totalRequests - totalErrors)) : 0;
            const successRate = totalRequests > 0 ? Math.round(((totalRequests - totalErrors) / totalRequests) * 100) : 0;

            document.getElementById('avgResponse').textContent = `${avgResponse} ms`;
            document.getElementById('successRate').textContent = `${successRate}%`;
            document.getElementById('totalRequests').textContent = totalRequests;
            document.getElementById('totalErrors').textContent = totalErrors;
        }

        // Start monitoring
        function startMonitoring() {
            if (isMonitoring) return;
            
            isMonitoring = true;
            currentInterval = parseInt(document.getElementById('intervalSelect').value);
            
            document.getElementById('monitoringStatus').innerHTML = 
                '<i class="bi bi-circle-fill text-success"></i> MONITORING AKTIF';
            document.getElementById('startBtn').disabled = true;
            document.getElementById('stopBtn').disabled = false;

            // Run first cycle immediately
            runMonitoringCycle();
            
            // Set interval for subsequent cycles
            monitoringInterval = setInterval(runMonitoringCycle, currentInterval);
        }

        // Stop monitoring
        function stopMonitoring() {
            isMonitoring = false;
            
            if (monitoringInterval) {
                clearInterval(monitoringInterval);
            }
            
            document.getElementById('monitoringStatus').innerHTML = 
                '<i class="bi bi-circle-fill text-danger"></i> MONITORING STOPPED';
            document.getElementById('startBtn').disabled = false;
            document.getElementById('stopBtn').disabled = true;
        }

        // Clear data
        function clearData() {
            // Clear chart data
            if (chart) {
                chart.series.forEach(series => {
                    series.setData([]);
                });
            }
            
            // Clear history
            historyData = [];
            document.getElementById('historyLog').innerHTML = 
                '<div class="text-muted text-center">Data cleared...</div>';
            
            // Reset metrics
            totalRequests = 0;
            totalErrors = 0;
            responseSum = 0;
            updateMetrics();
            
            // Reset status cards
            ['peserta', 'rujukan', 'antrol', 'sep'].forEach(service => {
                updateStatusCard(service, 'offline', null, 'Never checked');
            });
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            initChart();
            
            document.getElementById('startBtn').addEventListener('click', startMonitoring);
            document.getElementById('stopBtn').addEventListener('click', stopMonitoring);
            document.getElementById('clearBtn').addEventListener('click', clearData);
            
            document.getElementById('intervalSelect').addEventListener('change', function() {
                if (isMonitoring) {
                    stopMonitoring();
                    setTimeout(startMonitoring, 100);
                }
            });

            // Auto-start monitoring
            setTimeout(startMonitoring, 1000);
        });

        // Save/Load data from localStorage for persistence
        function saveDataToStorage() {
            const data = {
                historyData: historyData,
                totalRequests: totalRequests,
                totalErrors: totalErrors,
                responseSum: responseSum
            };
            localStorage.setItem('bpjs_monitoring_data', JSON.stringify(data));
        }

        function loadDataFromStorage() {
            const stored = localStorage.getItem('bpjs_monitoring_data');
            if (stored) {
                const data = JSON.parse(stored);
                historyData = data.historyData || [];
                totalRequests = data.totalRequests || 0;
                totalErrors = data.totalErrors || 0;
                responseSum = data.responseSum || 0;
                updateMetrics();
            }
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', loadDataFromStorage);

        // Save data periodically
        setInterval(saveDataToStorage, 30000); // Save every 30 seconds
    </script>
</body>
</html>
