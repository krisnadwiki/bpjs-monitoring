<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPJS Monitoring Chart</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container-fluid {
            padding: 0;
            margin: 0;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            margin: 20px;
            overflow: hidden;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .header-section {
                padding: 20px 15px;
            }
            
            .header-section h1 {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .main-container {
                margin: 5px;
                border-radius: 10px;
            }
            
            .header-section {
                padding: 15px 10px;
            }
            
            .header-section h1 {
                font-size: 1.3rem;
            }
        }
        
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        /* Responsive header styling */
        @media (max-width: 768px) {
            .header-section {
                padding: 20px;
            }
            .header-section h1 {
                font-size: 2.5rem !important;
            }
            .header-section .lead {
                font-size: 1.1rem;
            }
            .header-section small {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .header-section {
                padding: 15px;
            }
            .header-section h1 {
                font-size: 2rem !important;
            }
            .header-section .lead {
                font-size: 1rem;
            }
            .header-section small {
                font-size: 0.8rem;
            }
        }
        
        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .nav-tabs {
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
            padding: 10px;
        }
        
        .nav-tabs .nav-link {
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        
        /* Responsive Navigation */
        @media (max-width: 768px) {
            .nav-tabs {
                padding: 5px;
                border-radius: 8px 8px 0 0;
            }
            
            .nav-tabs .nav-link {
                font-size: 0.9rem;
                padding: 8px 12px;
                margin: 0 2px;
            }
            
            .nav-tabs .nav-link i {
                margin-right: 3px;
            }
        }
        
        @media (max-width: 576px) {
            .nav-tabs {
                padding: 3px;
                border-radius: 6px 6px 0 0;
            }
            
            .nav-tabs .nav-link {
                font-size: 0.8rem;
                padding: 6px 8px;
                margin: 0 1px;
            }
            
            .nav-tabs .nav-link i {
                margin-right: 2px;
                font-size: 0.9rem;
            }
            
            /* Hide text, show only icons on very small screens */
            .nav-tabs .nav-link span {
                display: none;
            }
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            min-height: 400px;
        }
        
        .control-panel {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .interval-control {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .status-indicator {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .status-running {
            background: #d4edda;
            color: #155724;
        }
        
        .status-stopped {
            background: #f8d7da;
            color: #721c24;
        }
        
        .btn-control {
            padding: 5px 15px;
            border-radius: 20px;
            border: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-control:hover {
            transform: translateY(-1px);
        }
        
        .loading-indicator {
            display: none;
            align-items: center;
            gap: 10px;
            margin-left: 15px;
        }
        
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .info-panel {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .metric-item {
            display: inline-block;
            margin: 0 15px;
        }
        
        .metric-value {
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .metric-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <!-- Header -->
            <div class="header-section">
                <div class="header-content">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-activity"></i>
                        BPJS Monitoring Dashboard
                    </h1>
                    <p class="lead mb-0">Real-time monitoring untuk VCLAIM dan ANTROL API</p>
                    <small class="d-block mt-2 opacity-75">Chart monitoring dengan visualisasi response time real-time</small>
                </div>
            </div>
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs" id="dashboardTabs">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-house"></i> <span>Home Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard_vclaim.php">
                        <i class="bi bi-hospital"></i> <span>VCLAIM Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard_antrol.php">
                        <i class="bi bi-calendar-check"></i> <span>ANTROL Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="monitoring_dashboard.php">
                        <i class="bi bi-speedometer2"></i> <span>Combined Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#chart" data-bs-toggle="tab">
                        <i class="bi bi-graph-up"></i> <span>Chart Monitor</span>
                    </a>
                </li>
            </ul>
            
            <!-- Info Panel -->
            <div class="info-panel">
                <h5 class="mb-3"><i class="bi bi-graph-up"></i> Monitoring Chart Bridging BPJS (VCLAIM dan ANTROL)</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="metric-item">
                            <div class="metric-value" id="avgResponseTime">0 ms</div>
                            <div class="metric-label">Avg Response</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-item">
                            <div class="metric-value" id="successRate">0%</div>
                            <div class="metric-label">Success Rate</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-item">
                            <div class="metric-value" id="totalRequests">0</div>
                            <div class="metric-label">Total Requests</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-item">
                            <div class="metric-value" id="currentTime">--:--:--</div>
                            <div class="metric-label">Current Time</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Control Panel -->
            <div class="control-panel">
                <div class="interval-control">
                    <label for="intervalSelect" class="form-label mb-0 fw-semibold">Interval (detik):</label>
                    <select id="intervalSelect" class="form-select" style="width: auto;">
                        <option value="1000">1 detik</option>
                        <option value="5000">5 detik</option>
                        <option value="10000" selected>10 detik</option>
                        <option value="30000">30 detik</option>
                        <option value="60000">1 menit</option>
                        <option value="300000">5 menit</option>
                    </select>
                    
                    <label for="endpointSelect" class="form-label mb-0 fw-semibold ms-3">Endpoint:</label>
                    <select id="endpointSelect" class="form-select" style="width: auto;">
                        <option value="production" selected>Production</option>
                        <option value="cdn">CDN</option>
                    </select>
                    
                    <button id="startBtn" class="btn btn-success btn-control ms-3">
                        <i class="bi bi-play-fill"></i> Start
                    </button>
                    <button id="stopBtn" class="btn btn-danger btn-control" disabled>
                        <i class="bi bi-stop-fill"></i> Stop
                    </button>
                    <button id="clearBtn" class="btn btn-warning btn-control">
                        <i class="bi bi-trash"></i> Clear
                    </button>
                    
                    <div class="status-indicator status-stopped" id="statusIndicator">
                        Monitoring Stopped
                    </div>
                    
                    <div class="loading-indicator" id="loadingIndicator">
                        <div class="loading-spinner"></div>
                        <span>Updating...</span>
                    </div>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="chart-container">
                <div id="chart-container" style="height: 500px;"></div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    
    <script>
        // Global variables
        let chart;
        let intervalId;
        let currentInterval = 5000;
        let isMonitoring = false;
        let totalRequests = 0;
        let successRequests = 0;
        let totalResponseTime = 0;

        // Configure Highcharts
        Highcharts.setOptions({
            time: {
                useUTC: false
            },
            lang: {
                months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ],
                weekdays: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
            }
        });

        // Initialize chart
        function initChart() {
            chart = Highcharts.chart('chart-container', {
                chart: {
                    type: 'spline',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: null
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
                    pointFormat: '{point.x:%A, %e %b %Y, %H:%M:%S}: <b>{point.y:,.0f}</b> ms',
                    xDateFormat: '%A, %e %B %Y, %H:%M:%S'
                },
                legend: {
                    enabled: true
                },
                exporting: {
                    enabled: true
                },
                plotOptions: {
                    spline: {
                        marker: {
                            enabled: true,
                            radius: 4
                        },
                        lineWidth: 2
                    }
                },
                series: [{
                    name: 'peserta',
                    data: [],
                    color: '#1f77b4'
                }, {
                    name: 'rujukan',
                    data: [],
                    color: '#ff7f0e'
                }, {
                    name: 'diagnosa',
                    data: [],
                    color: '#2ca02c'
                }, {
                    name: 'antrol',
                    data: [],
                    color: '#d62728'
                }]
            });
        }

        // API endpoints sesuai dengan contoh BPJS yang diberikan dan endpoint baru
        const endpoints = {
            production: {
                peserta: "monitoring_controller.php?param=peserta",
                rujukan: "monitoring_controller.php?param=rujukan",
                diagnosa: "monitoring_controller.php?param=diagnosa", 
                antrol: "monitoring_controller.php?param=antrol"  // getDokter
            },
            cdn: {
                peserta: "monitoring_controller.php?param=peserta&cdn=1",
                rujukan: "monitoring_controller.php?param=rujukan&cdn=1",
                diagnosa: "monitoring_controller.php?param=diagnosa&cdn=1",
                antrol: "monitoring_controller.php?param=antrol&cdn=1"  // getDokter
            }
        };

        // Fetch response time
        function fetchResponse(endpoint, url, seriesIndex) {
            const start = performance.now();
            
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                timeout: 10000,
                success: function(data, textStatus, xhr) {
                    const end = performance.now();
                    const time = Math.round(end - start);
                    const x = (new Date()).getTime();
                    
                    // Add point to chart
                    chart.series[seriesIndex].addPoint([x, time], true, 
                        chart.series[seriesIndex].data.length >= 20);
                    
                    // Update metrics
                    totalRequests++;
                    totalResponseTime += time;
                    if (xhr.status === 200 && data.status === 'success') {
                        successRequests++;
                    }
                    updateMetrics();
                },
                error: function() {
                    const end = performance.now();
                    const time = Math.round(end - start);
                    const x = (new Date()).getTime();
                    
                    // Add error point (null)
                    chart.series[seriesIndex].addPoint([x, null], true, 
                        chart.series[seriesIndex].data.length >= 20);
                    
                    totalRequests++;
                    updateMetrics();
                }
            });
        }

        // Update metrics display
        function updateMetrics() {
            const avgTime = totalRequests > 0 ? Math.round(totalResponseTime / totalRequests) : 0;
            const successRate = totalRequests > 0 ? Math.round((successRequests / totalRequests) * 100) : 0;
            
            document.getElementById('avgResponseTime').textContent = avgTime + ' ms';
            document.getElementById('successRate').textContent = successRate + '%';
            document.getElementById('totalRequests').textContent = totalRequests;
        }

        // Update current time
        function updateCurrentTime() {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString();
        }

        // Start monitoring
        function startMonitoring() {
            if (intervalId) clearInterval(intervalId);
            
            isMonitoring = true;
            currentInterval = parseInt(document.getElementById('intervalSelect').value);
            
            document.getElementById('startBtn').disabled = true;
            document.getElementById('stopBtn').disabled = false;
            document.getElementById('statusIndicator').textContent = 'Monitoring Running';
            document.getElementById('statusIndicator').className = 'status-indicator status-running';
            
            // Show loading indicator during requests
            function runCycle() {
                if (!isMonitoring) return;
                
                document.getElementById('loadingIndicator').style.display = 'flex';
                
                const endpointType = document.getElementById('endpointSelect').value;
                const currentEndpoints = endpoints[endpointType];
                
                fetchResponse('peserta', currentEndpoints.peserta, 0);
                fetchResponse('rujukan', currentEndpoints.rujukan, 1);
                fetchResponse('diagnosa', currentEndpoints.diagnosa, 2);
                fetchResponse('antrol', currentEndpoints.antrol, 3);
                
                // Hide loading indicator after a short delay
                setTimeout(() => {
                    document.getElementById('loadingIndicator').style.display = 'none';
                }, 1000);
            }
            
            // Run first cycle immediately
            runCycle();
            
            // Set interval for subsequent cycles
            intervalId = setInterval(runCycle, currentInterval);
        }

        // Stop monitoring
        function stopMonitoring() {
            isMonitoring = false;
            
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
            
            document.getElementById('startBtn').disabled = false;
            document.getElementById('stopBtn').disabled = true;
            document.getElementById('statusIndicator').textContent = 'Monitoring Stopped';
            document.getElementById('statusIndicator').className = 'status-indicator status-stopped';
            document.getElementById('loadingIndicator').style.display = 'none';
        }

        // Clear chart data
        function clearData() {
            chart.series.forEach(series => {
                series.setData([]);
            });
            
            totalRequests = 0;
            successRequests = 0;
            totalResponseTime = 0;
            updateMetrics();
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            initChart();
            
            // Update current time every second
            setInterval(updateCurrentTime, 1000);
            updateCurrentTime();
            
            document.getElementById('startBtn').addEventListener('click', startMonitoring);
            document.getElementById('stopBtn').addEventListener('click', stopMonitoring);
            document.getElementById('clearBtn').addEventListener('click', clearData);
            
            // Handle interval change
            document.getElementById('intervalSelect').addEventListener('change', function() {
                if (isMonitoring) {
                    stopMonitoring();
                    setTimeout(startMonitoring, 100);
                }
            });
            
            // Handle endpoint change
            document.getElementById('endpointSelect').addEventListener('change', function() {
                if (isMonitoring) {
                    stopMonitoring();
                    setTimeout(startMonitoring, 100);
                }
            });
            
            // Auto-start monitoring
            setTimeout(startMonitoring, 1000);
        });
    </script>
</body>
</html>