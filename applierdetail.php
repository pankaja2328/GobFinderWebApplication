<?php
require_once "PHP/db_connection.php";
session_start();

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
    exit;
}

$applierUniID = $_GET['applier'] ?? '';

if (empty($applierUniID)) {
    echo "Invalid applier ID.";
    exit;
}

// Fetch applier details
$query = "SELECT FirstName, LastName, Email, UniID, PhoneNumber, Rating FROM user WHERE UniID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $applierUniID);
$stmt->execute();
$result = $stmt->get_result();
$applier = $result->fetch_assoc();
$stmt->close();

if (!$applier) {
    echo "Applier not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applier Detail</title>
    <link rel="stylesheet" href="css/applierdetail.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Applier Detail</h1>
        <nav>
            <ul>
                <li><a href="jobs.php">Home</a></li>
                <li><a href="jobs.php">Jobs</a></li>
                <li><a href="createjob.php">Create Job</a></li>
                <li><a href="myprofile.php">My Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="applier-detail">
    <div class="container">
        <div class="profile-card">
            <h2><?php echo htmlspecialchars($applier['FirstName']); ?></h2>
            <p><strong>UniID:</strong> <?php echo htmlspecialchars($applier['UniID']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($applier['Email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($applier['PhoneNumber']) ?></p>
            <p><strong>Rating:</strong> <?php echo htmlspecialchars($applier['Rating']); ?></p>
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
