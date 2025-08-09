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
        
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-card {
                padding: 15px;
                margin: 10px 0;
                border-radius: 12px;
            }
            
            .container {
                padding: 0 10px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .status-card {
                padding: 15px;
                margin: 8px 0;
                min-height: 140px;
            }
        }
        
        @media (max-width: 576px) {
            .dashboard-card {
                padding: 12px;
                margin: 8px 0;
                border-radius: 10px;
            }
            
            .container {
                padding: 0 5px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .status-card {
                padding: 12px;
                margin: 5px 0;
                min-height: 120px;
            }
            
            .metric-value {
                font-size: 1.5rem !important;
            }
            
            .metric-label {
                font-size: 0.8rem !important;
            }
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
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
            background: linear-gradient(45deg, #667eea, #764ba2);
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
        
        .status-success {
            background: linear-gradient(45deg, #d4edda, #c3e6cb);
            color: #155724;
            animation: pulse-success 2s infinite;
        }
        
        .status-error {
            background: linear-gradient(45deg, #f8d7da, #f1aeb5);
            color: #721c24;
        }
        
        .status-loading {
            background: linear-gradient(45deg, #fff3cd, #ffeaa7);
            color: #856404;
        }
        
        .status-code {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        
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
        
        .http-200 { background: #d4edda; color: #155724; }
        .http-400 { background: #fff3cd; color: #856404; }
        .http-500 { background: #f8d7da; color: #721c24; }
        .http-other { background: #e2e3e5; color: #383d41; }
        
        @keyframes pulse-success {
            0%, 100% { box-shadow: 0 0 0 0 rgba(103, 126, 234, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(103, 126, 234, 0); }
        }
        
        .response-time {
            font-weight: 600;
            color: #495057;
            margin: 8px 0;
            font-size: 1.1rem;
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
        
        .metric-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .metric-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
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
        
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .endpoint-badge {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            margin-left: 8px;
            font-weight: 600;
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
                    <small class="d-block mt-2 opacity-75">Dashboard overview semua layanan BPJS Kesehatan</small>
                </div>
            </div>
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs" id="dashboardTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#home" data-bs-toggle="tab">
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
                            <button id="refreshBtn" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                            <button id="autoRefreshBtn" class="btn btn-success">
                                <i class="bi bi-play-fill"></i> Auto Start
                            </button>
                            <button id="stopRefreshBtn" class="btn btn-danger" disabled>
                                <i class="bi bi-stop-fill"></i> Stop
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Refresh Interval:</label>
                        <select id="intervalSelect" class="form-select">
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
                            <div class="metric-value" id="onlineServices">0/9</div>
                            <div class="metric-label">Online Services</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status Cards -->
            <div class="row px-3">
                <!-- VCLAIM Endpoints -->
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="bi bi-hospital"></i> VCLAIM Services</h5>
                    
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
                        <h6><i class="bi bi-file-medical"></i> Get Rujukan <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-rujukan" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-rujukan" class="response-time">Response Time: -</div>
                        <div id="response-details-rujukan" class="response-details"></div>
                        <div id="last-update-rujukan" class="last-update">Last Update: -</div>
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
                        <h6><i class="bi bi-list-check"></i> Get Rujukan by Nomor Kartu <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-rujukan-kartu" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-rujukan-kartu" class="response-time">Response Time: -</div>
                        <div id="response-details-rujukan-kartu" class="response-details"></div>
                        <div id="last-update-rujukan-kartu" class="last-update">Last Update: -</div>
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
                        <h6><i class="bi bi-card-text"></i> Get Surat Kontrol <span class="endpoint-badge">VCLAIM</span></h6>
                        <div id="status-surat-kontrol" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-surat-kontrol" class="response-time">Response Time: -</div>
                        <div id="response-details-surat-kontrol" class="response-details"></div>
                        <div id="last-update-surat-kontrol" class="last-update">Last Update: -</div>
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
                
                <!-- ANTROL Services -->
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="bi bi-calendar-check"></i> ANTROL Services</h5>
                    
                    <div class="status-card">
                        <h6><i class="bi bi-person-workspace"></i> Get Dokter <span class="endpoint-badge">ANTROL</span></h6>
                        <div id="status-antrol-dokter" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-antrol-dokter" class="response-time">Response Time: -</div>
                        <div id="response-details-antrol-dokter" class="response-details"></div>
                        <div id="last-update-antrol-dokter" class="last-update">Last Update: -</div>
                    </div>
                    
                    <div class="status-card">
                        <h6><i class="bi bi-building"></i> Get Poli <span class="endpoint-badge">ANTROL</span></h6>
                        <div id="status-antrol-poli" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-antrol-poli" class="response-time">Response Time: -</div>
                        <div id="response-details-antrol-poli" class="response-details"></div>
                        <div id="last-update-antrol-poli" class="last-update">Last Update: -</div>
                    </div>
                    
                    <div class="status-card">
                        <h6><i class="bi bi-calendar2-week"></i> Get Jadwal Dokter <span class="endpoint-badge">ANTROL</span></h6>
                        <div id="status-antrol-jadwal" class="status-indicator status-loading">
                            <i class="bi bi-hourglass-split"></i> Loading...
                        </div>
                        <div id="response-time-antrol-jadwal" class="response-time">Response Time: -</div>
                        <div id="response-details-antrol-jadwal" class="response-details"></div>
                        <div id="last-update-antrol-jadwal" class="last-update">Last Update: -</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Global variables for metrics
        let autoRefreshInterval;
        let isAutoRefresh = false;
        let totalRequests = 0;
        let successCount = 0;
        let responseSum = 0;
        let onlineCount = 0;
        
        // API endpoints
        const endpoints = {
            production: {
                peserta: "monitoring_controller.php?param=peserta",
                rujukan: "monitoring_controller.php?param=rujukan", 
                sep: "monitoring_controller.php?param=sep",
                rujukan_kartu: "monitoring_controller.php?param=rujukan_kartu",
                rujukan_multi: "monitoring_controller.php?param=rujukan_multi",
                diagnosa: "monitoring_controller.php?param=diagnosa",
                antrol_dokter: "monitoring_controller.php?param=antrol",
                antrol_poli: "monitoring_controller.php?param=antrol_poli",
                antrol_jadwal: "monitoring_controller.php?param=antrol_jadwal",
                surat_kontrol: "monitoring_controller.php?param=surat_kontrol"
            },
            cdn: {
                peserta: "monitoring_controller.php?param=peserta&cdn=1",
                rujukan: "monitoring_controller.php?param=rujukan&cdn=1",
                sep: "monitoring_controller.php?param=sep&cdn=1",
                rujukan_kartu: "monitoring_controller.php?param=rujukan_kartu&cdn=1",
                rujukan_multi: "monitoring_controller.php?param=rujukan_multi&cdn=1", 
                diagnosa: "monitoring_controller.php?param=diagnosa&cdn=1",
                antrol_dokter: "monitoring_controller.php?param=antrol&cdn=1",
                antrol_poli: "monitoring_controller.php?param=antrol_poli&cdn=1",
                antrol_jadwal: "monitoring_controller.php?param=antrol_jadwal&cdn=1",
                surat_kontrol: "monitoring_controller.php?param=surat_kontrol&cdn=1"
            }
        };
        
        function fetchAndDisplay(id, url) {
            const start = performance.now();
            
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                timeout: 10000,
                success: function(data, textStatus, xhr) {
                    const end = performance.now();
                    const time = Math.round(end - start);
                    
                    let status = 'ERROR';
                    let statusClass = 'status-error';
                    let icon = '<i class="bi bi-x-circle-fill"></i>';
                    let httpStatusClass = 'http-other';
                    let responseDetails = '';
                    
                    // Determine HTTP status badge class
                    if (xhr.status >= 200 && xhr.status < 300) {
                        httpStatusClass = 'http-200';
                    } else if (xhr.status >= 400 && xhr.status < 500) {
                        httpStatusClass = 'http-400';
                    } else if (xhr.status >= 500) {
                        httpStatusClass = 'http-500';
                    }
                    
                    // Use BPJS validation if available
                    if (data && data.validation) {
                        // Consider codes 200, 201, and 1 as valid for BPJS
                        if (data.validation.is_valid || ['201', '1', 201, 1].includes(data.validation.error_code)) {
                            status = 'CONNECTED';
                            statusClass = 'status-success';
                            icon = '<i class="bi bi-check-circle-fill"></i>';
                            successCount++;
                            responseDetails = `BPJS Status: ${data.validation.message}`;
                            
                            if (data.validation.error_code) {
                                responseDetails += ` | Code: ${data.validation.error_code}`;
                            }
                        } else {
                            status = 'API ERROR';
                            statusClass = 'status-error';
                            responseDetails = `BPJS Status: ${data.validation.message}`;
                            
                            if (data.validation.error_code && !['200', '201', '1'].includes(String(data.validation.error_code))) {
                                responseDetails += ` | Code: ${data.validation.error_code}`;
                            }
                        }
                    } else {
                        // Fallback to old validation method
                        if (xhr.status === 200 && data.status === 'success') {
                            status = 'CONNECTED';
                            statusClass = 'status-success';
                            icon = '<i class="bi bi-check-circle-fill"></i>';
                            successCount++;
                            
                            // Add API response details
                            if (data.data && data.data.metaData) {
                                responseDetails = `API Code: ${data.data.metaData.code || 'N/A'} | Message: ${data.data.metaData.message || 'Success'}`;
                            } else if (data.message) {
                                responseDetails = `Message: ${data.message}`;
                            }
                        } else if (data.meta || data.metaData) {
                            const meta = data.meta || data.metaData;
                            const code = meta.code || 'Unknown';
                            if (["200", "201", "1"].includes(String(code))) {
                                status = 'CONNECTED';
                                statusClass = 'status-success';
                                icon = '<i class="bi bi-check-circle-fill"></i>';
                                successCount++;
                                responseDetails = `API Code: ${code} | Message: ${meta.message || 'Success'}`;
                            } else {
                                status = 'API ERROR';
                                statusClass = 'status-error';
                                responseDetails = `API Code: ${code} | Message: ${meta.message || 'Error'}`;
                            }
                        } else if (xhr.status === 200) {
                            // HTTP 200 but unexpected response structure
                            status = 'PARTIAL';
                            statusClass = 'status-loading';
                            icon = '<i class="bi bi-exclamation-triangle-fill"></i>';
                            responseDetails = 'Unexpected response structure';
                        }
                    }
                    
                    // Update metrics
                    totalRequests++;
                    responseSum += time;
                    
                    $(`#status-${id}`).removeClass('status-loading status-success status-error').addClass(statusClass);
                    $(`#status-${id}`).html(`${icon} ${status} <span class="status-code">${xhr.status}</span>`);
                    $(`#response-time-${id}`).text(`Response Time: ${time} ms`);
                    
                    // Update response details
                    const httpBadge = `<span class="http-status-badge ${httpStatusClass}">HTTP ${xhr.status}</span>`;
                    $(`#response-details-${id}`).html(`${httpBadge}${responseDetails}`);
                    
                    const now = new Date();
                    $(`#last-update-${id}`).text("Last Update: " + now.toLocaleTimeString('id-ID'));
                    
                    updateMetrics();
                },
                error: function(xhr, status, error) {
                    const end = performance.now();
                    const time = Math.round(end - start);
                    
                    totalRequests++;
                    responseSum += time;
                    
                    let httpStatusClass = 'http-other';
                    if (xhr.status >= 400 && xhr.status < 500) {
                        httpStatusClass = 'http-400';
                    } else if (xhr.status >= 500) {
                        httpStatusClass = 'http-500';
                    }
                    
                    $(`#status-${id}`).removeClass('status-loading status-success').addClass('status-error');
                    $(`#status-${id}`).html(`<i class="bi bi-x-circle-fill"></i> ERROR <span class="status-code">${xhr.status || 'NET'}</span>`);
                    $(`#response-time-${id}`).text(`Response Time: ${time} ms (Error)`);
                    
                    // Update error details
                    const httpBadge = `<span class="http-status-badge ${httpStatusClass}">HTTP ${xhr.status || 'Network Error'}</span>`;
                    const errorDetails = `Error: ${error || status || 'Network/Timeout Error'}`;
                    $(`#response-details-${id}`).html(`${httpBadge}${errorDetails}`);
                    
                    $(`#last-update-${id}`).text(`Last Update: ${new Date().toLocaleTimeString('id-ID')}`);
                    
                    updateMetrics();
                }
            });
        }
        
        function updateMetrics() {
            const avgResponseTime = totalRequests > 0 ? Math.round(responseSum / totalRequests) : 0;
            const successRate = totalRequests > 0 ? Math.round((successCount / totalRequests) * 100) : 0;
            
            // Count online services
            onlineCount = document.querySelectorAll('.status-success').length;
            
            document.getElementById('avgResponseTime').textContent = avgResponseTime + ' ms';
            document.getElementById('successRate').textContent = successRate + '%';
            document.getElementById('totalRequests').textContent = totalRequests;
            document.getElementById('onlineServices').textContent = `${onlineCount}/9`;
        }
        
        function refreshAll() {
            const endpointType = $('#endpointSelect').val();
            const currentEndpoints = endpoints[endpointType];
            
            // Reset all to loading state
            $('.status-indicator').removeClass('status-success status-error').addClass('status-loading');
            $('.status-indicator').html('<i class="bi bi-hourglass-split"></i> Loading...');
            
            // Reset success count for this refresh cycle
            successCount = 0;
            
            // Fetch all endpoints
            Object.keys(currentEndpoints).forEach(key => {
                const mappedKey = key.replace('_', '-'); // antrol_dokter -> antrol-dokter
                fetchAndDisplay(mappedKey, currentEndpoints[key]);
            });
        }
        
        function startAutoRefresh() {
            if (autoRefreshInterval) clearInterval(autoRefreshInterval);
            
            isAutoRefresh = true;
            $('#autoRefreshBtn').prop('disabled', true);
            $('#stopRefreshBtn').prop('disabled', false);
            
            // Refresh immediately
            refreshAll();
            
            // Set interval for 10 seconds
            autoRefreshInterval = setInterval(refreshAll, 10000);
        }
        
        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
            
            isAutoRefresh = false;
            $('#autoRefreshBtn').prop('disabled', false);
            $('#stopRefreshBtn').prop('disabled', true);
        }
        
        // Event listeners
        $(document).ready(function() {
            // Initial refresh
            refreshAll();
            
            $('#refreshBtn').click(refreshAll);
            $('#autoRefreshBtn').click(startAutoRefresh);
            $('#stopRefreshBtn').click(stopAutoRefresh);
            
            $('#endpointSelect').change(function() {
                if (isAutoRefresh) {
                    stopAutoRefresh();
                    setTimeout(startAutoRefresh, 100);
                } else {
                    refreshAll();
                }
            });
            
            // Auto-start monitoring after 2 seconds
            setTimeout(startAutoRefresh, 2000);
        });
    </script>
</body>
</html>