
<!-- ----------------------- PHP ----------------------- -->
<?php

session_start();

// Set database credentials
$host = "localhost";
$username = $_SESSION['username'];
$password = $_SESSION['password']; 
$database = "crime";

// Creating a connection object
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PHP variables
$selectedAppealId = "";
$deleteButtonPressed = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn->begin_transaction();

    try {
        // Data from form
        $criminal_id = $_POST['criminal_id'];
        $alias_id = $_POST['alias_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $phone = $_POST['phone'];
        $v_status = $_POST['v_status'];
        $p_status = $_POST['p_status'];
        $alias = $_POST['alias'];

        // Check if the criminal ID or alias ID already exists
        $checkCriminal = $conn->prepare("SELECT criminal_ID FROM Criminals WHERE criminal_ID = ?");
        $checkCriminal->bind_param("i", $criminal_id);
        $checkCriminal->execute();
        $resultCriminal = $checkCriminal->get_result();
        $checkCriminal->close();

        if ($resultCriminal->num_rows > 0) {
            throw new Exception("Criminal ID already exists. Please choose a different ID.");
        }

        $checkAlias = $conn->prepare("SELECT alias_ID FROM Alias WHERE alias_ID = ?");
        $checkAlias->bind_param("i", $alias_id);
        $checkAlias->execute();
        $resultAlias = $checkAlias->get_result();
        $checkAlias->close();

        if ($resultAlias->num_rows > 0) {
            throw new Exception("Alias ID already exists. Please choose a different ID.");
        }

        // Prepared statement for inserting into criminals table
        $criminal_stmt = $conn->prepare("INSERT INTO Criminals (criminal_ID, criminal_last, criminal_first, street, city, state, zip, phone, v_status, p_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $criminal_stmt->bind_param("isssssssss", $criminal_id, $last_name, $first_name, $street, $city, $state, $zip, $phone, $v_status, $p_status);

        // Prepared statement for inserting into Alias table
        $alias_stmt = $conn->prepare("INSERT INTO Alias (alias_ID, criminal_ID, alias) VALUES (?, ?, ?)");
        $alias_stmt->bind_param("iis", $alias_id, $criminal_id, $alias);

        // Execute the queries
        if (!$criminal_stmt->execute() || !$alias_stmt->execute()) {
            throw new Exception("Error in executing statements: " . $conn->error);
        }

        // Commit the transaction
        $conn->commit();
        echo "New criminal and alias added successfully.";

        // Close statements
        $criminal_stmt->close();
        $alias_stmt->close();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }

    // Close connection
    $conn->close();

}
?>


<!-- ----------------------- HTML ----------------------- -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Criminal</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="top">
        <h2 class="profileTitle">Add New Criminal</h2>
        <a href="crime_search.php" class = "searchBar">SEARCH</a>
    </div>


    <form method="post" class ="addDiv">
        <!-- Criminal Details -->
        <div class = "subDiv">
            <div class = "addInfoDiv">
                <label class = "crimLabel">Criminal ID: </label>
                <input type="number" name="criminal_id" required>
            </div>
            <div  class = "addInfoDiv">
                <label class = "crimLabel">First Name: </label>
                <input type="text" name="first_name" required>
            </div>
            <div  class = "addInfoDiv">
                <label class = "crimLabel">Last Name: </label>
                <input type="text" name="last_name" required>
            </div>
        </div>
        <div class = "subDiv">
            <div  class = "addInfoDiv">
                <label class = "crimLabel">Street: </label>
                <input type="text" name="street" required>
            </div>
            <div  class = "addInfoDiv">
                <label class = "crimLabel">City: </label>
                <input type="text" name="city" required>
            </div>
            <div  class = "addInfoDiv">
                <label class = "crimLabel">State: </label>
                <input type="text" name="state" required>
            </div>
            <div>
                <label class = "crimLabel">Zip: </label>
                <input type="text" name="zip" required>
            </div>

    
            <div  class = "addInfoDiv">
                <label class = "crimLabel">Phone: </label>
                <input type="text" name="phone" required>
            </div>
        </div>
        <div class = "subDiv">
            <div class = "addInfoDiv">
                <label class = "crimLabel">V Staus(Y/N): </label>
                <input type="text" name="v_status" required>
            </div>

            <div  class = "addInfoDiv">
                <label class = "crimLabel">P Status(Y/N): </label>
                <input type="text" name="p_status" required>
            </div>


            <!-- Alias Details -->
            <div  class = "addInfoDiv">
                <label class = "crimLabel">Alias ID: </label>
                <input type="number" name="alias_id" required>
            </div>
            <div  class = "addInfoDiv">
                <label class = "crimLabel">Alias: </label>
                <input type="text" name="alias" required>
            </div  class = "addInfoDiv">
        </div>

        <button type="submit" name="submit" class = "addButton">Add Criminal</button>
    
    </form>
   

</body>
</html>

