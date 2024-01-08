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
            $crime_id = $_GET["id"];

            $sql = "CALL crime_profile($crime_id)";
            $result = $conn->query($sql);

            

            // Initialize variables

            // General Information
            $crimeID = "";
            $crimeCriminalID = "";
            $crimeClassification = "";
            $crimeDateCharged = "";
            $crimeStatus = "";

            // Process
            $crimeHearingDate = "";
            $crimeAppealCutDate = "";

            // Charges
            $charges = [];

            // Appeals
            $appeals = [];

            $crimeCodes= [];

            $crimeOfficer = [];

            $officerInfo = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    // General Information
                    $crimeID = $row["crime_id"];
                    $crimeCriminalID = $row["criminal_id"];
                    $crimeClassification = $row["classification"];
                    $crimeDateCharged = $row["date_charged"];
                    $crimeStatus = $row["status"];

                    // Process
                    $crimeHearingDate = $row["hearing_date"];
                    $crimeAppealCutDate = $row["appeal_cut_date"];

                     // Reassign crime classification
                    switch ($crimeClassification) {
                    case "F":
                        $crimeClassification = "FELONY😬";
                        break;
                    case "M":
                        $crimeClassification = "MISDEMEANOR😳";
                        break;
                    case "O":
                        $crimeClassification = "OTHER🤨";
                        break;
                    }
                    
                    // Charges
                    $charge = [
                        "chargeID" => $row["charge_id"],
                        "chargeStatus" => $row["charge_status"],
                        "crimeCode" => $row["crime_code"],
                        "fine_amount" => $row["fine_amount"],
                        "court_fee" => $row["court_fee"],
                        "amount_paid" => $row["amount_paid"],
                        "pay_due_date" => $row["pay_due_date"]
                    ];
                    array_push($charges, $charge);

                    // Appeals
                    $appeal = [
                        "appealID" => $row["appeal_id"],
                        "filingDate" => $row["filing_date"],
                        "hearingDate" => $row["hearing_date"],
                        "appealStatus" => $row["appeal_status"]
                    ];
                    array_push($appeals, $appeal);

                    $crimeCodes = [
                        "code_description" => $row["code_description"]
                    ];
                    $crimeOfficer = [
                        "officer_ID" => $row["officer_ID"]
                    ];
                    $officerInfo = [
                        "po_last" => $row["po_last"],
                        "po_first" => $row["po_first"]

                    ];
                    $criminalInfo = [
                        "criminal_last" => $row["criminal_last"],
                        "criminal_first" => $row["criminal_first"]
                    ];


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
        <title>CRIME</title>
    </head>
    <body>

    <div class="top">
        <h2 class="profileTitle">Crime Information</h2>
        <a href="crime_search.php" class = "searchBar">SEARCH</a>
    </div>

    <div class = "crimeInfo" >
        <h1 class = "name"><?php echo $crimeClassification?> ON <?php echo $crimeDateCharged ?></h1>
        <h3 class = "infoElem"> COMMITED BY: <?php echo $criminalInfo['criminal_last'] ?>, <?php echo $criminalInfo['criminal_first'] ?> (<?php echo $crimeCriminalID ?>) </h3>
        <h3 class = "infoElem"> ASSIGNED OFFICER: <?php echo $officerInfo['po_last'] ?>, <?php echo $officerInfo['po_first'] ?> (<?php echo $crimeOfficer['officer_ID'] ?>)</h3>
        <h3 class = "infoElem"> CRIME STATUS: <?php echo $crimeStatus ?> </h3>
    </div>

    <br>
    <br>
 

    <div class = "section">
        <h2 class = sectionTitle>💗PROCESS💗</h2>
        <h2 class = "sectionElem1"> HEARING DATE: <?php echo $crimeHearingDate ?></h2>
        <h2 class = "sectionElem2"> APPEAL CUT OFF DATE: <?php echo $crimeAppealCutDate ?></h2>
    </div>

    <br>
    <br>
 

   <div class="section">
        <h2 class="sectionTitle">💞CRIME CHARGES💞</h2>
        <div class="sectionElemName">
            <h3 class="elemName">CHARGE ID</h3>
            <h3 class="elemName">CHARGE STATUS</h3>
            <h3 class="elemName">CRIME CODE</h3>
        </div>
        <?php foreach ($charges as $charge): ?>
        <div class="elemInfo">
            <h2 class="info"><?php echo htmlspecialchars($charge['chargeID']); ?></h2>
            <h2 class="info"><?php echo htmlspecialchars($charge['chargeStatus']); ?></h2>
            <h2 class="info"><?php echo htmlspecialchars($charge['crimeCode']); ?></h2>
        </div>
        <div class = "subInfoDiv">
            <h3 class = "subInfo">- CODE DESCRIPTION: <?php echo htmlspecialchars($crimeCodes['code_description']); ?></h3>
            <h3 class = "subInfo">- FINE AMOUNT: <?php echo htmlspecialchars($charge['fine_amount']); ?></h3>
            <h3 class = "subInfo">- COURT FEE: <?php echo htmlspecialchars($charge['court_fee']); ?></h3>
            <h3 class = "subInfo">- AMOUNT PAID: <?php echo htmlspecialchars($charge['amount_paid']); ?></h3>
            <h3 class = "subInfo">- PAYMENT DUE DATE: <?php echo htmlspecialchars($charge['pay_due_date']); ?></h3>
        </div>
        <?php endforeach; ?>
    </div>

    <br>
    <br>

    <div class="section">
        <h2 class="sectionTitle">💖APPEALS💖</h2>
        <div class="sectionElemName">
            <h3 class="elemName">APPEAL ID</h3>
            <h3 class="elemName">FILING DATE</h3>
            <h3 class="elemName">HEARING DATE</h3>
            <h3 class="elemName">STATUS</h3>
        </div>
        <?php if (!empty($appeals)): ?>
        <?php foreach ($appeals as $appeal): ?>
        <div class="elemInfo">
            <h2 class="info"><?php echo htmlspecialchars($appeal['appealID']); ?></h2>
            <h2 class="info"><?php echo htmlspecialchars($appeal['filingDate']); ?></h2>
            <h2 class="info"><?php echo htmlspecialchars($appeal['hearingDate']); ?></h2>
            <h2 class="info"><?php echo htmlspecialchars($appeal['appealStatus']); ?></h2>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
            <p class = "noCrimes" > No appeals for this crime.</p>
        <?php endif; ?>
    </div>
</body>
</html>