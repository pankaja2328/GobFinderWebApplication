<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
    <link rel="stylesheet" href="CSS/index_styles.css">
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
                    <li><a href="#about">About</a></li>
                    <li><a href="#advantages">Advantages</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h2>Welcome to Job Portal</h2>
            <p>Your one-stop destination for all job-related needs.</p>
            <a href="#about" class="btn">Learn More</a>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <h2>About Us</h2>
            <p>We are committed to connecting job seekers with employers, making the job search process easy and effective.</p>
        </div>
    </section>

    <section id="advantages" class="advantages">
        <div class="container">
            <h2>Our Advantages</h2>
            <ul>
                <li>Wide range of job opportunities</li>
                <li>Easy application process</li>
                <li>Free resources for job seekers</li>
                <li>Trusted by top companies</li>
            </ul>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <h2>Contact Us</h2>
            <p>Have questions? Get in touch with us.</p>
            <form>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn">Send Message</button>
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
