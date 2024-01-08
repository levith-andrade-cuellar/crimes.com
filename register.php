<!-- ----------------------- PHP ----------------------- -->
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Set database credentials
        $host = '10.18.151.52';
        $username = 'usera';
        $password = 'password';     // Update with your password
        $database = 'crime';        // Update with your database name
        
        // Create database connection
        $conn = new mysqli($host, $username, $password, $database);
        
        // Check connection
        if ($conn->connect_error) {
          die('Connection failed: ' . $conn->connect_error);
    }

    // Obtain user input from the HTML.
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $role = $conn->real_escape_string($_POST['role']);

    // Create a hashed password.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    if ($stmt->execute()) {

        // MySQL User Creation

        $createUserStatement = "";

        // Officers and Probation Officers

        // If the registered user is an officer or probation officer,
        if ($role == "officer" or $role == "probation_officer"){

            // Set privileges.
            $createUserStatement = "CREATE USER '$username'@'localhost' IDENTIFIED BY '$hashed_password'; 

                                    GRANT SELECT, INSERT, UPDATE, DELETE ON crime.* TO '$username'@'localhost';

                                    GRANT EXECUTE ON PROCEDURE crime.criminal_profile TO '$username'@'localhost';
                                    GRANT EXECUTE ON PROCEDURE crime.crime_profile TO '$username'@'localhost';
                                    GRANT EXECUTE ON PROCEDURE crime.probation_officer_info TO '$username'@'localhost';
                                    GRANT EXECUTE ON PROCEDURE crime.officer_profile TO '$username'@'localhost';
                                    GRANT EXECUTE ON PROCEDURE crime.criminal_crimes TO '$username'@'localhost';
                                    FLUSH PRIVILEGES;
                                    ";
        }

        // Criminals

        // If the registered user is a criminal,
        else if ($role == "criminal"){

            // Set privileges.
            $createUserStatement = "CREATE USER '$username'@'localhost' IDENTIFIED BY '$hashed_password'; 

                                    GRANT SELECT ON crime.criminals TO '$username'@'localhost'; FLUSH PRIVILEGES;
                                    GRANT SELECT ON crime.crimes TO '$username'@'localhost'; FLUSH PRIVILEGES;
                                    GRANT SELECT ON crime.sentences TO '$username'@'localhost'; FLUSH PRIVILEGES;
                                    GRANT SELECT ON crime.crime_charges TO '$username'@'localhost'; FLUSH PRIVILEGES;
                                    GRANT SELECT ON crime.appeals TO '$username'@'localhost'; FLUSH PRIVILEGES;

                                    GRANT EXECUTE ON PROCEDURE crime.criminal_profile TO '$username'@'localhost'; FLUSH PRIVILEGES;
                                    GRANT EXECUTE ON PROCEDURE crime.crime_profile TO '$username'@'localhost'; FLUSH PRIVILEGES;
                                    GRANT EXECUTE ON PROCEDURE crime.criminal_crimes TO '$username'@'localhost'; FLUSH PRIVILEGES;
                                    ";
        }

        // Pass the multiple queries of the user creation process into the connection.

        if ($conn->multi_query($createUserStatement)) {

            echo "<p>MySQL user created.</p>";

            do {
            // Loop through the multiple queries.
            
            } while ($conn->more_results() && $conn->next_result());


        } 
        
        else {
            echo "<p>Error creating MySQL user: " . $conn->error . "</p>";
        }

    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();

    $conn->close();
}
?>

<!-- ----------------------- HTML ----------------------- -->


<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&family=DM+Serif+Display&display=swap" rel="stylesheet">
 
</head>
<body>
    <h2 class = "loginTitle">✨User Registration ✨</h2>
    <div class = "loginInfo">
    <form method="post">    
    <h3> Username: <input type="text" name="username"  class = "box" required><br><br></h3>
    <h3>  Password: <input type="password" name="password"  class = "box" required><br><br></h3>
    <h3>  Role: <select name="role"  class = "box" required></h3>
                <option value="officer">Officer</option>
                <option value="probation_officer">Probation Officer</option>
                <option value="criminal">Criminal</option>
              </select><br>
              <br>
        <input class= 'loginButton' type="submit" value="Register" >
    </form>
</div>
<div class="loginBack">
    <a href="index.php"  class ="backButton"> Back to Login </a>
</div>  
</body>
</html>

