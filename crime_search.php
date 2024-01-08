<!-- ----------------------- PHP ----------------------- -->
<?php
    // Start the session to retrieve session data
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

    $errorMessage = '';

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["searchInput"]) && isset($_GET["searchCategory"])) {
        // Retrieve the search input and category from the form
        $searchInput = $_GET["searchInput"];
        $searchCategory = $_GET["searchCategory"];

        // Redirection based on category
        $profileLinks = [
            'Criminal' => "criminal.php?id=$searchInput",
            'Crime' => "crime.php?id=$searchInput",
            'Probation Officer' => "prob_officer.php?id=$searchInput",
            'Officer' => "officer.php?id=$searchInput"
        ];

        if (isset($profileLinks[$searchCategory])) {
            header("Location: " . $profileLinks[$searchCategory]);
            exit;
        } else {
            $errorMessage = "No results found for the selected category.";
        }
    }
?>

<!-- ----------------------- HTML ----------------------- -->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>CRIME SEARCH</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1 class = "crimeSearch">CRIMES.COM</h1>
    <div class="container">
        <div class="buttons">
            <p class = "find">FIND INFORMATION ABOUT...</p>
            <br>
            <div class="navigation">
                <!-- JavaScript for setting the category -->
                <script>
                    let currentCategory = '';

                    function setCategory(category) {
                        currentCategory = category;
                        updatePlaceholder();
                        document.querySelector('input[name="searchCategory"]').value = category;
                    }

                    function updatePlaceholder() {
                        const placeholderText = currentCategory ? `Search ${currentCategory} ID...` : 'Search...';
                        document.querySelector('.search-box').placeholder = placeholderText;
                    }
                </script>
                <!-- Category Buttons -->
                <?php 
                    if ($_SESSION['role'] == "criminal"){
                        echo "<button class='nav-button' onclick=\"setCategory('Criminal')\">Criminal</button>";
                        echo "<button class='nav-button' onclick=\"setCategory('Crime')\">Crime</button>";
                    }
                    else {
                        echo "<button class='nav-button' onclick=\"setCategory('Criminal')\">Criminal</button>";
                        echo "<button class='nav-button' onclick=\"setCategory('Crime')\">Crime</button>";
                        echo "<button class='nav-button' onclick=\"setCategory('Probation Officer')\">Probation Officer</button>";
                        echo "<button class='nav-button' onclick=\"setCategory('Officer')\">Officer</button>";
                    }
                ?>
            </div>
        </div>
        <br>
        
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <div class="search-container">
            <form method="GET">
                <input type="text" class="search-box" name="searchInput" placeholder="Search...">
                <input type="hidden" name="searchCategory">
                <button class="search-button" type="submit">Search</button>
            </form>
        </div>
    </div>
</body>
</html>
