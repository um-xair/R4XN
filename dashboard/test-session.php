<?php
session_start();
echo "<h2>Session Test</h2>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NOT SET') . "</p>";
echo "<p>User Name: " . (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'NOT SET') . "</p>";
echo "<p>All Session Data:</p>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";
?>
