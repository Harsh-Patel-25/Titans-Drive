<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <h1>TITAN'S DRIVE</h1>
            <span class="tagline">Premium BMW Experience</span>
        </div>
        <ul class="nav-menu" id="navMenu">
            <li><a href="index.php">Home</a></li>
            <li><a href="cars.php">Our Fleet</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if(isLoggedIn()): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php" class="btn-signup" style="color:white;">Sign Up</a></li>
            <?php endif; ?>
        </ul>
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="navbot">
        <div class="strip1"></div>
        <div class="strip2"></div>
        <div class="strip3"></div>
    </div>
</nav>