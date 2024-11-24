<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="css/user_login.css">
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

    <form action="login.php" method="post" class="login">
        <label>Email</label>
        <input type="email" name="email" placeholder="Example@example.com" required>
        
        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>
        
        <p>No account? <a href="signup.php">Create One</a></p>
        <p id="invalid_pw" style="color:red"></p>
        
        <button type="submit" name="submit">Login</button>
    </form>

</body>
</html>



<?php
//back end coding *************

require_once "PHP/db_connection.php";
session_start();
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $query = "SELECT `UniId`,`Password`,`Approval` FROM `user` WHERE `email` = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $email); 
        $stmt->execute(); 
        $stmt->store_result(); 

        if ($stmt->num_rows > 0) { 
            $stmt->bind_result($uniid,$password_con,$approval); 
            $stmt->fetch();

           
            if ( ($password_con==$password)  && $approval==1 ) {

                $_SESSION['email'] = $email;
                setcookie("email",$email,time()+3600,"/");
                setcookie("uniid",$uniid,time()+3600,"/");
                header("Location: jobs.php");
                
            }
            
             else {
                echo "<script>
                        const r = document.getElementById('invalid_pw');
                        r.innerHTML = 'Invalid password. or It not confromd by Administrtor';
                    </script>" ;
            }
        } else {
            echo "<script>alert('No user found with this email.');</script>"; 
        }

        $stmt->close(); 
    } else {
        echo "Error preparing query: " . $conn->error; 
    }

    $conn->close(); 
} 
?>
