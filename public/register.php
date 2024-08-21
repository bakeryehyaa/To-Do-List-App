<?php
require '../config/config.php';  

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmation = $_POST["confirm-password"];

    try {

        $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Username or Email has already been taken');</script>";
        } else {
            $trimmedPassword = trim($password);
            $trimmedConfirmation = trim($confirmation);

            if (hash_equals($trimmedPassword, $trimmedConfirmation)) {
       
                $hashedPassword = password_hash($trimmedPassword, PASSWORD_DEFAULT);

                
                $query = "INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);

                if ($stmt->execute()) {
                    echo "<script>alert('Registration Success');</script>";
                } else {
                    echo "<script>alert('Registration Failed');</script>";
                }
            } else {
                echo "<script>alert('Passwords do not match');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../register.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Register</h2>
            <form action="" method="post" autocomplete="off">
                <div class="input-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" name="confirm-password" id="confirm-password" required>
                </div>
                <button type="submit" name="submit" id="submit">Register</button>
                <p class="link-text">Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>
