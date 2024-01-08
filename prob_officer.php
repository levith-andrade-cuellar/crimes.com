
<!-- ----------------------- PHP ----------------------- -->
<?php
        // Start the session to retrieve session data
        session_start();

        // Set database credentials
        $host = "localhost";
        $username = $_SESSION['username'];
        $password = $_SESSION['password']; 
        $database = "crime";

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Initialize variables
        $officerName = '';
        $officerID = '';
        $status = '';
        $address = '';
        $phoneNumber = '';
        $email = '';
        $assignedCases = [];

        // Check if the ID is provided in the URL
        if (isset($_GET["id"])) {
            $probation_officer_id = $_GET["id"];

            // Query to get probation officer profile
            $sql = "CALL probation_officer_info($probation_officer_id)";
            $result = $conn->query($sql);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $officerName = $row["pb_last"] . ', ' . $row["pb_first"];
                    $officerID = $row["prob_ID"];
                    $status = $row["pb_status"];
                    $address = $row["pb_street"] . ', ' . $row["pb_city"] . ', ' . $row["pb_state"] . ', ' . $row["pb_zip"];
                    $phoneNumber = $row["pb_phone"];
                    $email = $row["pb_email"];

                    // Assuming the stored procedure returns sentences information
                    $assignedCases[] = [
                        'sentence_ID' => $row['sentence_ID'], // Sentence ID from Sentences table
                        'criminal_ID' => $row['criminal_ID']  // Criminal ID from Sentences table
                    ];
                }
            } else {
                echo "No probation officer found with ID: $probation_officer_id";
            }
        } else {
            echo "No probation officer ID provided.";
        }
    ?>

<!-- ----------------------- HTML ----------------------- -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Probation Officer Profile</title>
</head>
<body>

    <div class="top">
        <h2 class="profileTitle">Probation Officer Profile</h2>
        <a href="crime_search.php" class = "searchBar">SEARCH</a>
    </div>
    <div class = "topSec">
        <img src="probCar.png" class = "picInfo">
        <div class="personInfo">
            <h1 class="name"><?php echo $officerName; ?></h1>
            <h3 class="infoElem">PROBATION OFFICER ID: <?php echo $officerID; ?></h3>
            <h3 class="infoElem">STATUS: <?php echo $status; ?></h3>
            <h3 class="infoElem">ADDRESS: <?php echo $address; ?></h3>
            <h3 class="infoElem"><?php echo $phoneNumber; ?></h3>
            <h3 class="infoElem"><?php echo $email; ?></h3>
        </div>
    </div>

    <div class="section">
        <h2 class="sectionTitle">Assigned Cases</h2>
        <?php if (!empty($assignedCases)): ?>
            <div class="sectionElemName">
                <h3 class="elemName">Sentence ID</h3>
                <h3 class="elemName">Criminal ID</h3>
            </div>
            <?php foreach ($assignedCases as $case): ?>
                <div class='elemInfo'>
                    <h2 class='info'><?php echo $case['sentence_ID']; ?></h2>
                    <h2 class='info'><?php echo $case['criminal_ID']; ?></h2>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class = "noCrimes" >No cases assigned to this probation officer.</p>
        <?php endif; ?>
    </div>

    <hr>

    <h2 class = "modifyInfo"> Modify Information</h2>

    <div  class = "editLinks">
        <a href="update.php" class="link">✎ Update Officer Info</a>
        <a href="add_criminal.php" class="link">✎ Add Criminal Record</a>
        <a href="delete.php" class="link"> ✎ Delete Appeal</a>
    </div>
            
</body>
</html>


