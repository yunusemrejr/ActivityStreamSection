<?php
/**
 * Password Hash Generator
 * 
 * This utility helps you generate a secure password hash for the admin panel.
 * 
 * Usage:
 * 1. Run this file in your browser or via CLI
 * 2. Enter your desired password
 * 3. Copy the generated hash to config.php
 * 
 * Security Note: Delete this file after generating your password hash!
 */

$passwordHash = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($password)) {
        $error = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }
}

// CLI mode
if (php_sapi_name() === 'cli') {
    echo "=== Password Hash Generator ===\n\n";
    
    if ($argc > 1) {
        $password = $argv[1];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        echo "Password: {$password}\n";
        echo "Hash: {$hash}\n\n";
        echo "Copy this hash to config.php:\n";
        echo "define('ADMIN_PASSWORD_HASH', '{$hash}');\n";
    } else {
        echo "Usage: php generate_password_hash.php <password>\n";
        echo "Example: php generate_password_hash.php MySecurePassword123\n";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator - Activity Stream</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .container {
            background: white;
            border-radius: 1rem;
            padding: 2.5rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        h1 {
            color: #1f2937;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            color: #6b7280;
            margin-bottom: 2rem;
        }
        
        .warning {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #92400e;
        }
        
        .warning strong {
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.15s;
        }
        
        input:focus {
            outline: none;
            border-color: #6366f1;
        }
        
        .help-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        button {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        .error {
            background: #fef2f2;
            border: 2px solid #ef4444;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #991b1b;
        }
        
        .success {
            background: #f0fdf4;
            border: 2px solid #10b981;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .success h3 {
            color: #166534;
            margin-bottom: 1rem;
        }
        
        .hash-output {
            background: #1f2937;
            color: #10b981;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            word-break: break-all;
            margin-bottom: 1rem;
        }
        
        .instructions {
            color: #166534;
            font-size: 0.9375rem;
            line-height: 1.6;
        }
        
        .instructions ol {
            margin-left: 1.5rem;
            margin-top: 0.5rem;
        }
        
        .instructions li {
            margin-bottom: 0.5rem;
        }
        
        .instructions code {
            background: #e5e7eb;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Password Hash Generator</h1>
        <p class="subtitle">Generate a secure password hash for your Activity Stream admin panel</p>
        
        <div class="warning">
            <strong>⚠️ Security Warning</strong>
            Delete this file (generate_password_hash.php) after generating your password hash to prevent unauthorized access!
        </div>
        
        <?php if ($error): ?>
            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($passwordHash): ?>
            <div class="success">
                <h3>✅ Password Hash Generated Successfully!</h3>
                
                <div class="hash-output"><?php echo htmlspecialchars($passwordHash); ?></div>
                
                <div class="instructions">
                    <strong>Next Steps:</strong>
                    <ol>
                        <li>Copy the hash above</li>
                        <li>Open <code>config.php</code> in a text editor</li>
                        <li>Find the line: <code>define('ADMIN_PASSWORD_HASH', ...);</code></li>
                        <li>Replace the existing hash with your new hash</li>
                        <li><strong>Delete this file</strong> for security</li>
                    </ol>
                </div>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="password">New Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    minlength="8"
                    autocomplete="new-password"
                >
                <p class="help-text">Minimum 8 characters. Use a strong, unique password.</p>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    required 
                    minlength="8"
                    autocomplete="new-password"
                >
            </div>
            
            <button type="submit">Generate Hash</button>
        </form>
    </div>
</body>
</html>
