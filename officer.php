
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

    // Check if the ID is provided in the URL
    if (isset($_GET["id"])) {
        $officer_id = $_GET["id"];

        // Query to get officer profile and assigned crimes
        $sql = "CALL officer_profile($officer_id)";
        $result = $conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();

            // Officer Information
            $officerName = $row["po_last"] . ', ' . $row["po_first"];
            $officerID = $row["officer_id"];
            $status = $row["po_status"];
            $precinct = $row["precinct"];
            $badge = $row["badge"];
            $phoneNumber = $row["po_phone"];

            // Crimes Assigned
            $assignedCrimes = [];
            do {
                $assignedCrimes[] = [
                    'crimeID' => $row['crime_id'],
                    'classification' => $row['classification']
                ];
            } while ($row = $result->fetch_assoc());
        } else {
            echo "No officer found with ID: $officer_id";
        }
    } else {
        echo "No officer ID provided.";
    }
?>

<!-- ----------------------- HTML  ----------------------- -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Officer Profile</title>
</head>
<body >


<div class="top">
        <h2 class="profileTitle">Officer Profile</h2>
        <a href="crime_search.php" class = "searchBar">SEARCH</a>
</div>

<div class = "topSec">
    <img src="policeCar.png" class = "picInfo">
    <div class="personInfo">
        <h1 class="name"><?php echo $officerName; ?></h1>
        <h3 class="infoElem">OFFICER ID: <?php echo $officerID; ?></h3>
        <h3 class="infoElem">STATUS: <?php echo $status; ?></h3>
        <h3 class="infoElem">PRECINCT: <?php echo $precinct; ?></h3>
        <h3 class="infoElem">BADGE: <?php echo $badge; ?></h3>
        <h3 class="infoElem">PHONE: <?php echo $phoneNumber; ?></h3>
    </div>
</div>

<div class="section">
    <h2 class="sectionTitle">🙈Assigned Crimes🙈</h2>
    <?php if (!empty($assignedCrimes)): ?>
        <div class="sectionElemName">
            <h3 class="elemName">Crime ID</h3>
            <h3 class="elemName">Classification</h3>
        </div>
        <?php foreach ($assignedCrimes as $crime): ?>
            <div class='elemInfo'>
                <h2 class='info'><?php echo $crime['crimeID']; ?></h2>
                <h2 class='info'><?php echo $crime['classification']; ?></h2>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class = "noCrimes">No crimes assigned to this officer.</p>
    <?php endif; ?>
</div>

<br>
<br>
<hr>
<h2 class = "modifyInfo">🌟Modify Information🌟</h2>

<div  class = "editLinks">
    <a href="update.php" class="link">✎ Update Criminal Info</a>
    <a href="add_criminal.php" class="link">✎ Add Criminal Record</a>
    <a href="delete.php" class="link">✎ Delete Appeal</a>
</div>

</body>
</html>

