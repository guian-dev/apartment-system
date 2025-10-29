<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kagay an View - Access Portal</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        .access-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
        }
        .access-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        .logo-section {
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
        }
        .logo-text h1 {
            font-size: 2.5rem;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .logo-text p {
            color: #666;
            margin: 0.5rem 0 0;
            font-size: 1.1rem;
        }
        .access-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        .access-btn {
            padding: 2rem;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        .access-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        .access-btn.admin:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .access-btn.staff:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .access-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
            color: #4a5568;
        }
        .access-btn.admin:hover .access-icon {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .access-btn.staff:hover .access-icon {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .access-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .access-desc {
            color: #666;
            font-size: 0.9rem;
        }
        .access-btn.admin:hover .access-desc,
        .access-btn.staff:hover .access-desc {
            color: rgba(255,255,255,0.9);
        }
        .customer-link {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }
        .customer-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .customer-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .access-buttons {
                grid-template-columns: 1fr;
            }
            .access-card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="access-container">
        <div class="access-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i data-lucide="building-2" width="40" height="40"></i>
                </div>
                <div class="logo-text">
                    <h1>Kagay an View</h1>
                    <p>Apartment Management System</p>
                </div>
            </div>
            
            <h2>Choose Your Access Level</h2>
            
            <div class="access-buttons">
                <a href="main.php" class="access-btn admin">
                    <div class="access-icon">
                        <i data-lucide="shield-check" width="30" height="30"></i>
                    </div>
                    <div class="access-title">Admin Access</div>
                    <div class="access-desc">Full system management<br>Staff, tenants, units, payments</div>
                </a>
                
                <a href="staff_dashboard.php" class="access-btn staff">
                    <div class="access-icon">
                        <i data-lucide="users" width="30" height="30"></i>
                    </div>
                    <div class="access-title">Staff Access</div>
                    <div class="access-desc">Daily operations<br>Reservations, maintenance, reports</div>
                </a>
            </div>
            
            <div class="customer-link">
                <p>Looking for available units? <a href="customer.php">Browse as Customer</a></p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
