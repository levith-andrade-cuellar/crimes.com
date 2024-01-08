<!-- ----------------------- PHP ----------------------- -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$host = "localhost";
$username = $_SESSION['username'];
$password = $_SESSION['password']; 
$database = "crime";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedTable = "criminals"; // Update to the specific table you want to modify
$selectedID = "";
$selectedAttribute = "";
$newAttributeValue = "";
$updateButtonPressed = false;

$attributes = ["city", "zip", "state", "street","phone", "v_status", "p_status"]; // List of attributes you want to update

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_button_pressed'])) {
    $selectedID = $_POST["selectedID"];
    $selectedAttribute = $_POST["selectedAttribute"];
    $newAttributeValue = $_POST["attribute_type"];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update statement
        $sqlUpdate = "UPDATE $selectedTable SET $selectedAttribute = ? WHERE criminal_ID = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("si", $newAttributeValue, $selectedID);
        $stmtUpdate->execute();

        // Check if update was successful
        if ($stmtUpdate->affected_rows > 0) {
            echo "<p>Record updated successfully.</p>";
            // Commit the transaction
            $conn->commit();
        } else {
            echo "<p>Error updating record: " . $stmtUpdate->error . "</p>";
            // Rollback the transaction in case of error
            $conn->rollback();
        }

        $stmtUpdate->close();
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}


?>


<!-- ----------------------- HTML ----------------------- -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <!-- include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- styling for the update page -->
    <title>Update Page</title>
</head>

<body>
     <div class="top">
    <h2 class="profileTitle">Update Information</h2>
    <a href="crime_search.php" class = "searchBar">SEARCH</a>
    </div>

    <!-- select table -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="request">
        <label>Criminal Table:</label>


        <?php

        $sql2 = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'crime'";
        $result2 = $conn->query($sql2);

      
        ?>

        <br>
    </form>

    <!-- display Table -->
    <br>

    <?php
    
    if ($selectedTable) {
        $sql = "SELECT * FROM $selectedTable";
        $result = $conn->query($sql);
        if ($result) {
            echo "<table>";

            // column headers
            echo "<tr>";

            while ($column = $result->fetch_field()){
                echo "<th>" . $column->name . "</th>";
            }

            echo "</tr>";

            // all Rows
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";

                foreach ($row as $value) {
                    echo "<td>" . $value . "</td>";
                }

                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No results.";
        }
    }
    ?>

    <br>

    <!-- select By ID and Attribute -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="request">
        <label>Select By Criminal ID :</label>
        <br>
        <select name="selectedID">
            <?php
            if ($selectedTable) {
                $sql3 = "SELECT criminal_ID FROM $selectedTable";
                $result3 = $conn->query($sql3);

                if ($result3) {
                    while ($row3 = $result3->fetch_assoc()){
                        echo "<option value='" . $row3["criminal_ID"] . "'>" . $row3["criminal_ID"] . "</option>";
                    }
                }
            }
            ?>
        </select>

        <br>
        <br>
        <label>Select attribute to update:</label>
        <br>
        <select name="selectedAttribute">
            <?php
            if ($selectedTable) {
                foreach ($attributes as $attribute){
                    echo "<option value='" . $attribute . "'>" . $attribute . "</option>";
                }
            }
            ?>
        </select>

        <br>
        <br>
        <!-- type the new attribute value -->
        <label>Type the new attribute value :</label>
        <br>
        <input type="text" name="attribute_type" placeholder="New Attribute Value" required>
        <br>
        <br>
        <input type="submit" name="update_button_pressed" value="Update">
    </form>
    <br>
    <br>

</body>
</html>