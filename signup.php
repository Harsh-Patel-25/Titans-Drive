<?php
require_once 'config.php';

if(isLoggedIn()) {
    redirect('dashboard.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']); 
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    if(empty($full_name) || empty($email) || empty($phone) || empty($password)) {
        $errors[] = 'Please fill in all required fields';
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if(strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if(empty($errors)) {
        $db = new Database();
        $conn = $db->connect();
        
        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if($stmt->fetch()) {
            showError('Email already registered');
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
            
            if($stmt->execute([$full_name, $email, $phone, $hashed_password])) {
                showSuccess('Registration successful! Please login.');
                redirect('login.php');
            } else {
                showError('Registration failed. Please try again.');
            }
        }
    } else {
        showError(implode('<br>', $errors));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h1>TITAN'S DRIVE</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-3">Create Your Account</h2>
            
            <?php if($error = getError()): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" id="signupForm">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small>Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                
                <p class="text-center mt-2">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </form>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>