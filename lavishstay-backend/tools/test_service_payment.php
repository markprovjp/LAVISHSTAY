<?php
// Bootstrap Laravel and call BookingServicePaymentController for tests
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\BookingServicePaymentController;
use Illuminate\Http\Request;

try {
    $controller = new BookingServicePaymentController();

    // Test getServicePaymentInfo
    $infoResponse = $controller->getServicePaymentInfo(23);
    $infoData = method_exists($infoResponse, 'getData') ? $infoResponse->getData() : $infoResponse;

    // Test generateServicePaymentQR
    $req = Request::create('/','POST', ['amount' => 100000, 'service_ids' => []]);
    $qrResponse = $controller->generateServicePaymentQR($req, 23);
    $qrData = method_exists($qrResponse, 'getData') ? $qrResponse->getData() : $qrResponse;

    echo "=== SERVICE INFO ===\n";
    echo json_encode($infoData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
    echo "=== GENERATE QR ===\n";
    echo json_encode($qrData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
