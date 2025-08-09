<?php

/**
 * BPJS Configuration dengan dukungan .env
 * Pastikan file .env sudah dibuat dari .env.example
 */

// Function untuk load .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
    return true;
}

// Load .env file jika ada
$envPath = __DIR__ . '/.env';
$envLoaded = loadEnv($envPath);

// Helper function untuk get environment variable dengan fallback
function env($key, $default = null) {
    return isset($_ENV[$key]) ? $_ENV[$key] : $default;
}

return [
    // Kredensial BPJS - Dari .env atau fallback ke nilai default
    'cons_id' => env('BPJS_CONS_ID', '1234'),
    'secret_key' => env('BPJS_SECRET_KEY', 'secretkey'),
    
    // VCLAIM Configuration
    'user_key' => env('BPJS_VCLAIM_USER_KEY', '{user_key_vclaim}'),
    'base_url' => env('BPJS_VCLAIM_BASE_URL', 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest/'),
    'base_url_vclaim_cdn' => env('BPJS_VCLAIM_CDN_URL', 'https://apijkn-cdn.bpjs-kesehatan.go.id/vclaim-rest/'),
    
    // ANTROL/HFIS Configuration  
    'user_key_antrol' => env('BPJS_ANTROL_USER_KEY', '{user_key_antrol}'),
    'base_url_antrol' => env('BPJS_ANTROL_BASE_URL', 'https://apijkn.bpjs-kesehatan.go.id/antreanrs/'),
    'base_url_antrol_cdn' => env('BPJS_ANTROL_CDN_URL', 'https://apijkn-cdn.bpjs-kesehatan.go.id/antreanrs/'),
    
    // Monitoring Settings
    'monitoring' => [
        'default_interval' => (int)env('MONITORING_DEFAULT_INTERVAL', 5000),
        'max_history_records' => (int)env('MONITORING_MAX_HISTORY', 100),
        'timeout' => (int)env('MONITORING_TIMEOUT', 30),
        'retry_attempts' => (int)env('MONITORING_RETRY_ATTEMPTS', 3)
    ],
    
    // Security Settings
    'security' => [
        'env_loaded' => $envLoaded,
        'config_source' => $envLoaded ? '.env file' : 'default values'
    ]
];
