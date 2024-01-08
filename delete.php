<!-- ----------------------- PHP ----------------------- -->
<?php
// Start the session to retrieve session data
session_start();

// Set database credentials
$host = "localhost";
$username = $_SESSION['username'];
$password = $_SESSION['password']; 
$database = "crime";

// creating a connection object
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // if connection fails, print.
}

// php variables
$selectedAppealId = "";
$deleteButtonPressed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_button_pressed"])) {
    // Get values from the form
    $selectedAppealId = $_POST["selectedAppealId"];

    // Start transaction refernce: https://www.php.net/manual/en/pdo.transactions.php
    $conn->begin_transaction();

    try {
        // Delete statement
        $sqlDelete = "DELETE FROM appeals WHERE appeal_ID = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $selectedAppealId);
        $stmtDelete->execute();

        // Check if delete was successful
        if ($stmtDelete->affected_rows > 0) {
            echo "<p>Record deleted successfully.</p>";
            // Commit the transaction
            $conn->commit();
        } else {
            echo "<p>Error deleting record: " . $stmtDelete->error . "</p>";
            // Rollback the transaction in case of error
            $conn->rollback();
        }

        $stmtDelete->close();
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Delete Page</title>
</head>

<body>

    <div class="top">
    <h2 class="profileTitle">Delete Appeal</h2>
    <a href="crime_search.php" class = "searchBar">SEARCH</a>
    </div>



    <?php
    if ($deleteButtonPressed) {
        // use prepared statement to delete the entry
        $sqlDelete = "DELETE FROM appeals WHERE appeal_id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("s", $selectedAppealId);

        if ($stmtDelete->execute()) {
            echo "<p style='text-align: center; color: green;'>Record deleted successfully</p>";
        } else {
            echo "<p style='text-align: center; color: red;'>Error deleting record: " . $stmtDelete->error . "</p>";
        }

        $stmtDelete->close();
    }
    ?>

    <!-- select appeal ID -->
    <div class="request">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label>Select Appeal ID :</label>
            <br>
            <select name="selectedAppealId">
                <option value="">-- Select Appeal ID --</option>
                <?php
                $sqlAppeals = "SELECT appeal_id FROM appeals";
                $resultAppeals = $conn->query($sqlAppeals);

                if ($resultAppeals) {
                    while ($rowAppeals = $resultAppeals->fetch_assoc()) {
                        echo "<option value='" . $rowAppeals["appeal_id"] . "'>" . $rowAppeals["appeal_id"] . "</option>";
                    }
                }
                ?>
            </select>

            <br>
            <br>
            <input type="submit" name="delete_button_pressed" value="Delete">
        </form>
    </div>

    <!-- Display appeals table -->
    <div>
        <?php
        $sql = "SELECT * FROM appeals";
        $result = $conn->query($sql);
        $attributes = [];

        if ($result) {
            echo "<table>";

            // column headers
            echo "<tr>";

            while ($column = $result->fetch_field()) {
                echo "<th>" . $column->name . "</th>";
                array_push($attributes, $column->name);
            }

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
        ?>
    </div>

</body>

</html>




