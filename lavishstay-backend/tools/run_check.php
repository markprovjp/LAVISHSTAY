<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BookingServicePaymentController;

$paymentId = $argv[1] ?? 144;
$bookingCode = $argv[2] ?? 'LVS176174436';
$amount = $argv[3] ?? 1000;

$ctl = new BookingServicePaymentController();
$req = new Request([ 'payment_id' => $paymentId, 'booking_code' => $bookingCode, 'amount' => $amount ]);
$res = $ctl->checkServicePayment($req);

// Print JSON response
echo json_encode($res->getData(), JSON_PRETTY_PRINT), PHP_EOL;
