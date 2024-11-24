<?php
require_once "PHP/db_connection.php";
session_start();

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$uniid = $_COOKIE['uniid'];

// Fetch jobs posted by the user
$query = "SELECT JobID, JobTitle, Description, Status, AppliedBy FROM job WHERE PostedBy = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $uniid);
$stmt->execute();
$result = $stmt->get_result();
$jobs = [];

while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}

$stmt->close();

// Handle confirmation of applicants and job deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        $jobId = $_POST['job_id'];
        $appliedBy = $_POST['applied_by'];

        // Update job status to "Closed"
        $updateQuery = "UPDATE job SET Status = 'Closed' WHERE JobID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $stmt->close();

        // Update the Confirmed column with the JobID in the jobapplier table
        $confirmQuery = "UPDATE jobapplier SET Confirmed = ? WHERE UniID = ?";
        $stmt = $conn->prepare($confirmQuery);
        $stmt->bind_param("is", $jobId, $appliedBy);
        $stmt->execute();
        $stmt->close();

        header("Location: createjob.php");
        exit;
    }

    // Handle job deletion
    if (isset($_POST['delete'])) {
        $jobId = $_POST['job_id'];

        // Delete the job from the job table
        $deleteQuery = "DELETE FROM job WHERE JobID = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $stmt->close();

        header("Location: createjob.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job</title>
    <link rel="stylesheet" href="css/createjob.css">
    <script src="js/dashboard.js"></script>
</head>
<body>

<header>
    <div class="container">
        <h1>Your Posted Jobs</h1>
        <nav>
            <ul>
                <li><a href="jobs.php">Home</a></li>
                <li><a href="jobs.php">Jobs</a></li>
                <li><a href="createjob.php" class="active">Create Job</a></li>
                <li><a href="myprofile.php">My Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="job-list">
    <div class="container">
        <h2>Jobs You Have Posted</h2>
        <?php if (count($jobs) > 0): ?>
            <ul>
                <?php foreach ($jobs as $job): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($job['JobTitle']); ?></h3>
                        <p><?php echo htmlspecialchars($job['Description']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($job['Status']); ?></p>
                        
                        <?php if ($job['AppliedBy']): ?>
                            <p>Applied By:</p>
                            <form method="POST" action="createjob.php">
                                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['JobID']); ?>">

                                <?php 
                                $apply = $job['AppliedBy'];
                                $appliers = explode(',', $apply);
                                foreach ($appliers as $applier): 
                                    // Fetch the rating of the applier
                                    $ratingQuery = "SELECT Rating FROM user WHERE UniID = ?";
                                    $stmt = $conn->prepare($ratingQuery);
                                    $stmt->bind_param("s", $applier);
                                    $stmt->execute();
                                    $ratingResult = $stmt->get_result();
                                    $ratingRow = $ratingResult->fetch_assoc();
                                    $rating = $ratingRow['Rating'] ?? 'N/A';
                                    $stmt->close();
                                ?>
                                    <label>
                                        <input type="radio" name="applied_by" value="<?php echo htmlspecialchars($applier); ?>">
                                        <?php echo htmlspecialchars($applier); ?> - Rating: <?php echo htmlspecialchars($rating); ?>
                                        <a href="applierdetail.php?applier=<?php echo htmlspecialchars($applier); ?>" style="color:white; text-decoration:none;"> - Show Profile</a>
                                    </label><br>
                                <?php endforeach; ?>

                                <?php if ($job['Status'] == 'Open'): ?>
                                    <button type="submit" name="confirm">Confirm Applicant</button>
                                <?php endif; ?>
                            </form>
                        <?php else: ?>
                            <p>No applicants yet.</p>
                        <?php endif; ?>

                        <!-- Add a form to delete the job -->
                        <form method="POST" action="createjob.php" onsubmit="return confirm('Are you sure you want to delete this job?');">
                            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['JobID']); ?>">
                            <button type="submit" name="delete" style="background-color:red;color:white;">Delete Job</button>
                        </form>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You have not posted any jobs yet.</p>
        <?php endif; ?>
    </div>

    <a href="addajob.php" style="text-decoration:none">Add a New Job</a>

</section>



<footer>
    <div class="container">
        <p>&copy; 2024 Job Portal. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
