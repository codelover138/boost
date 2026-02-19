<?php
define('BASEPATH', 'dummy');
require_once 'application/config/database.php';
$db_config = $db['default'];

$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = 'boost_users';
$result = $conn->query("DESCRIBE $table");
if ($result) {
    echo "Columns in $table:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
$conn->close();
