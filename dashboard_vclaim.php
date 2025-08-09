<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPJS VCLAIM Monitoring Dashboard</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2E8B57 0%, #228B22 100%);
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
            
            .nav-tabs {
                padding: 5px;
            }
            
            .nav-tabs .nav-link {
                font-size: 0.9rem;
                padding: 8px 12px;
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
            
            .metric-card {
                margin-bottom: 10px;
            }
            
            .status-card {
                margin-bottom: 15px;
            }
        }
        
        .header-section {
            background: linear-gradient(135deg, #2E8B57 0%, #228B22 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        /* Responsive Header */
        @media (max-width: 768px) {
            .header-section {
                padding: 20px 15px;
            }
            
            .header-section h1 {
                font-size: 2.5rem !important;
            }
            
            .header-section .lead {
                font-size: 1.1rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .header-section {
                padding: 15px 10px;
            }
            
            .header-section h1 {
                font-size: 2rem !important;
            }
            
            .header-section .lead {
                font-size: 1rem !important;
            }
            
            .header-section small {
                font-size: 0.8rem !important;
            }
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
            background: linear-gradient(135deg, #2E8B57 0%, #228B22 100%);
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
        
        .status-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            min-height: 160px;
            position: relative;
            overflow: hidden;
        }
        
        .status-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(45deg, #2E8B57, #228B22);
        }
        
        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        }
        
        .status-indicator {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin: 8px 0;
            transition: all 0.3s ease;
        }
        
        .status-online {
            background: linear-gradient(45deg, #d4edda, #c3e6cb);
            color: #155724;
            animation: pulse-success 2s infinite;
        }
        
        .status-offline {
            background: linear-gradient(45deg, #f8d7da, #f1aeb5);
            color: #721c24;
        }
        
        .status-loading {
            background: linear-gradient(45deg, #fff3cd, #ffeaa7);
            color: #856404;
        }
        
        @keyframes pulse-success {
            0%, 100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .smooth-update {
            animation: fadeIn 0.5s ease;
        }
        
        .response-time {
            font-weight: 600;
            color: #495057;
            margin: 8px 0;
            font-size: 1.1rem;
        }
        
        .response-good { color: #28a745; }
        .response-warning { color: #ffc107; }
        .response-danger { color: #dc3545; }
        
        .http-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            margin: 5px 0;
        }
        
        .http-200 { background: #d4edda; color: #155724; }
        .http-400 { background: #fff3cd; color: #856404; }
        .http-500 { background: #f8d7da; color: #721c24; }
        
        .response-details {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
            line-height: 1.4;
        }
        
        .http-status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-right: 5px;
        }
        
        .last-update {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 10px;
        }
        
        .control-panel {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            min-height: 400px;
        }
        
        /* Responsive Chart */
        @media (max-width: 768px) {
            .chart-container {
                padding: 15px;
                margin: 15px 0;
                min-height: 300px;
                border-radius: 12px;
            }
            
            #chart-container {
                height: 250px !important;
            }
        }
        
        @media (max-width: 576px) {
            .chart-container {
                padding: 10px;
                margin: 10px 0;
                min-height: 250px;
                border-radius: 10px;
            }
            
            #chart-container {
                height: 200px !important;
            }
        }
        
        .metrics-row {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .metric-card {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
            margin: 5px 0;
        }
        
        /* Responsive Metric Cards */
        @media (max-width: 768px) {
            .metric-card {
                padding: 12px;
                margin: 3px 0;
            }
        }
        
        @media (max-width: 576px) {
            .metric-card {
                padding: 10px;
                margin: 2px 0;
            }
            
            .metric-value {
                font-size: 1.5rem !important;
            }
            
            .metric-label {
                font-size: 0.8rem !important;
            }
        }
        
        .metric-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2E8B57;
        }
        
        .metric-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
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
                    <small class="d-block mt-2 opacity-75">Monitoring khusus layanan VCLAIM BPJS Kesehatan</small>
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
                    <a class="nav-link active" href="#vclaim" data-bs-toggle="tab">
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
                    <a class="nav-link" href="chart.php">
                        <i class="bi bi-graph-up"></i> <span>Chart Monitor</span>
                    </a>
                </li>
            </ul>
            
            <!-- Control Panel -->
            <div class="control-panel">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5><i class="bi bi-gear"></i> Control Panel</h5>
                        <div class="btn-group" role="group">
                            <button id="startBtn" class="btn btn-success">
                                <i class="bi bi-play-fill"></i> Start
                            </button>
                            <button id="stopBtn" class="btn btn-danger" disabled>
                                <i class="bi bi-stop-fill"></i> Stop
                            </button>
                            <button id="clearBtn" class="btn btn-warning">
                                <i class="bi bi-trash"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Refresh Interval:</label>
                        <select id="intervalSelect" class="form-select">
                            <option value="2000">2 detik</option>
                            <option value="5000">5 detik</option>
                            <option value="10000" selected>10 detik</option>
                            <option value="30000">30 detik</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Endpoint Type:</label>
                        <select id="endpointSelect" class="form-select">
                            <option value="production">Non CDN</option>
                            <option value="cdn" selected>CDN</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Metrics Row -->
            <div class="metrics-row">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <div class="metric-card">
                            <div class="metric-value" id="avgResponseTime">0 ms</div>
                            <div class="metric-label">Avg Response Time</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <div class="metric-card">
                            <div class="metric-value" id="successRate">0%</div>
                            <div class="metric-label">Success Rate</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <div class="metric-card">
                            <div class="metric-value" id="totalRequests">0</div>
                            <div class="metric-label">Total Requests</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <div class="metric-card">
                            <div class="metric-value" id="onlineServices">0/7</div>
                            <div class="metric-label">Online Services</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status Cards -->
            <div class="row px-3">
                <div class="col-12">
                    <h5 class="mb-3"><i class="bi bi-hospital"></i> VCLAIM Services</h5>
                </div>
                
                <!-- Column 1 -->
                <div class="col-lg-6 col-md-12">
                    <div class="status-card">
                        <h6><i class="bi bi-person-check"></i> Get Peserta <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-peserta" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-peserta" class="response-time">Response Time: -</div>
                        <div id="response-details-peserta" class="response-details"></div>
                        <div id="last-update-peserta" class="last-update">Last Update: -</div>
                    </div>
                    
                    <div class="status-card">
                        <h6><i class="bi bi-file-text"></i> Get SEP <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-sep" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-sep" class="response-time">Response Time: -</div>
                        <div id="response-details-sep" class="response-details"></div>
                        <div id="last-update-sep" class="last-update">Last Update: -</div>
                    </div>
                    
                    <div class="status-card">
                        <h6><i class="bi bi-list-ul"></i> Get Rujukan Multi Record <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-rujukan-multi" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-rujukan-multi" class="response-time">Response Time: -</div>
                        <div id="response-details-rujukan-multi" class="response-details"></div>
                        <div id="last-update-rujukan-multi" class="last-update">Last Update: -</div>
                    </div>
                    
                    <div class="status-card">
                        <h6><i class="bi bi-clipboard-data"></i> Get Diagnosa <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-diagnosa" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-diagnosa" class="response-time">Response Time: -</div>
                        <div id="response-details-diagnosa" class="response-details"></div>
                        <div id="last-update-diagnosa" class="last-update">Last Update: -</div>
                    </div>
                </div>
                
                <!-- Column 2 -->
                <div class="col-lg-6 col-md-12">
                    <div class="status-card">
                        <h6><i class="bi bi-file-medical"></i> Get Rujukan <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-rujukan" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-rujukan" class="response-time">Response Time: -</div>
                        <div id="response-details-rujukan" class="response-details"></div>
                        <div id="last-update-rujukan" class="last-update">Last Update: -</div>
                    </div>
                    
                    <div class="status-card">
                        <h6><i class="bi bi-list-check"></i> Get Rujukan by Nomor Kartu <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-rujukan-kartu" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-rujukan-kartu" class="response-time">Response Time: -</div>
                        <div id="response-details-rujukan-kartu" class="response-details"></div>
                        <div id="last-update-rujukan-kartu" class="last-update">Last Update: -</div>
                    </div>
                    
                    <div class="status-card">
                        <h6><i class="bi bi-card-text"></i> Get Surat Kontrol <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-surat-kontrol" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-surat-kontrol" class="response-time">Response Time: -</div>
                        <div id="response-details-surat-kontrol" class="response-details"></div>
                        <div id="last-update-surat-kontrol" class="last-update">Last Update: -</div>
                    </div>
                </div>
            </div>
            
            <!-- Chart Container -->
            <div class="chart-container">
                <h5><i class="bi bi-graph-up"></i> Response Time Chart</h5>
                <div id="chart-container" style="height: 350px;"></div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    
    <script>
        // Global variables
        let chart;
        let monitoringInterval;
        let isMonitoring = false;
        let currentInterval = 5000;
        let totalRequests = 0;
        let successCount = 0;
        let responseSum = 0;
        let onlineCount = 0;
        
        // Response time thresholds (in milliseconds)
        const RESPONSE_THRESHOLDS = {
            GOOD: 1000,    // < 1 second
            WARNING: 3000, // 1-3 seconds
            DANGER: 5000   // > 3 seconds
        };
        
        // VCLAIM endpoints
        const endpoints = {
            production: {
                peserta: "monitoring_controller.php?param=peserta",
                rujukan: "monitoring_controller.php?param=rujukan",
                sep: "monitoring_controller.php?param=sep",
                rujukan_kartu: "monitoring_controller.php?param=rujukan_kartu",
                rujukan_multi: "monitoring_controller.php?param=rujukan_multi",
                surat_kontrol: "monitoring_controller.php?param=surat_kontrol",
                diagnosa: "monitoring_controller.php?param=diagnosa"
            },
            cdn: {
                peserta: "monitoring_controller.php?param=peserta&cdn=1",
                rujukan: "monitoring_controller.php?param=rujukan&cdn=1",
                sep: "monitoring_controller.php?param=sep&cdn=1",
                rujukan_kartu: "monitoring_controller.php?param=rujukan_kartu&cdn=1",
                rujukan_multi: "monitoring_controller.php?param=rujukan_multi&cdn=1",
                surat_kontrol: "monitoring_controller.php?param=surat_kontrol&cdn=1",
                diagnosa: "monitoring_controller.php?param=diagnosa&cdn=1"
            }
        };
        
        // Configure Highcharts timezone to use browser's local timezone
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
        
        // Initialize chart with response time threshold lines
        function initChart() {
            chart = Highcharts.chart('chart-container', {
                accessibility: {
                    enabled: false
                },
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
                        format: '{value:%H:%M:%S}',
                        useHTML: false
                    },
                    title: {
                        text: 'Waktu (Local Time)'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Response Time (ms)'
                    },
                    min: 0,
                    plotLines: [{
                        value: RESPONSE_THRESHOLDS.GOOD,
                        color: '#28a745',
                        dashStyle: 'Dash',
                        width: 2,
                        label: {
                            text: 'Good (< 1s)',
                            align: 'right',
                            style: { color: '#28a745' }
                        }
                    }, {
                        value: RESPONSE_THRESHOLDS.WARNING,
                        color: '#ffc107',
                        dashStyle: 'Dash',
                        width: 2,
                        label: {
                            text: 'Warning (< 3s)',
                            align: 'right',
                            style: { color: '#ffc107' }
                        }
                    }, {
                        value: RESPONSE_THRESHOLDS.DANGER,
                        color: '#dc3545',
                        dashStyle: 'Dash',
                        width: 2,
                        label: {
                            text: 'Critical (> 5s)',
                            align: 'right',
                            style: { color: '#dc3545' }
                        }
                    }]
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br/>',
                    pointFormat: '{point.x:%A, %e %b %Y, %H:%M:%S}: <b>{point.y:,.0f}</b> ms',
                    xDateFormat: '%A, %e %B %Y, %H:%M:%S'
                },
                legend: {
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
                    name: 'Peserta',
                    data: [],
                    color: '#2E8B57'
                }, {
                    name: 'Rujukan',
                    data: [],
                    color: '#228B22'
                }, {
                    name: 'SEP',
                    data: [],
                    color: '#32CD32'
                }, {
                    name: 'Rujukan Kartu',
                    data: [],
                    color: '#90EE90'
                }, {
                    name: 'Rujukan Multi',
                    data: [],
                    color: '#98FB98'
                }, {
                    name: 'Surat Kontrol',
                    data: [],
                    color: '#00FF7F'
                }, {
                    name: 'Diagnosa',
                    data: [],
                    color: '#00FA9A'
                }]
            });
        }
        
        function getResponseTimeClass(responseTime) {
            if (responseTime < RESPONSE_THRESHOLDS.GOOD) return 'response-good';
            if (responseTime < RESPONSE_THRESHOLDS.WARNING) return 'response-warning';
            return 'response-danger';
        }
        
        function getHttpStatusClass(httpCode) {
            if (httpCode >= 200 && httpCode < 300) return 'http-200';
            if (httpCode >= 400 && httpCode < 500) return 'http-400';
            if (httpCode >= 500) return 'http-500';
            return 'http-400';
        }
        
        function updateStatusCard(service, status, responseTime, httpCode, lastUpdate, responseDetails = '') {
            // Convert underscore to dash for HTML ID compatibility
            const serviceId = service.replace(/_/g, '-');
            
            // Direct access to elements by their specific IDs
            const statusIndicator = document.getElementById(`status-${serviceId}`);
            const responseTimeEl = document.getElementById(`response-time-${serviceId}`);
            const responseDetailsEl = document.getElementById(`response-details-${serviceId}`);
            const lastUpdateEl = document.getElementById(`last-update-${serviceId}`);
            
            // Update status - with null check
            if (statusIndicator) {
                if (status === 'online') {
                    statusIndicator.className = 'status-indicator status-online';
                    statusIndicator.innerHTML = '<i class="bi bi-check-circle-fill"></i> Online';
                } else if (status === 'offline') {
                    statusIndicator.className = 'status-indicator status-offline';
                    statusIndicator.innerHTML = '<i class="bi bi-x-circle-fill"></i> Offline';
                } else {
                    statusIndicator.className = 'status-indicator status-loading';
                    statusIndicator.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';
                }
            }
            
            // Update response time with color coding - with null check
            if (responseTimeEl && responseTime !== null) {
                responseTimeEl.textContent = `Response Time: ${responseTime} ms`;
                responseTimeEl.className = `response-time ${getResponseTimeClass(responseTime)}`;
            }
            
            // Update response details with HTTP status
            if (responseDetailsEl && responseDetails) {
                let httpStatusClass = 'http-other';
                if (httpCode >= 200 && httpCode < 300) {
                    httpStatusClass = 'http-200';
                } else if (httpCode >= 400 && httpCode < 500) {
                    httpStatusClass = 'http-400';
                } else if (httpCode >= 500) {
                    httpStatusClass = 'http-500';
                }
                
                const httpBadge = `<span class="http-status-badge ${httpStatusClass}">HTTP ${httpCode || 'N/A'}</span>`;
                responseDetailsEl.innerHTML = `${httpBadge}${responseDetails}`;
            }
            
            // Update last update time - with null check
            if (lastUpdateEl) {
                lastUpdateEl.textContent = `Last Update: ${lastUpdate}`;
            }
        }
        
        function fetchServiceStatus(service, url, seriesIndex) {
            const start = performance.now();
            
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                timeout: 10000,
                success: function(data, textStatus, xhr) {
                    const end = performance.now();
                    const responseTime = Math.round(end - start);
                    const httpCode = xhr.status;
                    const now = new Date();
                    
                    // Update chart
                    if (chart && chart.series[seriesIndex]) {
                        chart.series[seriesIndex].addPoint([now.getTime(), responseTime], true, 
                            chart.series[seriesIndex].data.length >= 20);
                    }
                    
                    // Determine status based on BPJS validation
                    let status = 'offline';
                    let isValid = false;
                    let responseDetails = '';
                    
                    if (data && data.validation) {
                        // Use new BPJS validation - consider codes 200, 201, and 1 as valid
                        isValid = data.validation.is_valid || ['201', '1', 201, 1].includes(data.validation.error_code);
                        responseDetails = `BPJS Status: ${data.validation.message}`;
                        
                        if (data.validation.error_code) {
                            responseDetails += ` | Code: ${data.validation.error_code}`;
                        }
                        
                        if (isValid) {
                            status = responseTime < 3000 ? 'online' : 'warning';
                        } else {
                            status = 'offline';
                        }
                    } else {
                        // Fallback to old validation method
                        if (data && data.status === 'success') {
                            isValid = true;
                            status = 'online';
                            if (data.data && data.data.metaData) {
                                responseDetails = `API Code: ${data.data.metaData.code || 'N/A'} | Message: ${data.data.metaData.message || 'Success'}`;
                            } else if (data.message) {
                                responseDetails = `Message: ${data.message}`;
                            }
                        } else if (data && (data.meta || data.metaData)) {
                            const meta = data.meta || data.metaData;
                            const code = meta.code || 'Unknown';
                            if (['200', '201', '1', 200, 201, 1].includes(code)) {
                                isValid = true;
                                status = 'online';
                            } else {
                                status = 'offline';
                            }
                            responseDetails = `API Code: ${code} | Message: ${meta.message || 'Response'}`;
                        } else {
                            status = httpCode === 200 ? 'warning' : 'offline';
                            responseDetails = `HTTP ${httpCode} - Basic connectivity`;
                        }
                    }
                    
                    // Update status card with BPJS validation details
                    updateStatusCard(service, status, responseTime, httpCode, now.toLocaleTimeString(), responseDetails);
                    
                    // Update metrics
                    totalRequests++;
                    responseSum += responseTime;
                    if (isValid) {
                        successCount++;
                    }
                    
                    updateMetrics();
                },
                error: function(xhr, status, error) {
                    const end = performance.now();
                    const responseTime = Math.round(end - start);
                    const httpCode = xhr.status || 0;
                    const now = new Date();
                    
                    const errorDetails = `Error: ${error || status || 'Network/Timeout Error'}`;
                    updateStatusCard(service, 'offline', responseTime, httpCode, now.toLocaleTimeString(), errorDetails);
                    
                    totalRequests++;
                    responseSum += responseTime;
                    updateMetrics();
                }
            });
        }
        
        function updateMetrics() {
            const avgResponseTime = totalRequests > 0 ? Math.round(responseSum / totalRequests) : 0;
            const successRate = totalRequests > 0 ? Math.round((successCount / totalRequests) * 100) : 0;
            
            // Count online services
            onlineCount = document.querySelectorAll('.status-online').length;
            
            document.getElementById('avgResponseTime').textContent = avgResponseTime + ' ms';
            document.getElementById('successRate').textContent = successRate + '%';
            document.getElementById('totalRequests').textContent = totalRequests;
            document.getElementById('onlineServices').textContent = `${onlineCount}/7`;
        }
        
        function runMonitoringCycle() {
            if (!isMonitoring) return;
            
            const endpointType = document.getElementById('endpointSelect').value;
            const currentEndpoints = endpoints[endpointType];
            
            const services = ['peserta', 'rujukan', 'sep', 'rujukan_kartu', 'rujukan_multi', 'surat_kontrol', 'diagnosa'];
            services.forEach((service, index) => {
                if (currentEndpoints[service]) {
                    // Stagger requests to make it smoother
                    setTimeout(() => {
                        fetchServiceStatus(service, currentEndpoints[service], index);
                    }, index * 200); // 200ms delay between each request
                }
            });
        }
        
        function startMonitoring() {
            if (monitoringInterval) {
                clearInterval(monitoringInterval);
            }
            
            isMonitoring = true;
            currentInterval = parseInt(document.getElementById('intervalSelect').value);
            
            document.getElementById('startBtn').disabled = true;
            document.getElementById('stopBtn').disabled = false;
            
            // Run first cycle immediately
            runMonitoringCycle();
            
            // Set interval for subsequent cycles
            monitoringInterval = setInterval(runMonitoringCycle, currentInterval);
        }
        
        function stopMonitoring() {
            isMonitoring = false;
            
            if (monitoringInterval) {
                clearInterval(monitoringInterval);
            }
            
            document.getElementById('startBtn').disabled = false;
            document.getElementById('stopBtn').disabled = true;
        }
        
        function clearData() {
            if (chart) {
                chart.series.forEach(series => {
                    series.setData([]);
                });
            }
            
            totalRequests = 0;
            successCount = 0;
            responseSum = 0;
            onlineCount = 0;
            
            updateMetrics();
            
            ['peserta', 'rujukan', 'sep', 'rujukan_kartu', 'rujukan_multi', 'surat_kontrol', 'diagnosa'].forEach(service => {
                updateStatusCard(service, 'loading', null, null, 'Never', '');
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
                    setTimeout(startMonitoring, 500); // Smoother transition
                }
            });
            
            document.getElementById('endpointSelect').addEventListener('change', function() {
                if (isMonitoring) {
                    stopMonitoring();
                    setTimeout(startMonitoring, 500); // Smoother transition
                }
            });
            
            // Auto-start monitoring
            setTimeout(startMonitoring, 1000);
        });
    </script>
</body>
</html>
