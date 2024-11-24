<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
    <link rel="stylesheet" href="CSS/user_login.css">
    <script src="JS/confirm_pw.js"></script>

</head>
<body>
<header>
        <div class="container">
            <h1>Job Portal</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="SignUp.php">Sign Up</a></li>
                    <li><a href="index.php?#about">About</a></li>
                    <li><a href="index.php?#advantages">Advantages</a></li>
                    <li><a href="index.php?#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    
    <form action="signup.php" method="post" class="login" onsubmit="return matchPassword()">

        <label >University Id / NIC</label>
        <input type="text" name="UniId" id="UniId" placeholder="PS****" required>

        <label >First Name</label>
        <input type="text" name="FirstName" id="FirstName" placeholder="John" required>

        <label >Last Name</label>
        <input type="text" name="LastName" id="LastName" placeholder="Doe" required>

        <label >Phone Number</label>
        <input type="text" name="PhoneNumber" id="PhoneNumber" placeholder="07* *******" required>

        <label>Prefer Role</label>
        <select id="Role" name="Role" onchange="expan()">
            <option value="Buyer">Buyer</option>
            <option value="Job Applier">Job Applier</option>
        </select>

        <label id = "label-change">Jobs Can Do</label>
        <input id = "input-change" type="text" name="JobsCanDo" placeholder="Painting,Programmming,..." required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Example@email.com" required>
    
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
    
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
    
        <p>Already a Member? <a href="login.php">Login</a></p>

        
        <div id="mismached_pw"></div> 

       
        <button type="submit" name="submit">Register</button>
    </form>
   
</body>
</html>

<?php
require_once "PHP/db_connection.php";

if (isset($_POST['submit'])) {
    $uniID = $_POST['UniId'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $fname = $_POST['FirstName'];
    $lname = $_POST['LastName'];
    $phone = $_POST['PhoneNumber'];
    $approval = "1";
    $rating = 0.0;
    $preferrole = $_POST['Role'];

    // Check if the email already exists
    $checkQuery = "SELECT * FROM `user` WHERE `email` = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>document.getElementById('mismached_pw').innerHTML = \"<p style='color: red;'>An account with this email already exists. Please log in or use a different email.</p>\";</script>";
    } else {
        // Register the user
        $registerQuery = "INSERT INTO `user`(`UniID`, `FirstName`, `LastName`, `Email`, `PhoneNumber`, `Rating`, `Password`, `Approval`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $registerStmt = $conn->prepare($registerQuery);
        $registerStmt->bind_param("sssssdss", $uniID, $fname, $lname, $email, $phone, $rating, $password, $approval);

        if ($registerStmt->execute()) {
            // Insert into specific role table
            if ($preferrole == "Buyer") {
                
                $jobscando = $_POST['JobsCanDo'];
                $query = "INSERT INTO `jobapplier`(`UniID`, `JobsCanDo`) VALUES (?, ?) ";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $uniID, $jobscando);
                $stmt->execute();
                $stmt->close();

            } elseif ($preferrole == "Job Applier") {

                $location = $_POST['Location'];
                $query = "INSERT INTO `buyer`(`UniID`, `Location`) VALUES (?, ?) ";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $uniID, $location);
                $stmt->execute();
                $stmt->close();
            }

            echo "<script>alert('Your request was sent to the Admin successfully. Please wait for their confirmation.');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('There was an error registering your account. Please try again later.');</script>";
        }

        $registerStmt->close();
    }

    $checkStmt->close();
}

$conn->close();
?>
