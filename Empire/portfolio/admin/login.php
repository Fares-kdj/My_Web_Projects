<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    if ($password === 'empireP2025*') { // Simple password
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit(); // Ensure the script stops after redirection
    } else {
        $error_message = "Incorrect password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./login.css"> <!-- Link to your CSS file -->
    <!-- Add FontAwesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-toggle">
                    <input type="password" id="password" name="password" required class="form-control">
                    <i class="fas fa-eye" id="togglePassword"></i> <!-- Eye icon -->
                </div>
            </div>
            <button class="smoothScroll btn btn-default btn-lg" type="submit">
                <span>Login</span>
            </button>
        </form>
    </div>

    <!-- JavaScript to toggle password visibility -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>