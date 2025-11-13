<?php
/**
 * Setup Verification Script
 * 
 * Run this script to verify your PHP Todo App setup is correct.
 * Access it via: http://your-domain/setup-check.php
 * 
 * IMPORTANT: Delete this file after setup verification for security!
 */

// Check PHP version
$phpVersion = phpversion();
$phpVersionOk = version_compare($phpVersion, '7.4.0', '>=');

// Check MySQLi extension
$mysqliOk = extension_loaded('mysqli');

// Check file structure
$structureOk = true;
$missingFiles = [];

$requiredFiles = [
    'includes/config.php',
    'includes/db.php',
    'api/add.php',
    'api/get.php',
    'api/update.php',
    'api/delete.php',
    'public/index.php',
    'assets/css/style.css',
    'assets/js/script.js',
    'sql/schema.sql'
];

foreach ($requiredFiles as $file) {
    if (!file_exists(__DIR__ . '/' . $file)) {
        $structureOk = false;
        $missingFiles[] = $file;
    }
}

// Test database connection
$dbOk = false;
$dbError = '';

if (file_exists(__DIR__ . '/includes/config.php')) {
    require_once __DIR__ . '/includes/config.php';
    require_once __DIR__ . '/includes/db.php';
    
    if (isset($conn) && $conn instanceof mysqli) {
        if ($conn->connect_error) {
            $dbError = $conn->connect_error;
        } else {
            // Check if table exists
            $result = $conn->query("SHOW TABLES LIKE 'todos'");
            if ($result && $result->num_rows > 0) {
                $dbOk = true;
            } else {
                $dbError = "Database table 'todos' does not exist. Please run sql/schema.sql";
            }
            $conn->close();
        }
    } else {
        $dbError = "Database connection object not created";
    }
} else {
    $dbError = "Config file not found";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Todo App - Setup Check</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
        }
        h1 {
            color: #2d3748;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .check-item {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            border-left: 4px solid;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .check-item.success {
            background: #f0fff4;
            border-color: #48bb78;
        }
        .check-item.error {
            background: #fff5f5;
            border-color: #f56565;
        }
        .check-item.warning {
            background: #fffaf0;
            border-color: #ed8936;
        }
        .status {
            font-weight: 600;
            font-size: 14px;
        }
        .status.success { color: #2f855a; }
        .status.error { color: #c53030; }
        .status.warning { color: #c05621; }
        .details {
            margin-top: 10px;
            font-size: 13px;
            color: #718096;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 10px;
            text-align: center;
        }
        .summary h2 {
            color: #2d3748;
            margin-bottom: 10px;
        }
        .summary p {
            color: #718096;
            line-height: 1.6;
        }
        ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        li {
            color: #718096;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 PHP Todo App - Setup Verification</h1>
        
        <div class="check-item <?php echo $phpVersionOk ? 'success' : 'error'; ?>">
            <div>
                <div class="status <?php echo $phpVersionOk ? 'success' : 'error'; ?>">
                    <?php echo $phpVersionOk ? '✓' : '✗'; ?> PHP Version
                </div>
                <div class="details">
                    Current: <?php echo $phpVersion; ?> 
                    <?php echo $phpVersionOk ? '(OK)' : '(Requires 7.4+)'; ?>
                </div>
            </div>
        </div>
        
        <div class="check-item <?php echo $mysqliOk ? 'success' : 'error'; ?>">
            <div>
                <div class="status <?php echo $mysqliOk ? 'success' : 'error'; ?>">
                    <?php echo $mysqliOk ? '✓' : '✗'; ?> MySQLi Extension
                </div>
                <div class="details">
                    <?php echo $mysqliOk ? 'MySQLi extension is loaded' : 'MySQLi extension is not loaded'; ?>
                </div>
            </div>
        </div>
        
        <div class="check-item <?php echo $structureOk ? 'success' : 'error'; ?>">
            <div>
                <div class="status <?php echo $structureOk ? 'success' : 'error'; ?>">
                    <?php echo $structureOk ? '✓' : '✗'; ?> File Structure
                </div>
                <div class="details">
                    <?php if ($structureOk): ?>
                        All required files are present
                    <?php else: ?>
                        Missing files:
                        <ul>
                            <?php foreach ($missingFiles as $file): ?>
                                <li><?php echo htmlspecialchars($file); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="check-item <?php echo $dbOk ? 'success' : 'error'; ?>">
            <div>
                <div class="status <?php echo $dbOk ? 'success' : 'error'; ?>">
                    <?php echo $dbOk ? '✓' : '✗'; ?> Database Connection
                </div>
                <div class="details">
                    <?php if ($dbOk): ?>
                        Database connected and table exists
                    <?php else: ?>
                        <?php echo htmlspecialchars($dbError); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="summary">
            <?php if ($phpVersionOk && $mysqliOk && $structureOk && $dbOk): ?>
                <h2>✅ Setup Complete!</h2>
                <p>All checks passed. Your PHP Todo App is ready to use!</p>
                <p style="margin-top: 15px;">
                    <strong>⚠️ Security Note:</strong> Please delete this file (setup-check.php) after verification.
                </p>
            <?php else: ?>
                <h2>⚠️ Setup Issues Found</h2>
                <p>Please fix the issues above before using the application.</p>
                <p style="margin-top: 15px;">
                    Refer to the README.md file for detailed setup instructions.
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

