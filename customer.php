<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Units - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Customer-specific styles */
        .customer-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }

        .customer-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .customer-header p {
            font-size: 1.125rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-section {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-light);
        }

        .search-container {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-input input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-size: 1rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-input input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-input .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            background: white;
            color: var(--text-secondary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .units-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .unit-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .unit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--success-color) 0%, #059669 100%);
        }

        .unit-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--success-color);
        }

        .unit-image {
            height: 200px;
            background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .unit-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .unit-image-placeholder {
            color: var(--info-color);
            font-size: 3rem;
        }

        .unit-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(16, 185, 129, 0.9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .unit-content {
            padding: 1.5rem;
        }

        .unit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .unit-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .unit-price {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--success-color);
        }

        .unit-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: rgba(79, 70, 229, 0.05);
            border-radius: var(--radius);
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .detail-item i {
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .unit-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .unit-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-primary {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: white;
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .no-units {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
            grid-column: 1 / -1;
        }

        .no-units i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-units h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .no-units p {
            font-size: 1rem;
        }

        .contact-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: var(--radius-xl);
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        .contact-section h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .contact-section p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .contact-item i {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .customer-header h1 {
                font-size: 2rem;
            }

            .search-container {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                min-width: auto;
            }

            .filter-buttons {
                justify-content: center;
            }

            .units-grid {
                grid-template-columns: 1fr;
            }

            .unit-details {
                grid-template-columns: 1fr;
            }

            .unit-actions {
                flex-direction: column;
            }

            .contact-info {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Customer Header -->
    <div class="customer-header">
        <div class="container">
            <h1>Find Your Perfect Home</h1>
            <p>Discover beautiful, modern apartments at Kagay an View. Browse our available units and find the perfect place to call home.</p>
        </div>
    </div>

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <div class="search-input">
                    <i data-lucide="search" class="search-icon"></i>
                    <input type="text" placeholder="Search by unit number, bedrooms, or features..." id="unitSearch">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" onclick="filterUnits('all')">
                        <i data-lucide="grid-3x3" width="16"></i>
                        All Units
                    </button>
                    <button class="filter-btn" onclick="filterUnits('studio')">
                        <i data-lucide="home" width="16"></i>
                        Studio
                    </button>
                    <button class="filter-btn" onclick="filterUnits('1-bedroom')">
                        <i data-lucide="bed" width="16"></i>
                        1 Bedroom
                    </button>
                    <button class="filter-btn" onclick="filterUnits('2-bedroom')">
                        <i data-lucide="bed" width="16"></i>
                        2 Bedrooms
                    </button>
                    <button class="filter-btn" onclick="filterUnits('3-bedroom')">
                        <i data-lucide="bed" width="16"></i>
                        3+ Bedrooms
                    </button>
                </div>
            </div>
        </div>

        <!-- Units Grid -->
        <div class="units-grid" id="unitsGrid">
            <?php
            // Get available units
            $units = $conn->query("
                SELECT u.*, 
                       CASE 
                           WHEN u.bedrooms = 0 THEN 'Studio'
                           WHEN u.bedrooms = 1 THEN '1 Bedroom'
                           WHEN u.bedrooms = 2 THEN '2 Bedrooms'
                           ELSE CONCAT(u.bedrooms, '+ Bedrooms')
                       END as bedroom_type
                FROM units u
                WHERE u.status = 'available'
                ORDER BY u.unit_number
            ");
            
            if ($units->num_rows > 0) {
                while($unit = $units->fetch_assoc()) {
                    $bedroomText = $unit['bedrooms'] == 1 ? '1 Bedroom' : ($unit['bedrooms'] == 0 ? 'Studio' : $unit['bedrooms'] . ' Bedrooms');
                    $bathroomText = $unit['bathrooms'] == 1 ? '1 Bathroom' : $unit['bathrooms'] . ' Bathrooms';
                    $areaText = $unit['area_sqm'] ? $unit['area_sqm'] . ' sqm' : 'N/A';
                    $rentAmount = formatCurrency($unit['monthly_rent']);
                    
                    echo "<div class='unit-card' data-bedrooms='{$unit['bedrooms']}' data-bedroom-type='{$unit['bedroom_type']}'>
                            <div class='unit-image'>
                                <div class='unit-image-placeholder'>
                                    <i data-lucide='home' width='48' height='48'></i>
                                </div>
                                <div class='unit-badge'>Available</div>
                            </div>
                            <div class='unit-content'>
                                <div class='unit-header'>
                                    <h3 class='unit-title'>Unit {$unit['unit_number']}</h3>
                                    <div class='unit-price'>{$rentAmount}/month</div>
                                </div>
                                <div class='unit-details'>
                                    <div class='detail-item'>
                                        <i data-lucide='bed' width='16'></i>
                                        <span>{$bedroomText}</span>
                                    </div>
                                    <div class='detail-item'>
                                        <i data-lucide='bath' width='16'></i>
                                        <span>{$bathroomText}</span>
                                    </div>
                                    <div class='detail-item'>
                                        <i data-lucide='maximize' width='16'></i>
                                        <span>{$areaText}</span>
                                    </div>
                                    <div class='detail-item'>
                                        <i data-lucide='calendar' width='16'></i>
                                        <span>Available Now</span>
                                    </div>
                                </div>
                                <div class='unit-description'>
                                    Beautiful " . strtolower($bedroomText) . " apartment with modern amenities. Perfect for professionals and families looking for comfortable living in a great location.
                                </div>
                                <div class='unit-actions'>
                                    <button class='btn-primary' onclick='viewUnitDetails({$unit['id']})'>
                                        <i data-lucide='eye' width='16'></i>
                                        View Details
                                    </button>
                                    <button class='btn-secondary' onclick='contactUs()'>
                                        <i data-lucide='phone' width='16'></i>
                                        Contact
                                    </button>
                                </div>
                            </div>
                          </div>";
                }
            } else {
                echo "<div class='no-units'>
                        <i data-lucide='home'></i>
                        <h3>No Available Units</h3>
                        <p>All units are currently occupied. Please check back later or contact us for updates.</p>
                      </div>";
            }
            ?>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h3>Interested in Renting?</h3>
            <p>Contact us today to schedule a viewing or get more information about our available units.</p>
            <div class="contact-info">
                <div class="contact-item">
                    <i data-lucide="phone" width="20" height="20"></i>
                    <span>+63 912 345 6789</span>
                </div>
                <div class="contact-item">
                    <i data-lucide="mail" width="20" height="20"></i>
                    <span>info@kagayanview.com</span>
                </div>
                <div class="contact-item">
                    <i data-lucide="map-pin" width="20" height="20"></i>
                    <span>Kagay an View, Cagayan de Oro</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Unit Details Modal -->
    <div id="unitModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Unit Details</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i data-lucide="x" width="24" height="24"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Unit details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Close</button>
                <button class="btn-primary" onclick="contactUs()">
                    <i data-lucide="phone" width="16"></i>
                    Contact Us
                </button>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Search functionality
        document.getElementById('unitSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.unit-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Filter functionality
        function filterUnits(type) {
            const cards = document.querySelectorAll('.unit-card');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Update active filter button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            cards.forEach(card => {
                if (type === 'all') {
                    card.style.display = '';
                } else if (type === 'studio' && card.dataset.bedrooms === '0') {
                    card.style.display = '';
                } else if (type === '1-bedroom' && card.dataset.bedrooms === '1') {
                    card.style.display = '';
                } else if (type === '2-bedroom' && card.dataset.bedrooms === '2') {
                    card.style.display = '';
                } else if (type === '3-bedroom' && parseInt(card.dataset.bedrooms) >= 3) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
                
                if (card.style.display !== 'none') {
                    card.style.animation = 'fadeIn 0.3s ease';
                }
            });
        }

        // View unit details
        function viewUnitDetails(unitId) {
            // For now, show a simple modal with unit info
            const modal = document.getElementById('unitModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            
            modalTitle.textContent = 'Unit ' + unitId + ' Details';
            modalBody.innerHTML = `
                <div style="text-align: center; padding: 2rem;">
                    <i data-lucide="home" width="48" height="48" style="color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h4>Unit ` + unitId + `</h4>
                    <p>This unit features modern amenities and is perfect for comfortable living.</p>
                    <div style="margin-top: 1rem;">
                        <p><strong>Features:</strong></p>
                        <ul style="text-align: left; max-width: 300px; margin: 0 auto;">
                            <li>Modern kitchen with appliances</li>
                            <li>Spacious living area</li>
                            <li>Private balcony</li>
                            <li>Air conditioning</li>
                            <li>Parking space</li>
                        </ul>
                    </div>
                </div>
            `;
            
            modal.style.display = 'flex';
            lucide.createIcons();
        }

        // Close modal
        function closeModal() {
            document.getElementById('unitModal').style.display = 'none';
        }

        // Contact us function
        function contactUs() {
            alert('Contact us at +63 912 345 6789 or email info@kagayanview.com to schedule a viewing!');
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }
            
            .modal-content {
                background: white;
                border-radius: var(--radius-xl);
                box-shadow: var(--shadow-xl);
                max-width: 500px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
            }
            
            .modal-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--border-light);
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .modal-body {
                padding: 1.5rem;
            }
            
            .modal-footer {
                padding: 1.5rem;
                border-top: 1px solid var(--border-light);
                display: flex;
                gap: 1rem;
                justify-content: flex-end;
            }
            
            .modal-close {
                background: none;
                border: none;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: var(--radius);
                transition: all 0.2s ease;
            }
            
            .modal-close:hover {
                background: var(--border-light);
            }
        `;
        document.head.appendChild(style);

        // Add hover effects to unit cards
        document.querySelectorAll('.unit-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
