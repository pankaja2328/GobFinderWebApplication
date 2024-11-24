<?php
require_once "PHP/db_connection.php";
session_start();

// Check if the user is logged in
if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$uniid = $_COOKIE['uniid'];
$success = '';
$error = '';

// Handle form submission to add a new job
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle = trim($_POST['job_title']);
    $description = trim($_POST['description']);
    $status = 'Open';  // Default job status is open

    if (!empty($jobTitle) && !empty($description)) {
        // Insert the job into the job table
        $insertQuery = "INSERT INTO job (JobTitle, Description, Status, PostedBy, AppliedBy) VALUES (?, ?, ?, ?, '')";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssss", $jobTitle, $description, $status, $uniid);

        if ($stmt->execute()) {
            $success = "Job posted successfully!";
        } else {
            $error = "Error posting job. Please try again.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Job</title>
    <link rel="stylesheet" href="css/addjob.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Add a New Job</h1>
        <nav>
            <ul>
                <li><a href="jobs.php">Home</a></li>
                <li><a href="jobs.php">Jobs</a></li>
                <li><a href="createjob.php">My Jobs</a></li>
                <li><a href="addajob.php" class="active">Add Job</a></li>
                <li><a href="myprofile.php">My Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="add-job-form">
    <div class="container">
        <h2>Post a New Job</h2>

        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="addajob.php">
            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" required>
            
            <label for="description">Job Description:</label>
            <textarea id="description" name="description" required></textarea>

            <button type="submit">Post Job</button>
        </form>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 Job Portal. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
