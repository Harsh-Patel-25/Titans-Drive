<?php
require_once 'config.php';

if(!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->connect();

// Get user details
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Handle profile update
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $zip_code = sanitize($_POST['zip_code']);
    $license_number = sanitize($_POST['license_number']);
    
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, address = ?, city = ?, state = ?, zip_code = ?, license_number = ? WHERE user_id = ?");
    
    if($stmt->execute([$full_name, $phone, $address, $city, $state, $zip_code, $license_number, $_SESSION['user_id']])) {
        $_SESSION['user_name'] = $full_name;
        showSuccess('Profile updated successfully!');
        redirect('profile.php');
    } else {
        showError('Profile update failed');
    }
}

// Handle password change
if(isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if(password_verify($current_password, $user['password'])) {
        if($new_password === $confirm_password && strlen($new_password) >= 6) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            
            if($stmt->execute([$hashed_password, $_SESSION['user_id']])) {
                showSuccess('Password changed successfully!');
                redirect('profile.php');
            }
        } else {
            showError('Passwords do not match or too short');
        }
    } else {
        showError('Current password is incorrect');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="profile-section">
        <div class="container">
            <h1 class="section-title">My Profile</h1>
            
            <?php if($error = getError()): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success = getSuccess()): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="profile-grid">
                <!-- Profile Information -->
                <div class="profile-card">
                    <h2>Personal Information</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <small>Email cannot be changed</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo htmlspecialchars($user['city']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="state" name="state" 
                                       value="<?php echo htmlspecialchars($user['state']); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="zip_code">ZIP Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                   value="<?php echo htmlspecialchars($user['zip_code']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="license_number">Driving License Number</label>
                            <input type="text" class="form-control" id="license_number" name="license_number" 
                                   value="<?php echo htmlspecialchars($user['license_number']); ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
                
                <!-- Change Password -->
                <div class="profile-card">
                    <h2>Change Password</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label for="current_password">Current Password *</label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password *</label>
                            <input type="password" class="form-control" id="new_password" 
                                   name="new_password" required>
                            <small>Minimum 6 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password *</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required>
                        </div>
                        
                        <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                    </form>
                    
                    <!-- Account Info -->
                    <div class="account-info mt-3">
                        <h3>Account Information</h3>
                        <p><strong>Member Since:</strong> <?php echo date('d M Y', strtotime($user['created_at'])); ?></p>
                        <p><strong>Account Status:</strong> 
                            <span class="badge badge-<?php echo $user['is_active'] ? 'available' : 'cancelled'; ?>">
                                <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    
    <style>
        .profile-section {
            padding: 3rem 0;
            background: var(--light-color);
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }
        
        .profile-card {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .profile-card h2 {
            margin-bottom: 1.5rem;
            color: var(--dark-color);
        }
        
        .profile-card h3 {
            margin-bottom: 1rem;
            color: var(--dark-color);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .account-info {
            padding-top: 2rem;
            border-top: 1px solid #ddd;
        }
        
        .account-info p {
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>