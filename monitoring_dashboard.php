<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPJS Monitoring Dashboard</title>
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
        
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .control-panel {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .interval-selector {
            background: linear-gradient(45deg, #28a745, #20c997);
            border-radius: 10px;
            padding: 15px;
            color: white;
            margin-bottom: 20px;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            min-height: 500px;
        }
        
        .status-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px;
        }
        
        .status-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 5px solid #6c757d;
        }
        
        .status-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .status-card.online {
            border-left-color: #28a745;
        }
        
        .status-card.warning {
            border-left-color: #ffc107;
        }
        
        .status-card.offline {
            border-left-color: #dc3545;
        }
        
        .status-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .status-icon.online {
            color: #28a745;
        }
        
        .status-icon.warning {
            color: #ffc107;
        }
        
        .status-icon.offline {
            color: #dc3545;
        }
        
        .response-time {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .last-update {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .metrics-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px;
        }
        
        .metric-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        /* Enhanced response details styling */
        .http-status {
            margin-top: 8px;
        }
        
        .response-details {
            margin-top: 5px;
            line-height: 1.4;
        }
        
        .response-details .badge {
            margin: 2px;
            font-size: 0.75rem;
        }
        
        .response-details small {
            color: #6c757d;
            font-size: 0.7rem;
            display: block;
            margin-top: 3px;
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .metric-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .btn-control {
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-control:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .legend-item {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        
        .legend-color {
            width: 20px;
            height: 4px;
            border-radius: 2px;
            margin-right: 8px;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255,255,255,0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .alert-custom {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .service-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .service-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
            }
            
            .status-cards {
                grid-template-columns: 1fr;
                margin: 10px;
            }
            
            .metrics-row {
                grid-template-columns: repeat(2, 1fr);
                margin: 10px;
            }
            
            .chart-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
        </div>

        <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-content">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="bi bi-activity"></i>
                    BPJS Monitoring Dashboard
                </h1>
                <p class="lead mb-0">Real-time monitoring untuk VCLAIM dan ANTROL API</p>
                <small class="d-block mt-2 opacity-75">Combined dashboard dengan monitoring semua endpoint</small>
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
                    <a class="nav-link active" href="#combined" data-bs-toggle="tab">
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
            <div class="interval-selector">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="intervalSelect" class="form-label fw-semibold mb-0">
                            <i class="bi bi-clock-history me-2"></i>Interval Monitoring
                        </label>
                    </div>
                    <div class="col-md-3">
                        <select id="intervalSelect" class="form-select">
                            <option value="1000">1 detik</option>
                            <option value="5000">5 detik</option>
                            <option value="10000" selected>10 detik</option>
                            <option value="30000">30 detik</option>
                            <option value="60000">1 menit</option>
                            <option value="300000">5 menit</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="endpointSelect" class="form-select">
                            <option value="production">Non CDN</option>
                            <option value="cdn" selected>CDN</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button id="startBtn" class="btn btn-success btn-control btn-sm">
                                <i class="bi bi-play-fill"></i>
                            </button>
                            <button id="stopBtn" class="btn btn-danger btn-control btn-sm">
                                <i class="bi bi-stop-fill"></i>
                            </button>
                            <button id="clearBtn" class="btn btn-warning btn-control btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Info -->
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-custom alert-info mb-0">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <div>
                                <strong>Status Monitoring:</strong>
                                <span id="monitoringStatus" class="ms-2">Stopped</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-custom alert-secondary mb-0">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock me-2"></i>
                            <div>
                                <strong>Last Update:</strong>
                                <span id="lastUpdate" class="ms-2">Never</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Row -->
        <div class="metrics-row">
            <div class="metric-card">
                <i class="bi bi-speedometer2 mb-2" style="font-size: 1.5rem;"></i>
                <div class="metric-value" id="avgResponse">0 ms</div>
                <div class="metric-label">Avg Response Time</div>
            </div>
            <div class="metric-card">
                <i class="bi bi-check-circle mb-2" style="font-size: 1.5rem;"></i>
                <div class="metric-value" id="successRate">0%</div>
                <div class="metric-label">Success Rate</div>
            </div>
            <div class="metric-card">
                <i class="bi bi-graph-up mb-2" style="font-size: 1.5rem;"></i>
                <div class="metric-value" id="totalRequests">0</div>
                <div class="metric-label">Total Requests</div>
            </div>
            <div class="metric-card">
                <i class="bi bi-exclamation-triangle mb-2" style="font-size: 1.5rem;"></i>
                <div class="metric-value" id="totalErrors">0</div>
                <div class="metric-label">Total Errors</div>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="status-cards">
            <div class="status-card offline" id="card-peserta">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="service-title">Peserta VCLAIM</div>
                        <div class="service-subtitle">Data peserta by NIK/NOKA</div>
                    </div>
                    <i class="bi bi-person-check status-icon offline"></i>
                </div>
                <div class="response-time" id="response-peserta">- ms</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span id="status-peserta" class="badge bg-secondary">OFFLINE</span>
                    <small class="last-update" id="lastCheck-peserta">Ready to check</small>
                </div>
            </div>

            <div class="status-card offline" id="card-rujukan">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="service-title">Rujukan VCLAIM</div>
                        <div class="service-subtitle">Data rujukan pasien</div>
                    </div>
                    <i class="bi bi-file-medical status-icon offline"></i>
                </div>
                <div class="response-time" id="response-rujukan">- ms</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span id="status-rujukan" class="badge bg-secondary">OFFLINE</span>
                    <small class="last-update" id="lastCheck-rujukan">Ready to check</small>
                </div>
            </div>

            <div class="status-card offline" id="card-diagnosa">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="service-title">Diagnosa VCLAIM</div>
                        <div class="service-subtitle">Referensi diagnosa</div>
                    </div>
                    <i class="bi bi-clipboard-data status-icon offline"></i>
                </div>
                <div class="response-time" id="response-diagnosa">- ms</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span id="status-diagnosa" class="badge bg-secondary">OFFLINE</span>
                    <small class="last-update" id="lastCheck-diagnosa">Ready to check</small>
                </div>
            </div>

            <div class="status-card offline" id="card-antrol">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="service-title">Antrol</div>
                        <div class="service-subtitle">Sistem antrian online</div>
                    </div>
                    <i class="bi bi-list-ol status-icon offline"></i>
                </div>
                <div class="response-time" id="response-antrol">- ms</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span id="status-antrol" class="badge bg-secondary">OFFLINE</span>
                    <small class="last-update" id="lastCheck-antrol">Ready to check</small>
                </div>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="chart-container">
            <h5 class="mb-3">
                <i class="bi bi-graph-up me-2"></i>
                Monitoring Chart Bridging BPJS (VCLAIM dan ANTROL)
            </h5>
            
            <!-- Chart Legend -->
            <div class="mb-3">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #28a745;"></div>
                    <span>Peserta</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #007bff;"></div>
                    <span>Rujukan</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #ffc107;"></div>
                    <span>Diagnosa</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #17a2b8;"></div>
                    <span>Antrol</span>
                </div>
            </div>
            
            <div id="chartContainer" style="height: 400px;"></div>
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
        let totalRequests = 0;
        let totalErrors = 0;
        let responseSum = 0;
        let successCount = 0;

        // API endpoints configuration
        const endpoints = {
            production: {
                peserta: "monitoring_controller.php?param=peserta",
                rujukan: "monitoring_controller.php?param=rujukan", 
                diagnosa: "monitoring_controller.php?param=diagnosa",
                antrol: "monitoring_controller.php?param=antrol"
            },
            cdn: {
                peserta: "monitoring_controller.php?param=peserta&cdn=1",
                rujukan: "monitoring_controller.php?param=rujukan&cdn=1",
                diagnosa: "monitoring_controller.php?param=diagnosa&cdn=1", 
                antrol: "monitoring_controller.php?param=antrol&cdn=1"
            }
        };

        // Initialize Highcharts with error handling
        try {
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
            console.log("Highcharts configuration loaded successfully");
        } catch (error) {
            console.error("Error in Highcharts configuration:", error);
        }

        // Initialize chart
        function initChart() {
            chart = Highcharts.chart('chartContainer', {
                accessibility: {
                    enabled: false
                },
                chart: {
                    type: 'spline',
                    animation: Highcharts.svg,
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
                    },
                    gridLineWidth: 1,
                    gridLineColor: 'rgba(0,0,0,0.1)'
                },
                yAxis: {
                    title: {
                        text: 'Response Time (ms)'
                    },
                    min: 0,
                    gridLineColor: 'rgba(0,0,0,0.1)',
                    plotLines: [{
                        value: 1000,
                        color: '#28a745',
                        dashStyle: 'Dash',
                        width: 2,
                        label: {
                            text: 'Normal/Lancar (< 1s)',
                            align: 'right',
                            style: { color: '#28a745', fontWeight: 'bold' }
                        }
                    }, {
                        value: 3000,
                        color: '#ffc107',
                        dashStyle: 'Dash',
                        width: 2,
                        label: {
                            text: 'Lambat (< 3s)',
                            align: 'right',
                            style: { color: '#ffc107', fontWeight: 'bold' }
                        }
                    }, {
                        value: 5000,
                        color: '#dc3545',
                        dashStyle: 'Dash',
                        width: 2,
                        label: {
                            text: 'Sangat Lambat (> 5s)',
                            align: 'right',
                            style: { color: '#dc3545', fontWeight: 'bold' }
                        }
                    }]
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br/>',
                    pointFormat: '{point.x:%H:%M:%S}: <b>{point.y}</b> ms',
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    borderWidth: 1,
                    borderRadius: 10,
                    shadow: true
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    spline: {
                        marker: {
                            enabled: true,
                            radius: 4
                        },
                        lineWidth: 3
                    }
                },
                series: [{
                    name: 'Peserta',
                    data: [],
                    color: '#28a745'
                }, {
                    name: 'Rujukan',
                    data: [],
                    color: '#007bff'
                }, {
                    name: 'Diagnosa',
                    data: [],
                    color: '#ffc107'
                }, {
                    name: 'Antrol',
                    data: [],
                    color: '#17a2b8'
                }]
            });
        }

        // Update status card with BPJS validation display
        function updateStatusCard(service, status, responseTime, lastCheck, result) {
            try {
                const cardElement = document.getElementById(`card-${service}`);
                const statusElement = document.getElementById(`status-${service}`);
                const responseElement = document.getElementById(`response-${service}`);
                const lastCheckElement = document.getElementById(`lastCheck-${service}`);
                
                if (!cardElement || !statusElement || !responseElement || !lastCheckElement) {
                    console.error(`[${service}] Missing DOM elements for status card`);
                    return;
                }
                
                const iconElement = cardElement.querySelector('.status-icon');

            // Remove all status classes
            cardElement.classList.remove('online', 'warning', 'offline');
            iconElement.classList.remove('online', 'warning', 'offline');

            // Update status with smooth animation
            cardElement.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';

            // Enhanced status display with BPJS validation info
            let statusText = 'OFFLINE';
            let statusBadgeClass = 'badge bg-danger';
            let statusDetails = '';

            if (status === 'online') {
                cardElement.classList.add('online');
                iconElement.classList.add('online');
                statusText = 'ONLINE';
                statusBadgeClass = 'badge bg-success';
                
                // Add BPJS validation details
                if (result && result.bpjsCode) {
                    statusDetails = ` (BPJS: ${result.bpjsCode})`;
                } else if (result && result.validation && (result.validation.is_valid || ['201', '1', 201, 1].includes(result.validation.error_code))) {
                    statusDetails = ' (BPJS: Valid)';
                }
            } else if (status === 'warning') {
                cardElement.classList.add('warning');
                iconElement.classList.add('warning');
                statusText = 'SLOW';
                statusBadgeClass = 'badge bg-warning';
                
                if (result && result.bpjsMessage) {
                    statusDetails = ` (${result.bpjsMessage.substring(0, 20)}...)`;
                }
            } else {
                cardElement.classList.add('offline');
                iconElement.classList.add('offline');
                statusText = 'OFFLINE';
                statusBadgeClass = 'badge bg-danger';
                
                // Show BPJS error details
                if (result && result.bpjsCode && result.bpjsCode !== '200') {
                    statusDetails = ` (BPJS: ${result.bpjsCode})`;
                } else if (result && result.validation && result.validation.message) {
                    const msg = result.validation.message.substring(0, 25);
                    statusDetails = ` (${msg}...)`;
                } else if (result && result.httpCode && result.httpCode !== 200) {
                    statusDetails = ` (HTTP: ${result.httpCode})`;
                }
            }

            statusElement.className = statusBadgeClass;
            statusElement.textContent = statusText + statusDetails;

            // Update response time with color coding
            if (responseTime !== null) {
                let responseClass = '';
                if (responseTime < 1000) {
                    responseClass = 'text-success';
                } else if (responseTime < 3000) {
                    responseClass = 'text-warning';
                } else {
                    responseClass = 'text-danger';
                }
                responseElement.innerHTML = `<span class="${responseClass}">${responseTime} ms</span>`;
            } else {
                responseElement.textContent = '- ms';
            }
            
            // Add HTTP status display - using safer DOM manipulation
            let httpStatusEl = cardElement.querySelector('.http-status');
            if (!httpStatusEl) {
                httpStatusEl = document.createElement('div');
                httpStatusEl.className = 'http-status small mt-1';
                // Insert after response element instead of complex insertBefore
                if (responseElement && responseElement.nextSibling) {
                    responseElement.parentNode.insertBefore(httpStatusEl, responseElement.nextSibling);
                } else if (responseElement && responseElement.parentNode) {
                    responseElement.parentNode.appendChild(httpStatusEl);
                } else {
                    cardElement.appendChild(httpStatusEl);
                }
            }
            
            const httpCode = result ? result.httpCode : null;
            const apiResponse = result ? result.apiResponse : null;
            const hasValidData = result ? result.hasValidData : null;
            
            if (httpCode) {
                let httpClass = 'badge ';
                let statusText = '';
                if (httpCode >= 200 && httpCode < 300) {
                    httpClass += 'bg-success';
                    statusText = 'Success';
                } else if (httpCode >= 400 && httpCode < 500) {
                    httpClass += 'bg-warning text-dark';
                    statusText = 'Client Error';
                } else if (httpCode >= 500) {
                    httpClass += 'bg-danger';
                    statusText = 'Server Error';
                } else {
                    httpClass += 'bg-secondary';
                    statusText = 'Unknown';
                }
                
                // Add API response status
                let apiStatusBadge = '';
                let responseStatusInfo = '';
                if (apiResponse && hasValidData !== undefined) {
                    if (hasValidData) {
                        apiStatusBadge = ' <span class="badge bg-info">API OK</span>';
                        responseStatusInfo = ` <span class="badge bg-light text-dark">Response: ${apiResponse}</span>`;
                    } else {
                        apiStatusBadge = ' <span class="badge bg-warning text-dark">API Error</span>';
                        responseStatusInfo = ` <span class="badge bg-light text-dark">Response: ${apiResponse || 'invalid'}</span>`;
                    }
                }
                
                httpStatusEl.innerHTML = `<span class="${httpClass}">HTTP ${httpCode} - ${statusText}</span>${apiStatusBadge}${responseStatusInfo}`;
            } else {
                httpStatusEl.innerHTML = '<span class="badge bg-secondary">HTTP - No Response</span>';
            }
            
            // Add additional response details section - using safer DOM manipulation  
            let responseDetailsEl = cardElement.querySelector('.response-details');
            if (!responseDetailsEl) {
                responseDetailsEl = document.createElement('div');
                responseDetailsEl.className = 'response-details small mt-1';
                // Insert after httpStatusEl instead of complex insertBefore
                if (httpStatusEl && httpStatusEl.nextSibling) {
                    httpStatusEl.parentNode.insertBefore(responseDetailsEl, httpStatusEl.nextSibling);
                } else if (httpStatusEl && httpStatusEl.parentNode) {
                    httpStatusEl.parentNode.appendChild(responseDetailsEl);
                } else {
                    cardElement.appendChild(responseDetailsEl);
                }
            }
            
            // Show additional response information
            if (result) {
                let detailsHtml = '';
                
                // Data validation status
                if (result.hasValidData) {
                    detailsHtml += '<span class="badge bg-success-subtle text-success">✓ Valid Data Structure</span> ';
                } else {
                    detailsHtml += '<span class="badge bg-danger-subtle text-danger">✗ Invalid Data Structure</span> ';
                }
                
                // Error message if available
                if (result.errorMessage) {
                    detailsHtml += `<span class="badge bg-warning-subtle text-warning" title="${result.errorMessage}">⚠ Error</span> `;
                }
                
                // Response size indicator
                if (result.responseSize !== undefined) {
                    if (result.responseSize > 0) {
                        detailsHtml += `<span class="badge bg-info-subtle text-info">${(result.responseSize / 1024).toFixed(1)}KB</span> `;
                    } else {
                        detailsHtml += '<span class="badge bg-secondary-subtle text-secondary">0 KB</span> ';
                    }
                }
                
                // Data keys preview
                if (result.dataKeys && result.dataKeys !== 'Error - No data received') {
                    const keyPreview = result.dataKeys.length > 50 ? result.dataKeys.substring(0, 50) + '...' : result.dataKeys;
                    detailsHtml += `<br><small class="text-muted">Keys: ${keyPreview}</small>`;
                }
                
                responseDetailsEl.innerHTML = detailsHtml;
            } else {
                responseDetailsEl.innerHTML = '<span class="badge bg-secondary-subtle text-secondary">No API Response Data</span>';
            }
            
            // Update last check
            lastCheckElement.textContent = lastCheck;
            
            } catch (error) {
                console.error(`[${service}] Error updating status card:`, error);
            }
        }

        // Check API endpoint with smooth animation
        function checkEndpoint(service, url, seriesIndex) {
            const startTime = performance.now();
            
            // Add smooth loading animation to status card
            const cardElement = document.getElementById(`card-${service}`);
            cardElement.style.opacity = '0.7';
            cardElement.style.transform = 'scale(0.98)';
            
            return new Promise((resolve) => {
                $.ajax({
                    url: url,
                    type: 'GET',
                    timeout: 10000,
                    success: function(data, textStatus, xhr) {
                        const endTime = performance.now();
                        const responseTime = Math.round(endTime - startTime);
                        const currentTime = new Date().getTime();
                        
                        // Determine status based on BPJS validation
                        let status = 'offline';
                        let isSuccess = false;
                        let statusMessage = 'Unknown';
                        let bpjsCode = null;
                        
                        // Check BPJS validation from new API response
                        if (data && data.validation) {
                            // Consider codes 200, 201, and 1 as valid
                            isSuccess = data.validation.is_valid || ['201', '1', 201, 1].includes(data.validation.error_code);
                            bpjsCode = data.validation.error_code;
                            statusMessage = data.validation.message;
                            
                            if (isSuccess) {
                                // Valid BPJS response - determine status by response time
                                if (responseTime < 2000) {
                                    status = 'online';
                                } else if (responseTime < 8000) {
                                    status = 'warning';
                                } else {
                                    status = 'offline';
                                }
                            } else {
                                // Invalid BPJS response regardless of HTTP status
                                status = 'offline';
                            }
                        } else {
                            // Fallback to old logic if validation not available
                            if (xhr.status === 200) {
                                if (data) {
                                    isSuccess = true;
                                    statusMessage = 'HTTP 200 - Basic check passed';
                                    if (responseTime < 2000) {
                                        status = 'online';
                                    } else if (responseTime < 8000) {
                                        status = 'warning';
                                    } else {
                                        status = 'offline';
                                    }
                                } else {
                                    status = 'warning';
                                    statusMessage = 'HTTP 200 but no data';
                                    isSuccess = false;
                                }
                            } else if (xhr.status >= 200 && xhr.status < 400) {
                                isSuccess = true;
                                status = 'warning';
                                statusMessage = `HTTP ${xhr.status} - Non-standard success`;
                            } else {
                                status = 'offline';
                                statusMessage = `HTTP ${xhr.status} - Error response`;
                                isSuccess = false;
                            }
                        }
                        
                        // Add to chart with smooth animation
                        if (chart && chart.series[seriesIndex]) {
                            chart.series[seriesIndex].addPoint([currentTime, responseTime], true, 
                                chart.series[seriesIndex].data.length >= 20);
                        }
                        
                        // Update metrics
                        totalRequests++;
                        responseSum += responseTime;
                        if (isSuccess) {
                            successCount++;
                        } else {
                            totalErrors++;
                        }
                        
                        // Restore card animation
                        setTimeout(() => {
                            cardElement.style.opacity = '1';
                            cardElement.style.transform = 'scale(1)';
                        }, 100);
                        
                        const result = {
                            service: service,
                            status: status,
                            responseTime: responseTime,
                            timestamp: new Date(),
                            success: isSuccess,
                            data: data,
                            httpCode: xhr.status,
                            httpStatus: xhr.statusText || 'Unknown',
                            bpjsStatus: data && data.bpjs_status ? data.bpjs_status : 'unknown',
                            bpjsCode: bpjsCode,
                            bpjsMessage: statusMessage,
                            validation: data && data.validation ? data.validation : null,
                            apiResponse: data && data.status ? data.status : (xhr.status === 200 ? 'No status field' : 'Error'),
                            hasValidData: data && data.validation ? 
                                (data.validation.is_valid || ['200', '201', '1', 200, 201, 1].includes(data.validation.error_code)) : false,
                            errorMessage: data && data.message ? data.message : null,
                            dataKeys: data ? Object.keys(data).join(', ') : 'No data',
                            responseSize: JSON.stringify(data || {}).length
                        };
                        
                        resolve(result);
                    },
                    error: function(xhr, status, error) {
                        const endTime = performance.now();
                        const responseTime = Math.round(endTime - startTime);
                        
                        totalRequests++;
                        totalErrors++;
                        
                        // Restore card animation
                        setTimeout(() => {
                            cardElement.style.opacity = '1';
                            cardElement.style.transform = 'scale(1)';
                        }, 100);
                        
                        const result = {
                            service: service,
                            status: 'offline',
                            responseTime: responseTime > 0 ? responseTime : null,
                            timestamp: new Date(),
                            success: false,
                            error: error,
                            httpCode: xhr.status || 0,
                            httpStatus: xhr.statusText || status || 'Unknown Error',
                            apiResponse: 'error',
                            hasValidData: false,
                            errorType: status, // timeout, error, abort, etc.
                            errorMessage: error || 'Network error or timeout',
                            dataKeys: 'Error - No data received',
                            responseSize: 0
                        };
                        
                        resolve(result);
                    }
                });
            });
        }

        // Run monitoring cycle with staggered requests for smoother experience
        async function runMonitoringCycle() {
            if (!isMonitoring) return;

            const endpointType = document.getElementById('endpointSelect').value;
            const currentEndpoints = endpoints[endpointType];

            // Show subtle loading indicator
            document.getElementById('loadingOverlay').style.display = 'flex';
            document.getElementById('loadingOverlay').style.opacity = '0.3';

            const services = ['peserta', 'rujukan', 'diagnosa', 'antrol'];
            const promises = [];

            // Stagger requests by 200ms each for smoother experience
            services.forEach((service, index) => {
                const delay = index * 200;
                const promise = new Promise(resolve => {
                    setTimeout(() => {
                        checkEndpoint(service, currentEndpoints[service], index).then(resolve);
                    }, delay);
                });
                promises.push(promise);
            });

            try {
                const results = await Promise.all(promises);
                
                results.forEach(result => {
                    updateStatusCard(
                        result.service,
                        result.status,
                        result.responseTime,
                        result.timestamp.toLocaleTimeString(),
                        result
                    );
                });

                updateMetrics();
                
                document.getElementById('lastUpdate').textContent = 
                    new Date().toLocaleTimeString();

            } catch (error) {
                console.error('Error in monitoring cycle:', error);
            } finally {
                // Smooth hide loading overlay
                setTimeout(() => {
                    document.getElementById('loadingOverlay').style.opacity = '0';
                    setTimeout(() => {
                        document.getElementById('loadingOverlay').style.display = 'none';
                    }, 300);
                }, 500);
            }
        }

        // Update metrics
        function updateMetrics() {
            const avgResponse = totalRequests > 0 ? Math.round(responseSum / totalRequests) : 0;
            const successRate = totalRequests > 0 ? Math.round((successCount / totalRequests) * 100) : 0;

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
            
            document.getElementById('monitoringStatus').textContent = 'Running';
            document.getElementById('monitoringStatus').className = 'badge bg-success ms-2';
            document.getElementById('startBtn').disabled = true;
            document.getElementById('stopBtn').disabled = false;

            runMonitoringCycle();
            monitoringInterval = setInterval(runMonitoringCycle, currentInterval);
        }

        // Stop monitoring
        function stopMonitoring() {
            isMonitoring = false;
            
            if (monitoringInterval) {
                clearInterval(monitoringInterval);
            }
            
            document.getElementById('monitoringStatus').textContent = 'Stopped';
            document.getElementById('monitoringStatus').className = 'badge bg-danger ms-2';
            document.getElementById('startBtn').disabled = false;
            document.getElementById('stopBtn').disabled = true;
        }

        // Clear data
        function clearData() {
            if (chart) {
                chart.series.forEach(series => {
                    series.setData([]);
                });
            }
            
            totalRequests = 0;
            totalErrors = 0;
            responseSum = 0;
            successCount = 0;
            updateMetrics();
            
            // Clear data with smooth reset
            ['peserta', 'rujukan', 'diagnosa', 'antrol'].forEach((service, index) => {
                setTimeout(() => {
                    const emptyResult = {
                        service: service,
                        status: 'offline',
                        responseTime: null,
                        timestamp: new Date(),
                        success: false,
                        httpCode: null,
                        httpStatus: 'Ready to check',
                        apiResponse: 'ready',
                        hasValidData: false,
                        errorMessage: null,
                        dataKeys: 'Not checked yet',
                        responseSize: 0
                    };
                    updateStatusCard(service, 'offline', null, 'Ready to check', emptyResult);
                }, index * 100); // Staggered reset animation
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
    </script>
        </div>
    </div>
</body>
</html>
</html>
