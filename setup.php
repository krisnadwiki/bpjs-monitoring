<?php
/**
 * Setup script untuk BPJS Monitoring System
 * Menggunakan config_secure.php sebagai main config
 */

echo "=== BPJS Monitoring System Setup ===\n\n";

// Create necessary directories
$directories = ['data', 'logs', 'cache', 'backup'];

foreach ($directories as $dir) {
    if (!is_dir(__DIR__ . '/' . $dir)) {
        mkdir(__DIR__ . '/' . $dir, 0755, true);
        echo "✓ Created directory: {$dir}/\n";
    } else {
        echo "✓ Directory already exists: {$dir}/\n";
    }
}

// Validate config_secure.php
echo "\n=== Validate config_secure.php ===\n";
if (file_exists(__DIR__ . '/config_secure.php')) {
    try {
        $config = include(__DIR__ . '/config_secure.php');
        if (is_array($config)) {
            echo "✓ config_secure.php is valid and loaded\n";
            
            // Check if .env exists
            if (file_exists(__DIR__ . '/.env')) {
                echo "✓ .env file found\n";
                
                // Check security status
                if (isset($config['security']['env_loaded']) && $config['security']['env_loaded']) {
                    echo "✓ Configuration loaded from .env file\n";
                } else {
                    echo "⚠️  Using default values - .env file not loaded properly\n";
                }
                
                // Validate required credentials
                $requiredKeys = ['cons_id', 'secret_key', 'user_key', 'user_key_antrol'];
                $missingKeys = [];
                
                foreach ($requiredKeys as $key) {
                    if (empty($config[$key]) || strpos($config[$key], '{') !== false) {
                        $missingKeys[] = $key;
                    }
                }
                
                if (empty($missingKeys)) {
                    echo "✓ All required credentials are configured\n";
                } else {
                    echo "⚠️  Missing or placeholder credentials: " . implode(', ', $missingKeys) . "\n";
                    echo "⚠️  Update your .env file with real BPJS credentials\n";
                }
            } else {
                echo "❌ .env file not found!\n";
                echo "⚠️  Copy .env.example to .env and fill with your credentials\n";
            }
        } else {
            echo "❌ config_secure.php is invalid\n";
        }
    } catch (Exception $e) {
        echo "❌ Error loading config_secure.php: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ config_secure.php not found!\n";
    echo "⚠️  Main configuration file is missing\n";
}

// Test PHP requirements
echo "\n=== PHP Requirements Check ===\n";

$required_extensions = ['curl', 'json', 'openssl'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✓ {$ext} extension loaded\n";
    } else {
        echo "❌ {$ext} extension NOT loaded\n";
    }
}

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo "✓ PHP version: " . PHP_VERSION . " (compatible)\n";
} else {
    echo "❌ PHP version: " . PHP_VERSION . " (requires 7.4+)\n";
}

// Check permissions
echo "\n=== Permissions Check ===\n";
foreach ($directories as $dir) {
    if (is_writable(__DIR__ . '/' . $dir)) {
        echo "✓ {$dir}/ is writable\n";
    } else {
        echo "❌ {$dir}/ is NOT writable\n";
    }
}

// Create sample data (for testing)
$sampleData = [
    [
        'timestamp' => time(),
        'datetime' => date('Y-m-d H:i:s'),
        'service' => 'peserta',
        'status' => 'online',
        'response_time' => 850,
        'endpoint_type' => 'production',
        'success' => true,
        'http_code' => 200
    ]
];

file_put_contents(__DIR__ . '/data/sample_data.json', json_encode($sampleData, JSON_PRETTY_PRINT));
echo "✓ Created sample data file\n";

echo "\n=== Setup Complete ===\n";
echo "Next steps:\n";
echo "1. Copy .env.example to .env\n";
echo "2. Fill .env with your real BPJS credentials\n";
echo "3. Access monitoring.php through browser\n";
echo "4. Start monitoring and test connections\n\n";

echo "Dashboard URL: http://localhost/bpjs-monitoring/monitoring.php\n";
?>
