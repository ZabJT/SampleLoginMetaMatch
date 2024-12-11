<?php
session_start();
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_code = $_POST['code'];

    // Check if the entered code matches the session code
    if ($input_code == $_SESSION['verification_code']) {
        // Code is correct, proceed to the next step (e.g., log the user in or redirect)
        unset($_SESSION['verification_code']); // Clear the code from the session
        header("Location: homepage.php"); // Redirect to homepage or another page
        exit();
    } else {
        $error_message = "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Account</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <div class="form-container">
        <h1>Verify Your Account</h1>
        <p>Please enter the 4-digit verification code sent to your email.</p>

        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="post" action="verify.php">
            <div class="input-group">
                <input type="text" name="code1" maxlength="1" required>
                <input type="text" name="code2" maxlength="1" required>
                <input type="text" name="code3" maxlength="1" required>
                <input type="text" name="code4" maxlength="1" required>
            </div>
            <input type="submit" value="Verify">
        </form>
    </div>
</div>

<script>
    // JavaScript to handle focus on the next input box
    const inputs = document.querySelectorAll('input[type="text"]');
    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
    });
</script>

</body>
</html>