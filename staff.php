<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Management - Kagay an View</title>
    <link rel="stylesheet" href="staff.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- sidebar code unchanged -->
        <div class="main-content">
            <header class="header">
                <div class="header-left">
                    <h2>Staff Management</h2>
                    <p>Manage your staff members</p>
                </div>
            </header>

            <div class="content-area">
                <div class="card">
                    <div class="card-header">
                        <h3>Staff Members</h3>
                    </div>
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM staff");
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['name']}</td>
                                            <td>{$row['position']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['phone']}</td>
                                            <td>{$row['status']}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No staff found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
