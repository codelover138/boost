<?php
$conn = new mysqli('localhost', 'boost_api', 'DWe4Kk0gV7&y', 'boost_api');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tables = ['boost_organisations', 'boost_users'];
foreach ($tables as $table) {
    echo "--- Table: $table ---\n";
    $result = $conn->query("DESCRIBE $table");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "{$row['Field']} - {$row['Type']}\n";
        }
    } else {
        echo "Could not describe $table: " . $conn->error . "\n";
    }
    echo "\n";
}
$conn->close();
