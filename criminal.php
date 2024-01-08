
    <!-- ----------------------- PHP  ------------------------- -->
    
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
       if (isset($_GET["id"])) {
            $criminal_id = $_GET["id"];

            $sql = "CALL criminal_profile($criminal_id)";
            $result = $conn->query($sql);

            // Initialize variables
            $criminalID = "";
            $criminalFirstName = "";
            $criminalLastName = "";
            $alias = "";
            $criminalStreet = "";
            $criminalCity = "";
            $criminalState = "";
            $criminalPhone = "";
            $criminalV_Status = "";
            $criminalP_Status = "";
            $crimes = [];
            $sentences = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    // General Information
                    $criminalID = $row["criminal_ID"];
                    $criminalFirstName = $row["criminal_first"];
                    $criminalLastName = $row["criminal_last"];
                    $criminalStreet = $row["street"];
                    $criminalCity = $row["city"];
                    $criminalState = $row["zip"];
                    $criminalPhone = $row["phone"];
                    $criminalV_Status = $row["v_status"];
                    $criminalP_Status = $row["p_status"];


                    // Crimes
                    $crimes[] = [
                        "crimeID" => $row["crime_ID"],
                        "crimeClassification" => $row["classification"],
                        "crimeDateCharged" => $row["date_charged"]
                    ];

                    // Sentences
                    $sentences[] = [
                        "sentenceID" => $row["sentence_ID"],
                        "sentenceType" => $row["sentence_type"],
                        "probationID" => $row["prob_ID"],
                        "start_date" => $row["start_date"],
                        "end_date" => $row["end_date"], 
                        "violations" => $row["violations"]
                    ];

                    $alias = $row["alias"]; // Use equal sign to assign the alias



                }
            } else {
                echo "No results.";
            }
        } else {
            echo "No criminal ID provided.";
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
    <title>Criminal Profile</title>
</head>
<body>


    <!-- Display criminal profile -->


    <div class="top">
    <h2 class="profileTitle">Criminal Profile</h2>
    <a href="crime_search.php" class = "searchBar">SEARCH</a>
    </div>

    <div class = "topSec">
        <img src="clown.png">
        <div class="personInfo">
            <h1 class="name"><?php echo ($criminalLastName . ", " . $criminalFirstName); ?></h1>
            <h3 class="infoElem">ALIAS: <?php echo $alias; ?></h3> <!-- Use <?php echo $alias; ?> to display alias -->
            <h3 class="infoElem">CRIMINAL ID: <?php echo ($criminalID); ?></h3>
            <h3 class="infoElem">ADDRESS: <?php echo ($criminalStreet . ", " . $criminalCity . ", " . $criminalState); ?></h3>
            <h3 class="infoElem"><?php echo ($criminalPhone); ?></h3>
        </div>
    </div>

    <!-- (rest of the HTML for displaying crimes and sentences) -->

    <div class="section">
        <h2 class="sectionTitle">STATUS</h2>
        <h2 class="sectionElem1">VIOLATION STATUS: <?php echo ($criminalV_Status); ?></h2>
        <h2 class = "sectionElem2"> PROBATION STATUS: <?php echo ($criminalP_Status); ?> </h2>
    </div>

    <br>
    <br>

    <div class="section">
        <h2 class="sectionTitle">CRIMES</h2>
        <div class="sectionElemName">
            <h3 class="elemName">CRIME ID</h3>
            <h3 class="elemName">CLASSIFICATION</h3>
            <h3 class="elemName">DATE CHARGED</h3>
        </div>

        <?php foreach ($crimes as $crime): ?>
        <div class="elemInfo">
            <h2 class="info"><?php echo ($crime['crimeID']); ?></h2>
            <h2 class="info"><?php echo ($crime['crimeClassification']); ?></h2>
            <h2 class="info"><?php echo ($crime['crimeDateCharged']); ?></h2>
        </div>
        <?php endforeach; ?>
    </div>

    <br>
    <br>

    <div class="section">
        <h2 class="sectionTitle">SENTENCES</h2>
        <div class="sectionElemName">
            <h3 class="elemName">SENTENCE ID</h3>
            <h3 class="elemName">TYPE</h3>
            <h3 class="elemName">PROBATION ID</h3>
        </div>
        <?php foreach ($sentences as $sentence): ?>
        <div class="elemInfo">
            <h2 class="info"><?php echo ($sentence['sentenceID']); ?></h2>
            <h2 class="info"><?php echo ($sentence['sentenceType']); ?></h2>
            <h2 class="info">
                <?php 
                if (is_null($sentence['probationID']) || $sentence['probationID'] == '') {
                    echo "N/A";
                } else {
                    echo htmlspecialchars($sentence['probationID']);
                }
                ?>
            </h2>
        </div>
        <div class = "subInfoDiv">
            <h3 class = "subInfo">- START DATE: <?php echo ($sentence['start_date']); ?></h3>
            <h3 class = "subInfo">- END DATE: <?php echo ($sentence['end_date']); ?></h3>
            <h3 class = "subInfo">- VIOLATIONS: <?php echo ($sentence['violations']); ?></h3>
        </div>           
        <?php endforeach; ?>
    </div>

    <br>
    <br>
    <br>
    <br>

</body>
</html>