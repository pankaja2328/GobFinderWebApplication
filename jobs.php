<?php
require_once "PHP/db_connection.php";
session_start();
if (isset($_COOKIE['email'])) {
    
    // $email = $_COOKIE['email'];
    // $approval = "1";

    // $query = "SELECT * FROM `job` WHERE `Approval` = ? " ;
    // $stmt = $conn->prepare($query);


} else {
    echo "No cookie found for user_name.";
    header("Location: login.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs</title>
    <link rel="stylesheet" href="css/jobs.css">
    <script src="js/dashboard.js"></script>
    
</head>
<body>

    <header>
        <div class="container">
            <h1>Available Jobs</h1>
            <nav>
                <ul>
                    <li><a href="jobs.php">Home</a></li>
                    <li><a href="jobs.php" class="active">Jobs</a></li>
                    <li><a href="createjob.php">Create Job</a></li>
                    <li><a href="index.php?#contact">Contact</a></li>
                    <li><a href="myprofile.php">My Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="job-list">
        <div class="container">
            <h2>Find the Right Job for You</h2>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" placeholder="Search for jobs..." id="search-input">
                <button id="search-btn">Search</button>
            </div>

            <div id="jobs">

            
                
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Job Portal. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
