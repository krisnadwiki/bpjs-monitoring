<?php
// Perbaikan path untuk library LZ compression
include_once(__DIR__ . '/lz/LZString.php');
include_once(__DIR__ . '/lz/LZReverseDictionary.php');
include_once(__DIR__ . '/lz/LZData.php');
include_once(__DIR__ . '/lz/LZUtil.php');
include_once(__DIR__ . '/lz/LZUtil16.php');
include_once(__DIR__ . '/lz/LZContext.php');

function getTimestamp()
{
    // Sesuai contoh BPJS: time()-strtotime('1970-01-01 00:00:00')
    date_default_timezone_set('UTC');
    return strval(time()-strtotime('1970-01-01 00:00:00'));
}

function getSignature($cid, $secretKey, $timestamp)
{
    // Sesuai contoh BPJS: hash_hmac dengan data."&".$tStamp
    $signature = hash_hmac('sha256', $cid . "&" . $timestamp, $secretKey, true);
    return base64_encode($signature);
}

function stringDecrypt($key, $string)
{
    $encrypt_method = 'AES-256-CBC';
    $key_hash       = hex2bin(hash('sha256', $key));
    $iv             = substr($key_hash, 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
}

function decompressLZ($string)
{
    // Check if LZString class is available
    if (!class_exists('LZCompressor\LZString')) {
        error_log('LZCompressor\LZString class not found');
        return $string; // Return original string if can't decompress
    }
    
    try {
        return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
    } catch (Exception $e) {
        error_log('LZ decompression error: ' . $e->getMessage());
        return $string; // Return original string if decompression fails
    }
}

/**
 * Validate BPJS API response and determine actual status
 * 
 * @param string $response Raw API response
 * @param int $httpCode HTTP status code
 * @param string $consId Expected consumer ID
 * @return array Status information
 */
function validateBpjsResponse($response, $httpCode, $consId = null) {
    $validation = [
        'is_valid' => false,
        'status' => 'offline',
        'message' => '',
        'error_code' => null,
        'has_data' => false,
        'response_format' => 'unknown'
    ];

    // Check basic HTTP status
    if ($httpCode !== 200) {
        $validation['message'] = "HTTP Error: {$httpCode}";
        return $validation;
    }

    // Try to decode JSON
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $validation['message'] = 'Invalid JSON response';
        return $validation;
    }

    // Check for BPJS error response patterns
    if (isset($data['metaData']) || isset($data['metadata'])) {
        $meta = $data['metaData'] ?? $data['metadata'];
        
        // BPJS uses metaData.code for status
        if (isset($meta['code'])) {
            $code = (string)$meta['code'];
            $validation['error_code'] = $code;
            
            // Success codes: 200, "200", 1, "1", 201, "201"
            // Code 1 = OK with encrypted data, Code 201 = No Content (but connected), Code 200 = Success
            if (in_array($code, ['200', '201', '1', 200, 201, 1])) {
                $validation['is_valid'] = true;
                $validation['status'] = 'online';
                
                // Provide specific messages based on code
                switch ($code) {
                    case '1':
                    case 1:
                        $validation['message'] = 'Berhasil - Data Terenkripsi';
                        break;
                    case '201':
                    case 201:
                        $validation['message'] = 'Berhasil - Tidak Ada Data';
                        break;
                    case '200':
                    case 200:
                        $validation['message'] = 'Berhasil - Data Tersedia';
                        break;
                    default:
                        $validation['message'] = $meta['message'] ?? 'Berhasil';
                }
                
                $validation['has_data'] = isset($data['response']) || isset($data['data']);
                $validation['response_format'] = 'bpjs_standard';
            } else {
                // Error codes from BPJS (401, 402, 403, etc.)
                $validation['status'] = 'offline';
                
                // Provide specific error messages based on common codes
                switch ($code) {
                    case '401':
                    case 401:
                        $validation['message'] = 'Unauthorized - Tidak Terdaftar';
                        break;
                    case '402':
                    case 402:
                        $validation['message'] = 'Request Tidak Valid';
                        break;
                    case '403':
                    case 403:
                        $validation['message'] = 'Akses Ditolak';
                        break;
                    case '404':
                    case 404:
                        $validation['message'] = 'Data Tidak Ditemukan';
                        break;
                    case '500':
                    case 500:
                        $validation['message'] = 'Internal Server Error';
                        break;
                    default:
                        $validation['message'] = $meta['message'] ?? "Error Code: {$code}";
                }
                
                $validation['response_format'] = 'bpjs_error';
            }
        } else {
            $validation['message'] = 'Missing BPJS status code in metaData';
        }
    } 
    // Check for direct status field (some endpoints use this)
    elseif (isset($data['status'])) {
        if ($data['status'] === 'success' || $data['status'] === 200) {
            $validation['is_valid'] = true;
            $validation['status'] = 'online';
            $validation['message'] = $data['message'] ?? 'Success';
            $validation['has_data'] = isset($data['data']) || count($data) > 2;
            $validation['response_format'] = 'simple_status';
        } else {
            $validation['status'] = 'offline';
            $validation['message'] = $data['message'] ?? 'API returned error status';
            $validation['response_format'] = 'simple_error';
        }
    }
    // Check for error field
    elseif (isset($data['error'])) {
        if ($data['error'] === false || $data['error'] === 0) {
            $validation['is_valid'] = true;
            $validation['status'] = 'online';
            $validation['message'] = $data['message'] ?? 'Success';
            $validation['has_data'] = count($data) > 2;
            $validation['response_format'] = 'error_flag';
        } else {
            $validation['status'] = 'offline';
            $validation['message'] = $data['message'] ?? 'API returned error';
            $validation['response_format'] = 'error_response';
        }
    }
    // Check if response has reasonable data structure
    elseif (is_array($data) && count($data) > 0) {
        // If we have substantial data, consider it valid
        $validation['is_valid'] = true;
        $validation['status'] = 'online';
        $validation['message'] = 'Valid data response';
        $validation['has_data'] = true;
        $validation['response_format'] = 'data_array';
    } else {
        $validation['message'] = 'Empty or invalid response structure';
    }

    // Additional validation for consumer ID if provided
    if ($consId && $validation['is_valid']) {
        // Some BPJS responses include consumer ID verification
        $responseText = strtolower($response);
        $consIdLower = strtolower($consId);
        
        // This is optional validation - some endpoints don't return cons_id
        // We don't fail the validation just for this
        if (strpos($responseText, $consIdLower) !== false) {
            $validation['message'] .= ' (ConsID verified)';
        }
    }

    return $validation;
}

/**
 * BPJS Request dengan fallback CDN ke non-CDN
 */
function bpjsRequestWithFallback($endpoint, $config, $method = 'GET', $bodyData = null, $type = "vclaim", $useCdn = false)
{
    // Jika tidak menggunakan CDN, langsung panggil fungsi biasa
    if (!$useCdn) {
        return bpjsRequest($endpoint, $config, $method, $bodyData, $type, false);
    }
    
    // Try CDN first
    $response = bpjsRequest($endpoint, $config, $method, $bodyData, $type, true);
    $decoded = json_decode($response, true);
    
    // Check if CDN request failed due to DNS/connection issues
    if (isset($decoded['error']) && $decoded['error'] === true) {
        $errorMessage = strtolower($decoded['message'] ?? '');
        
        // If it's a DNS or connection error, fallback to non-CDN
        if (strpos($errorMessage, 'could not resolve host') !== false || 
            strpos($errorMessage, 'connection') !== false ||
            strpos($errorMessage, 'timeout') !== false ||
            $decoded['http_code'] === 0) {
            
            // Log the fallback attempt
            error_log("[BPJS Monitor] CDN failed, falling back to non-CDN for endpoint: $endpoint");
            
            // Try with non-CDN
            return bpjsRequest($endpoint, $config, $method, $bodyData, $type, false);
        }
    }
    
    return $response;
}

/**
 * Reusable function for BPJS request
 *
 * @param string $endpoint
 * @param array $config
 * @param string $method [GET|POST|PUT|DELETE]
 * @param array|null $bodyData - for POST, PUT
 * @param string $type [vclaim|antrol]
 * @param bool $useCdn - whether to use CDN endpoint
 * @return string|array
 */
function bpjsRequest($endpoint, $config, $method = 'GET', $bodyData = null, $type = "vclaim", $useCdn = false)
{
    $cid       = $config['cons_id'];
    $secretKey = $config['secret_key'];

    if ($type == 'vclaim') {
        $userKey = $config['user_key'];
        if ($useCdn && isset($config['base_url_vclaim_cdn'])) {
            $baseUrl = $config['base_url_vclaim_cdn'];
        } else {
            $baseUrl = $config['base_url'];
        }
    } else { // antrol
        $userKey = $config['user_key_antrol'];
        if ($useCdn && isset($config['base_url_antrol_cdn'])) {
            $baseUrl = $config['base_url_antrol_cdn'];
        } else {
            $baseUrl = $config['base_url_antrol'];
        }
    }

    $timestamp = getTimestamp();
    $signature = getSignature($cid, $secretKey, $timestamp);

    if ($type == 'antrol') {
        // ANTROL menggunakan header lowercase
        $headers = [
            'Content-Type: application/json',
            'x-cons-id: ' . $cid,
            'x-timestamp: ' . $timestamp,
            'x-signature: ' . $signature,
            'user_key: ' . $userKey,
        ];
    } else {
        // VCLAIM menggunakan header uppercase
        $headers = [
            'Content-Type: application/json',
            'X-cons-id: ' . $cid,
            'X-timestamp: ' . $timestamp,
            'X-signature: ' . $signature,
            'user_key: ' . $userKey,
        ];
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $baseUrl . $endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 30 second timeout
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10); // 10 second connection timeout
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    
    // Add DNS options for better hostname resolution
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 300); // Cache DNS for 5 minutes
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Force IPv4
    
    // Add user agent
    curl_setopt($curl, CURLOPT_USERAGENT, 'BPJS-Monitor/1.0');

    // Jika POST atau PUT, kirimkan body data dalam bentuk JSON
    if (in_array(strtoupper($method), ['POST', 'PUT']) && $bodyData !== null) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodyData);
    }

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    // Handle curl errors
    if ($curlError) {
        return json_encode([
            'error' => true,
            'message' => 'CURL Error: ' . $curlError,
            'http_code' => 0,
            'status' => 'error'
        ]);
    }

    // Include HTTP code in response for validation
    $responseData = [
        'http_code' => $httpCode,
        'raw_response' => $response
    ];

    // Handle HTTP errors but still try to parse response
    if ($httpCode >= 400) {
        $responseData['error'] = true;
        $responseData['message'] = 'HTTP Error: ' . $httpCode;
        $responseData['status'] = 'error';
        
        // Try to parse error response from BPJS
        $json = json_decode($response, true);
        if ($json) {
            $responseData = array_merge($responseData, $json);
        }
        
        return json_encode($responseData);
    }

    $json = json_decode($response, true);

    // If JSON decode fails, return raw response with HTTP info
    if (json_last_error() !== JSON_ERROR_NONE) {
        return json_encode([
            'error' => true,
            'message' => 'Invalid JSON response',
            'http_code' => $httpCode,
            'raw_response' => $response,
            'status' => 'error'
        ]);
    }

    // Check if response is encrypted (has 'response' field)
    if (!isset($json['response'])) {
        // Response tidak terenkripsi atau sudah dalam bentuk plain JSON
        // Add HTTP code to response
        if (is_array($json)) {
            $json['http_code'] = $httpCode;
            return json_encode($json);
        } else {
            return json_encode([
                'data' => $json,
                'http_code' => $httpCode,
                'status' => 'success'
            ]);
        }
    }

    // Check if response is in metaData format (some BPJS endpoints)
    if (isset($json['metaData']) || isset($json['metadata'])) {
        $json['http_code'] = $httpCode;
        return json_encode($json);
    }

    // Decrypt response
    try {
        $kunci = $cid . $secretKey . $timestamp;
        $decrypted = stringDecrypt($kunci, $json['response']);
        
        // Check if decompression is needed and available
        if (class_exists('LZCompressor\LZString')) {
            $decompressed = decompressLZ($decrypted);
            
            // Try to add HTTP code to decompressed data
            $decompressedData = json_decode($decompressed, true);
            if (is_array($decompressedData)) {
                $decompressedData['http_code'] = $httpCode;
                return json_encode($decompressedData);
            } else {
                return json_encode([
                    'data' => $decompressed,
                    'http_code' => $httpCode,
                    'status' => 'success'
                ]);
            }
        } else {
            // If LZ library not available, return decrypted data
            error_log('LZ library not available, returning decrypted data');
            
            // Try to add HTTP code to decrypted data
            $decryptedData = json_decode($decrypted, true);
            if (is_array($decryptedData)) {
                $decryptedData['http_code'] = $httpCode;
                return json_encode($decryptedData);
            } else {
                return json_encode([
                    'data' => $decrypted,
                    'http_code' => $httpCode,
                    'status' => 'success'
                ]);
            }
        }
    } catch (Exception $e) {
        return json_encode([
            'error' => true,
            'message' => 'Decryption Error: ' . $e->getMessage(),
            'http_code' => $httpCode,
            'raw_response' => $response,
            'status' => 'error'
        ]);
    }
}
