<?php
include 'db.php';
include 'auth.php';

$unitId = $_GET['id'] ?? 0;

// Check if unit_amenities table exists, then get unit details
$tableCheck = $conn->query("SHOW TABLES LIKE 'unit_amenities'");

if ($tableCheck && $tableCheck->num_rows > 0) {
    $stmt = $conn->prepare("
        SELECT u.*, 
               GROUP_CONCAT(DISTINCT ua.amenity) as amenities
        FROM units u
        LEFT JOIN unit_amenities ua ON u.id = ua.unit_id
        WHERE u.id = ? AND u.status = 'available'
        GROUP BY u.id
    ");
} else {
    $stmt = $conn->prepare("
        SELECT u.*,
               NULL as amenities
        FROM units u
        WHERE u.id = ? AND u.status = 'available'
    ");
}

$stmt->bind_param("i", $unitId);
$stmt->execute();
$result = $stmt->get_result();
$unit = $result->fetch_assoc();

if (!$unit) {
    header('Location: customer.php');
    exit();
}

// Get reviews and ratings (check if tables exist)
$reviewsCheck = $conn->query("SHOW TABLES LIKE 'reviews'");
$customersCheck = $conn->query("SHOW TABLES LIKE 'customers'");
$reviewsResult = null;
$avgRating = 0;
$totalReviews = 0;

if ($reviewsCheck && $reviewsCheck->num_rows > 0 && $customersCheck && $customersCheck->num_rows > 0) {
    try {
        $reviews = $conn->prepare("
            SELECT r.*, c.name as customer_name, c.email as customer_email
            FROM reviews r
            JOIN customers c ON r.customer_id = c.id
            WHERE r.unit_id = ? AND r.status = 'approved'
            ORDER BY r.created_at DESC
            LIMIT 10
        ");
        $reviews->bind_param("i", $unitId);
        $reviews->execute();
        $reviewsResult = $reviews->get_result();

        // Calculate average rating
        $avgRatingStmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE unit_id = ? AND status = 'approved'");
        $avgRatingStmt->bind_param("i", $unitId);
        $avgRatingStmt->execute();
        $avgRatingResult = $avgRatingStmt->get_result();
        $ratingData = $avgRatingResult->fetch_assoc();
        $avgRating = round($ratingData['avg_rating'] ?? 0, 1);
        $totalReviews = $ratingData['total_reviews'] ?? 0;
    } catch (Exception $e) {
        // Tables might not exist yet, continue without reviews
        $reviewsResult = null;
    }
}

$amenities = $unit['amenities'] ? explode(',', $unit['amenities']) : [];
$isLoggedIn = isLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit <?php echo htmlspecialchars($unit['unit_number']); ?> - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        .unit-details-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .unit-header-section {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .unit-title-section {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1.5rem;
        }
        
        .unit-title h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .unit-price-large {
            font-size: 2rem;
            font-weight: 700;
            color: var(--success-color);
        }
        
        .unit-specs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .spec-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: var(--light-color);
            border-radius: var(--radius-lg);
        }
        
        .unit-description-section {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .amenities-section {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: var(--light-color);
            border-radius: var(--radius);
        }
        
        .booking-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: var(--radius-xl);
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
        }
        
        .booking-section h3 {
            margin-bottom: 1rem;
        }
        
        .btn-booking {
            padding: 1rem 2rem;
            background: white;
            color: var(--primary-color);
            border: none;
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-booking:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .reviews-section {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
        }
        
        .review-item {
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem 0;
        }
        
        .review-item:last-child {
            border-bottom: none;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .review-rating {
            display: flex;
            gap: 0.25rem;
        }
        
        .star {
            color: #fbbf24;
        }
        
        .star-empty {
            color: #d1d5db;
        }
    </style>
</head>
<body>
    <div class="unit-details-container">
        <a href="customer.php" class="back-link">
            <i data-lucide="arrow-left" width="20"></i>
            Back to Listings
        </a>
        
        <div class="unit-header-section">
            <div class="unit-title-section">
                <div class="unit-title">
                    <h1>Unit <?php echo htmlspecialchars($unit['unit_number']); ?></h1>
                    <p style="color: var(--text-secondary);"><?php echo htmlspecialchars($unit['location'] ?? 'Kagay an View, Cagayan de Oro'); ?></p>
                    <?php if ($totalReviews > 0): ?>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                            <div class="review-rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i data-lucide="<?php echo $i <= $avgRating ? 'star' : 'star'; ?>" width="16" height="16" class="<?php echo $i <= $avgRating ? 'star' : 'star-empty'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span style="color: var(--text-secondary);"><?php echo $avgRating; ?> (<?php echo $totalReviews; ?> reviews)</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="unit-price-large">
                    <?php echo formatCurrency($unit['monthly_rent']); ?><small style="font-size: 1rem;">/month</small>
                </div>
            </div>
            
            <div class="unit-specs">
                <div class="spec-item">
                    <i data-lucide="bed" width="24" style="color: var(--primary-color);"></i>
                    <div>
                        <div style="font-weight: 600;"><?php echo $unit['bedrooms']; ?> Bedrooms</div>
                    </div>
                </div>
                <div class="spec-item">
                    <i data-lucide="bath" width="24" style="color: var(--primary-color);"></i>
                    <div>
                        <div style="font-weight: 600;"><?php echo $unit['bathrooms']; ?> Bathrooms</div>
                    </div>
                </div>
                <div class="spec-item">
                    <i data-lucide="maximize" width="24" style="color: var(--primary-color);"></i>
                    <div>
                        <div style="font-weight: 600;"><?php echo $unit['area_sqm']; ?> sqm</div>
                    </div>
                </div>
                <div class="spec-item">
                    <i data-lucide="layers" width="24" style="color: var(--primary-color);"></i>
                    <div>
                        <div style="font-weight: 600;">Floor <?php echo $unit['floor']; ?></div>
                    </div>
                </div>
                <div class="spec-item">
                    <i data-lucide="users" width="24" style="color: var(--primary-color);"></i>
                    <div>
                        <div style="font-weight: 600;">Capacity: <?php echo $unit['capacity'] ?? 2; ?> people</div>
                    </div>
                </div>
            </div>
            
            <!-- Availability Status -->
            <div style="text-align: center; margin-top: 1rem;">
                <span style="display: inline-block; padding: 0.5rem 1.5rem; background: <?php echo $unit['status'] === 'available' ? 'var(--success-light)' : 'var(--danger-light)'; ?>; color: <?php echo $unit['status'] === 'available' ? 'var(--success-color)' : 'var(--danger-color)'; ?>; border-radius: 9999px; font-weight: 600; text-transform: uppercase; font-size: 0.875rem;">
                    <?php echo $unit['status'] === 'available' ? '✓ Available' : 'Occupied'; ?>
                </span>
            </div>
        </div>
        
        <?php if ($unit['description']): ?>
        <div class="unit-description-section">
            <h2 style="margin-bottom: 1rem;">Description</h2>
            <p style="line-height: 1.8; color: var(--text-secondary);"><?php echo nl2br(htmlspecialchars($unit['description'])); ?></p>
            <?php if ($unit['house_rules']): ?>
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
                    <h3 style="margin-bottom: 1rem;">House Rules</h3>
                    <div style="white-space: pre-wrap; color: var(--text-secondary);"><?php echo nl2br(htmlspecialchars($unit['house_rules'])); ?></div>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($amenities)): ?>
        <div class="amenities-section">
            <h2 style="margin-bottom: 1rem;">Amenities</h2>
            <div class="amenities-grid">
                <?php 
                $amenityIcons = [
                    'WiFi' => 'wifi',
                    'Air Conditioning' => 'wind',
                    'Private Bathroom' => 'bath',
                    'Parking' => 'car',
                    'Balcony' => 'home'
                ];
                foreach ($amenities as $amenity): 
                    $icon = $amenityIcons[trim($amenity)] ?? 'check';
                ?>
                    <div class="amenity-item">
                        <i data-lucide="<?php echo $icon; ?>" width="20" style="color: var(--success-color);"></i>
                        <span><?php echo htmlspecialchars(trim($amenity)); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="booking-section">
            <h3>Interested in this unit?</h3>
            <p style="margin-bottom: 1.5rem; opacity: 0.9;">Reserve this unit now or schedule a viewing</p>
            <?php if ($isLoggedIn): ?>
                <a href="booking.php?unit_id=<?php echo $unit['id']; ?>" class="btn-booking" style="text-decoration: none; display: inline-block;">
                    <i data-lucide="calendar-check" width="20" style="vertical-align: middle;"></i>
                    Reserve Now
                </a>
            <?php else: ?>
                <a href="login.php?redirect=<?php echo urlencode('booking.php?unit_id=' . $unit['id']); ?>" class="btn-booking" style="text-decoration: none; display: inline-block;">
                    <i data-lucide="log-in" width="20" style="vertical-align: middle;"></i>
                    Login to Reserve
                </a>
            <?php endif; ?>
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.2);">
                <p style="margin-bottom: 0.5rem;"><strong>Deposit Required:</strong> <?php echo formatCurrency($unit['deposit_amount'] ?? $unit['monthly_rent']); ?></p>
                <p><strong>Capacity:</strong> Up to <?php echo $unit['capacity'] ?? 2; ?> people</p>
            </div>
        </div>
        
        <div class="reviews-section">
            <h2 style="margin-bottom: 1rem;">Reviews</h2>
            <?php if ($reviewsResult && $reviewsResult->num_rows > 0): ?>
                <?php while($review = $reviewsResult->fetch_assoc()): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <div>
                                <strong><?php echo htmlspecialchars($review['customer_name']); ?></strong>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;"> - <?php echo formatDate($review['review_date']); ?></span>
                            </div>
                            <div class="review-rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i data-lucide="<?php echo $i <= $review['rating'] ? 'star' : 'star'; ?>" width="16" height="16" class="<?php echo $i <= $review['rating'] ? 'star' : 'star-empty'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if ($review['title']): ?>
                            <h4 style="margin: 0.5rem 0;"><?php echo htmlspecialchars($review['title']); ?></h4>
                        <?php endif; ?>
                        <p style="color: var(--text-secondary); line-height: 1.6;"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">No reviews yet. Be the first to review this unit!</p>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        lucide.createIcons();
    </script>
</body>
</html>

