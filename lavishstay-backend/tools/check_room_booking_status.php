<?php
// Quick script to print rooms with current booking status for a given date
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// This script assumes you have set up a local environment and can run it via php from project root.
// Usage: php tools/check_room_booking_status.php 2025-08-24

$date = $argv[1] ?? date('Y-m-d');

// Load .env? If not available, ensure DB env variables are set in your shell before running.
$capsule = new DB();
$capsule->addConnection([
    'driver' => getenv('DB_CONNECTION') ?: 'mysql',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'database' => getenv('DB_DATABASE') ?: 'datn_build_basic_2',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    $rooms = DB::table('room as r')
        ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
        ->select(['r.room_id as id', 'r.name as room_name', 'r.status', 'rt.name as room_type_name'])
        ->limit(100) // Increased from 50 to 100
        ->get();

    $roomIds = $rooms->pluck('id')->toArray();

    $bookings = DB::table('booking_rooms as br')
        ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
        ->whereIn('br.room_id', $roomIds)
        ->where('br.check_in_date', '<=', $date)
        ->where('br.check_out_date', '>', $date)
        ->select(['br.room_id', 'b.booking_id', 'b.status as booking_status', 'br.check_in_date', 'br.check_out_date'])
        ->get();

    $bookingMap = [];
    foreach ($bookings as $bk) {
        if (!isset($bookingMap[$bk->room_id])) {
            $bookingMap[$bk->room_id] = $bk;
        }
    }

    foreach ($rooms as $r) {
        $bk = $bookingMap[$r->id] ?? null;
        echo "Room {$r->room_name} ({$r->id}) - status: {$r->status}";
        if ($bk) {
            echo " => booking_id={$bk->booking_id}, booking_status={$bk->booking_status}, check_in={$bk->check_in_date}, check_out={$bk->check_out_date}";
        }
        echo PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
