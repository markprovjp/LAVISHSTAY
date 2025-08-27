<?php

require_once 'vendor/autoload.php';

// Load environment
if (file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Test connection to database
try {
    $pdo = new PDO(
        'mysql:host=' . ($_ENV['DB_HOST'] ?? 'localhost') . 
        ';dbname=' . ($_ENV['DB_DATABASE'] ?? 'lavishstay'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    
    echo "✅ Database connection successful\n";
    
    // Test basic room query
    $stmt = $pdo->query("SELECT COUNT(*) as total_rooms FROM room");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Total rooms: " . $result['total_rooms'] . "\n";
    
    // Test room by status
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM room GROUP BY status");
    $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "📈 Room statuses:\n";
    foreach ($statuses as $status) {
        echo "   - {$status['status']}: {$status['count']} rooms\n";
    }
    
    // Test available rooms (no conflicting bookings)
    $checkIn = '2025-08-25';
    $checkOut = '2025-08-26';
    
    $stmt = $pdo->prepare("
        SELECT r.room_id, r.name, r.status 
        FROM room r
        WHERE r.room_id NOT IN (
            SELECT DISTINCT br.room_id 
            FROM booking_rooms br
            JOIN booking b ON br.booking_id = b.booking_id
            WHERE b.check_in_date < ? 
            AND b.check_out_date > ?
            AND b.status IN ('confirmed', 'checked_in', 'pending')
        )
        LIMIT 5
    ");
    $stmt->execute([$checkOut, $checkIn]);
    $availableRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "🏨 Available rooms for {$checkIn} to {$checkOut}:\n";
    foreach ($availableRooms as $room) {
        echo "   - Room {$room['name']} (ID: {$room['room_id']}, Status: {$room['status']})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
