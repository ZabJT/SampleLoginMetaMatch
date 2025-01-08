<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'connect.php';

$error_message = ""; 
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signUp'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $password_hashed = password_hash($password, PASSWORD_BCRYPT); // Use bcrypt instead of md5

        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            header("Location: login-register.php?error=email_exists");
            exit();
        } else {
            // Insert new user
            $insertQuery = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $insertQuery->bind_param("sss", $name, $email, $password_hashed);
            if ($insertQuery->execute()) {
                $_SESSION['success'] = "Your account has been successfully registered!";
                header("Location: login-register.php?status=registered");
                exit();
            } else {
                $_SESSION['error'] = "Error: " . $conn->error;
            }
        }
    }

    if (isset($_POST['signIn'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
    
        // Retrieve user with the given email
        $sql = $conn->prepare("SELECT name, role, password FROM users WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = $row['role'];
    
                // Redirect based on role
                if ($row['role'] === 'admin') {
                    header("Location: admin-dashboard.php");
                } else if ($row['role'] === 'user') {
                    header("Location: homepage.php");
                }
                exit();
            } else {
                header("Location: login-register.php?error=login_failed");
                exit();
            }
        } else {
            header("Location: login-register.php?error=login_failed");
            exit();
        }
    }
}
?>
