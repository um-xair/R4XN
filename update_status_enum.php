<?php
// Database connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'r4xn';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the status enum to use 'inactive' instead of 'completed' and 'archived'
$sql = "ALTER TABLE client_projects MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'";

if ($conn->query($sql) === TRUE) {
    echo "Status enum updated successfully\n";
    
    // Update any existing 'completed' or 'archived' status to 'inactive'
    $update_sql = "UPDATE client_projects SET status = 'inactive' WHERE status IN ('completed', 'archived')";
    if ($conn->query($update_sql) === TRUE) {
        echo "Existing status values updated to 'inactive'\n";
    } else {
        echo "Error updating existing status values: " . $conn->error . "\n";
    }
} else {
    echo "Error updating status enum: " . $conn->error . "\n";
}

$conn->close();
echo "Database update completed!\n";
?> 