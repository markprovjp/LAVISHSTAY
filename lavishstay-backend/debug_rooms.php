<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=datn_build_basic_3', 'root', '');
$stmt = $pdo->query('SELECT * FROM room WHERE room_type_id = 1 LIMIT 3');
var_dump($stmt->fetchAll(PDO::FETCH_OBJ));
