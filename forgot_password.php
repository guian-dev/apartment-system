<?php
include 'auth.php';

$error = '';
$success = '';
$showResetForm = false;
$token = $_GET['token'] ?? '';

if ($token) {
    // Show reset form
    $showResetForm = true;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newPassword = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($newPassword) || empty($confirmPassword)) {
            $error = 'Please enter both password fields.';
        } elseif (strlen($newPassword) < 8) {
            $error = 'Password must be at least 8 characters long.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            $result = resetPassword($token, $newPassword);
            
            if ($result['success']) {
                $success = 'Password reset successfully! Redirecting to login...';
                header('refresh:2;url=login.php');
            } else {
                $error = $result['message'];
            }
        }
    }
} else {
    // Show request form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $emailOrPhone = trim($_POST['email_or_phone'] ?? '');
        
        if (empty($emailOrPhone)) {
            $error = 'Please enter your email or phone number.';
        } else {
            $result = generateResetToken($emailOrPhone);
            
            if ($result['success']) {
                $success = 'Password reset link has been sent! Please check your email or phone for instructions.';
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $showResetForm ? 'Reset Password' : 'Forgot Password'; ?> - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
        }
        
        .auth-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            max-width: 450px;
            width: 100%;
            padding: 3rem;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .auth-header p {
            color: var(--text-secondary);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .alert {
            padding: 1rem;
            border-radius: var(--radius-lg);
            margin-bottom: 1.5rem;
        }
        
        .alert-error {
            background: var(--danger-light);
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
        }
        
        .alert-success {
            background: var(--success-light);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
        }
        
        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i data-lucide="<?php echo $showResetForm ? 'key' : 'lock'; ?>" width="48" height="48" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
                <h1><?php echo $showResetForm ? 'Reset Password' : 'Forgot Password'; ?></h1>
                <p><?php echo $showResetForm ? 'Enter your new password' : 'Enter your email or phone to reset password'; ?></p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i data-lucide="alert-circle" width="20" height="20" style="vertical-align: middle;"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i data-lucide="check-circle" width="20" height="20" style="vertical-align: middle;"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($showResetForm): ?>
                <form method="POST" action="">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" required minlength="8">
                        <small style="color: var(--text-muted);">Minimum 8 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i data-lucide="key" width="20" height="20" style="vertical-align: middle;"></i>
                        Reset Password
                    </button>
                </form>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Email or Phone Number</label>
                        <input type="text" name="email_or_phone" class="form-input" required placeholder="email@example.com or +63 912 345 6789" value="<?php echo htmlspecialchars($_POST['email_or_phone'] ?? ''); ?>">
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i data-lucide="send" width="20" height="20" style="vertical-align: middle;"></i>
                        Send Reset Link
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="auth-footer">
                <a href="login.php">Back to Login</a>
            </div>
        </div>
    </div>
    
    <script>
        lucide.createIcons();
    </script>
</body>
</html>

