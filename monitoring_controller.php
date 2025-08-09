<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response header untuk JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$config = include 'config_secure.php';
include 'bpjs_helper.php';

$param = isset($_REQUEST['param']) ? $_REQUEST['param'] : 'default';
$cdn = isset($_REQUEST['cdn']) ? $_REQUEST['cdn'] : false;
$tanggal = date('Y-m-d');

/* 
 * PARAMETER YANG BISA DIGUNAKAN:
 * 
 * Untuk VCLAIM:
 * - nokartu/noka: Nomor kartu BPJS (default: 0002057188781)
 * - tanggal: Tanggal SEP (default: 2023-12-04)
 * - nosep: Nomor SEP untuk endpoint SEP
 * - keyword: Keyword untuk diagnosa (default: A00)
 * 
 * Untuk ANTROL:
 * - kodepoli: Kode poli untuk jadwal dokter (default: ANA)
 * - tanggal: Tanggal untuk jadwal (default: besok)
 * 
 * Contoh penggunaan:
 * monitoring_controller.php?param=rujukan&nokartu=0001234567890&cdn=1
 * monitoring_controller.php?param=peserta&noka=0001234567890&tanggal=2024-01-15
 * monitoring_controller.php?param=antrol_jadwal&kodepoli=INT&tanggal=2024-01-16
 */

// Response structure
$response = [
    'status' => 'error',
    'message' => '',
    'data' => null,
    'response_time' => 0,
    'timestamp' => date('Y-m-d H:i:s'),
    'endpoint_type' => $cdn ? 'cdn' : 'non-cdn',
    'http_code' => 0,
    'validation' => null,
    'bpjs_status' => 'unknown'
];

$start_time = microtime(true);

try {
    $api_response = '';
    $validation = null;
    
    switch ($param) {
        case 'nik':
            $noka = isset($_REQUEST['noka']) ? $_REQUEST['noka'] : '0002057188781';    
            $tanggal = isset($_REQUEST['tanggal']) ? $_REQUEST['tanggal'] : '2023-12-04';
            $endpoint = "Peserta/nik/$noka/tglSEP/$tanggal";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'noka':
        case 'nokartu':
            $noka = isset($_REQUEST['noka']) ? $_REQUEST['noka'] : '0002057188781';    
            $tanggal = isset($_REQUEST['tanggal']) ? $_REQUEST['tanggal'] : '2023-12-04';
            $endpoint = "Peserta/nokartu/$noka/tglSEP/$tanggal"; 
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'peserta':
            // Default ke nokartu untuk monitoring
            $noka = isset($_REQUEST['noka']) ? $_REQUEST['noka'] : '0002057188781';    
            $tanggal = isset($_REQUEST['tanggal']) ? $_REQUEST['tanggal'] : '2023-12-04';
            $endpoint = "Peserta/nokartu/$noka/tglSEP/$tanggal"; 
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'rujukan':
            // Gunakan endpoint rujukan peserta yang lebih reliable
            $nokartu = isset($_REQUEST['nokartu']) ? $_REQUEST['nokartu'] : '0002057188781';
            $endpoint = "Rujukan/Peserta/$nokartu";    
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'rujukan_kartu':
            // Rujukan berdasarkan nomor kartu
            $nokartu = isset($_REQUEST['nokartu']) ? $_REQUEST['nokartu'] : '0002057188781';
            $endpoint = "Rujukan/Peserta/$nokartu";    
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'rujukan_multi':
            // Rujukan Multi - List peserta rujukan
            $nokartu = isset($_REQUEST['nokartu']) ? $_REQUEST['nokartu'] : '0000109784294';
            $endpoint = "Rujukan/List/Peserta/$nokartu";    
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'surat_kontrol':
            // Use referensi propinsi since surat kontrol endpoint doesn't exist
            $endpoint = "referensi/propinsi";    
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'antrol':
        case 'antrol_dokter':
            // Get Dokter ANTROL
            $endpoint = "ref/dokter";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'antrol', $cdn);
            break;

        case 'antrol_poli':
            // Get Poli ANTROL
            $endpoint = "ref/poli";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'antrol', $cdn);
            break;

        case 'antrol_jadwal':
            // Get Jadwal Dokter HFIS ANTROL
            $kodepoli = isset($_REQUEST['kodepoli']) ? $_REQUEST['kodepoli'] : 'ANA';
            $tanggal = isset($_REQUEST['tanggal']) ? $_REQUEST['tanggal'] : date('Y-m-d', strtotime('+1 day'));
            $endpoint = "jadwaldokter/kodepoli/$kodepoli/tanggal/$tanggal/";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'antrol', $cdn);
            break;

        case 'sep':
            // Get SEP by nomor SEP
            $nosep = isset($_REQUEST['nosep']) ? $_REQUEST['nosep'] : '0210R0190625V000359';
            $endpoint = "SEP/$nosep";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'diagnosa':
            // Test endpoint diagnosa
            $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : 'A00';
            $endpoint = "referensi/diagnosa/$keyword";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'poli':
            // Test endpoint poli
            $endpoint = "referensi/poli";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'faskes':
        case 'ppk':
            // Test endpoint faskes/PPK sesuai contoh yang diberikan
            $jenis = isset($_REQUEST['jenis']) ? $_REQUEST['jenis'] : 'daha';
            $kelas = isset($_REQUEST['kelas']) ? $_REQUEST['kelas'] : '2';
            $endpoint = "referensi/faskes/$jenis/$kelas";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        case 'monitoring':
            // Simple ping endpoint untuk monitoring
            $endpoint = "referensi/propinsi";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;

        default:
            // Default test dengan endpoint referensi sederhana
            $endpoint = "referensi/propinsi";
            $api_response = bpjsRequestWithFallback($endpoint, $config, 'GET', null, 'vclaim', $cdn);
            break;
    }

    // Parse response untuk mendapatkan HTTP code
    $decoded_response = json_decode($api_response, true);
    $http_code = 200; // Default jika tidak ada error
    
    if (isset($decoded_response['http_code'])) {
        $http_code = $decoded_response['http_code'];
    }

    // Validate BPJS response dengan consumer ID
    $validation = validateBpjsResponse($api_response, $http_code, $config['cons_id']);
    
    // Map BPJS error codes to appropriate HTTP status codes
    if (!$validation['is_valid'] && isset($validation['error_code'])) {
        switch ($validation['error_code']) {
            case '401':
            case 401:
                $http_code = 401; // Unauthorized
                break;
            case '402':
            case 402:
                $http_code = 400; // Bad Request
                break;
            case '403':
            case 403:
                $http_code = 403; // Forbidden
                break;
            case '404':
            case 404:
                $http_code = 404; // Not Found
                break;
            case '500':
            case 500:
                $http_code = 500; // Internal Server Error
                break;
            // For other error codes, keep HTTP 200 but with error status
        }
    }
    
    // Set response berdasarkan validasi BPJS
    $response['data'] = $decoded_response;
    $response['http_code'] = $http_code;
    $response['validation'] = $validation;
    $response['bpjs_status'] = $validation['status'];
    
    if ($validation['is_valid']) {
        $response['status'] = 'success';
        $response['message'] = $validation['message'];
    } else {
        $response['status'] = 'error';
        $response['message'] = $validation['message'];
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Exception: ' . $e->getMessage();
    $response['data'] = null;
    $response['validation'] = [
        'is_valid' => false,
        'status' => 'offline',
        'message' => 'System error: ' . $e->getMessage(),
        'response_format' => 'exception'
    ];
    $response['bpjs_status'] = 'offline';
}

// Calculate response time
$end_time = microtime(true);
$response['response_time'] = round(($end_time - $start_time) * 1000, 2); // in milliseconds

// Add metadata if available
if (isset($response['data']['metaData'])) {
    $response['meta'] = $response['data']['metaData'];
} elseif (isset($response['data']['metadata'])) {
    $response['meta'] = $response['data']['metadata'];
}

// Output JSON response with proper HTTP status code
http_response_code($response['http_code']);
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
