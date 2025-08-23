<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
$code = $argv[1] ?? 'LVS176174436';
$b = Booking::where('booking_code', $code)->first();
if ($b) {
    echo $b->booking_id . PHP_EOL;
} else {
    echo "NOT_FOUND" . PHP_EOL;
}
