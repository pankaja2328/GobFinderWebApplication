<?php
require_once "PHP/db_connection.php";
session_start();

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

// Fetch user data
$uniid = $_COOKIE['uniid'];
$query = "
    SELECT 
        user.FirstName, user.LastName, user.Email, user.PhoneNumber, user.Rating, jobapplier.JobsCanDo, jobapplier.AppliedJobs 
    FROM 
        `user`
    LEFT JOIN 
        `jobapplier` ON user.UniID = jobapplier.UniID 
    WHERE 
        user.UniID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $uniid);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    echo "User not found.";
    exit;
}

// Fetch applied jobs
$appliedJobs = [];
if (!empty($userData['AppliedJobs'])) {
    $appliedJobsIds = explode(",", $userData['AppliedJobs']);
    $placeholders = implode(',', array_fill(0, count($appliedJobsIds), '?'));
    $jobQuery = "SELECT JobID, JobTitle, Description, PostedBy,Status FROM job WHERE JobID IN ($placeholders)";
    
    $jobStmt = $conn->prepare($jobQuery);
    $jobStmt->bind_param(str_repeat('i', count($appliedJobsIds)), ...$appliedJobsIds);
    $jobStmt->execute();
    $jobResult = $jobStmt->get_result();

    while ($row = $jobResult->fetch_assoc()) {
        $appliedJobs[] = $row;
    }

    $jobStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="css/myprofile.css">
    <script src="js/dashboard.js"></script>
</head>
<body>

    <header>
        <div class="container">
            <h1>My Profile</h1>
            <nav>
                <ul>
                    <li><a href="jobs.php">Home</a></li>
                    <li><a href="jobs.php">Jobs</a></li>
                    <li><a href="createjob.php">Create Job</a></li>
                    <li><a href="myprofile.php" class="active">My Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="profile">
        <div class="container">
            <h2>Profile Information</h2>

            <form action="update_profile.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userData['FirstName'] . ' ' . $userData['LastName']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['Email']); ?>" required readonly>

                <label for="PhoneNumber">Phone Number:</label>
                <input type="text" id="PhoneNumber" name="PhoneNumber" value="<?php echo htmlspecialchars($userData['PhoneNumber']); ?>" required>

                <label for="job">Jobs You Can Do:</label>
                <input type="text" id="job" name="job" value="<?php echo htmlspecialchars($userData['JobsCanDo']); ?>">

                <label for="job">Ratings: <?php echo htmlspecialchars($userData['Rating']); ?></label>

                <button type="submit">Update Profile</button>
            </form>

            <?php if (!empty($appliedJobs)): ?>
                <h2>Jobs You've Applied For</h2>
                <div class="applied-jobs">
                    <?php foreach ($appliedJobs as $job): ?>
                        <div class="job">
                            <h3><?php echo htmlspecialchars($job['JobTitle']); ?></h3>
                            <p><?php echo htmlspecialchars($job['Description']); ?></p>
                            <p><strong>Posted By:</strong> <?php echo htmlspecialchars($job['PostedBy']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($job['Status']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>You have not applied for any jobs yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Job Portal. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
