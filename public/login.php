<?php
session_start();
require '../config/config.php'; 

if (isset($_POST["submit"])) {
    $usernameEmail = $_POST["username-email"];
    $password = $_POST["password"];

    try {
        $sql = "SELECT * FROM users WHERE username = :usernameEmail OR email = :usernameEmail";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usernameEmail', $usernameEmail);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["login"] = true;
                $_SESSION["id"] = $row["id"];
                header("Location: ../public/index.php");
                exit();
            } else {
                echo "<script>alert('Password doesn\'t match');</script>";
            }
        } else {
            echo "<script>alert('User Not Registered');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../login.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Login</h2>
            <form action="" method="post" autocomplete="off">
                <div class="input-group">
                    <label for="username-email">Username or Email:</label>
                    <input type="text" name="username-email" id="username-email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" name="submit" id="submit">Login</button>
                <p class="link-text">Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</body>
</html>
