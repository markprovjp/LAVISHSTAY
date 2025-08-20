<?php

require_once __DIR__ . '/vendor/autoload.php';

echo "Testing Google Client...\n";

try {
    // Test 1: Kiểm tra class tồn tại
    if (class_exists('Google\Client')) {
        echo "✅ Google\Client class exists\n";
    } else {
        echo "❌ Google\Client class not found\n";
    }
    
    // Test 2: Tạo instance
    $client = new Google\Client();
    echo "✅ Google Client instance created successfully\n";
    
    // Test 3: Kiểm tra OAuth2 service
    if (class_exists('Google\Service\Oauth2')) {
        echo "✅ Google\Service\Oauth2 class exists\n";
        $oauth2 = new Google\Service\Oauth2($client);
        echo "✅ OAuth2 service created successfully\n";
    } else {
        echo "❌ Google\Service\Oauth2 class not found\n";
    }
    
    echo "\n🎉 Google API Client is working properly!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
