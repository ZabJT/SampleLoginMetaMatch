<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include your database connection file
include 'connect.php'; // Ensure this file contains the correct database connection

// The plain text password that you want to hash (e.g., 'email123')
$plainPassword = 'email123';

// Hash the password using password_hash()
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// SQL query to update the password for the admin account
$sql = "UPDATE users SET password = '$hashedPassword' WHERE email = 'admin@email.com'";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Admin password updated successfully.";
} else {
    echo "Error updating password: " . $conn->error;
}

// Close the connection
$conn->close();
?>
