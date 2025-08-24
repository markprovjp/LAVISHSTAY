<?php
require 'vendor/autoload.php';
if (file_exists('.env')) { Dotenv\Dotenv::createImmutable(getcwd())->load(); }
$pdo = new PDO(
	'mysql:host=' . (getenv('DB_HOST') ?: 'localhost') . ';dbname=' . (getenv('DB_DATABASE') ?: 'lavishstay'),
	getenv('DB_USERNAME') ?: 'root',
	getenv('DB_PASSWORD') ?: ''
);
$date='2025-08-24';
$stmt=$pdo->prepare('SELECT DISTINCT br.room_id, b.status FROM booking_rooms br JOIN booking b ON br.booking_id=b.booking_id WHERE b.check_in_date <= ? AND b.check_out_date >= ? AND b.status IN ("confirmed","checked_in","pending")');
$stmt->execute([$date, $date]);
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Bookings overlapping {$date}:\n";
foreach($rows as $r){ echo " - room_id=".$r['room_id']." status=".$r['status']."\n"; }
