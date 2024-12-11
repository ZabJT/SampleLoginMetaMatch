<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'connect.php';

$error_message = ""; // Initialize error message variable
$success_message = ""; // Initialize success message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signUp'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = md5($password);

        // Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($checkEmail);
        if ($result->num_rows > 0) {
            // Redirect to login-register.php if email exists
            header("Location: login-register.php?error=email_exists");
            exit();
        } else {
            // Insert new user
            $insertQuery = "INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')";
            if ($conn->query($insertQuery) === true) {
                // Set success message
                $_SESSION['success'] = "Your account has been successfully registered!";
                header("location: login-register.php");
                exit();
            } else {
                // Handle insertion error
                $_SESSION['error'] = "Error: " . $conn->error;
            }
        }
    }

    if (isset($_POST['signIn'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = md5($password);

        // Check user credentials
        $sql = "SELECT * FROM users WHERE email = '$email' AND password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $row['email'];
            header("Location: homepage.php");
            exit();
        } else {
            // Handle incorrect login
            $_SESSION['error'] = "Not found, incorrect email or password";
        }
    }
}
?>