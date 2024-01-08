<!-- ----------------------- PHP ----------------------- -->
<?php

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Set database credentials
        $host = 'localhost';
        $username = 'root';
        $password = 'password';     // Update with your password
        $database = 'crime';        // Update with your database name

        // Create database connection
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Collect the user's information from the HTML.
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        // Prepare information about registered users
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // If the username is correct,
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // If the password is correct,
            if (password_verify($password, $user['password'])) {

                // Store the user's information on the session.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];
                $_SESSION['password'] = $user['password'];

                // Redirect the user to the search landing page.
                header("Location: crime_search.php");
                exit();

            // If the password is incorrect,
            } else {
                // Notify the user.
                echo "<p>Invalid password.</p>";
            }
        
            // If the username is incorrect,
        } else {
            // Notify the user.
            echo "<p>Invalid username.</p>";
        }

        $stmt->close();
        $conn->close();
    }

?>

<!-- ----------------------- HTML ----------------------- -->

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <title>User Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h2 class = "loginTitle">💫User Login💫 </h2>
    <div class = "loginInfo">
    <form method="post">
        <h3>Username: <input type="text" name="username" required class ="box"><br><br></h3>
        <h3>Password: <input type="password" name="password" required class ="box"><br><br></h3>
        <input type="submit" value="Login" class = "loginButton">
    </form>
    </div>
    <br>
    <div class="loginBack">
    <a href="register.php"  class ="backButton"> Register User </a>
    <p> Don't Have an Account Yet?</p>
    </div>  
</body>
</html>
