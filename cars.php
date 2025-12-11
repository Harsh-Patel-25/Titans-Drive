<?php
require_once 'config.php';

$db = new Database();
$conn = $db->connect();

// Get all available cars
$stmt = $conn->prepare("SELECT * FROM cars WHERE availability_status = 'available' ORDER BY model_name");
$stmt->execute();
$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Fleet - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>


    <section class="page-header">
        <div class="container">
            <h1>Our Premium BMW Fleet</h1>
            <p>Choose from our exclusive collection of BMW vehicles</p>
        </div>
    </section>

    <section class="cars-section">
        <div class="container">
            <!-- Filters -->
            <div class="filters-container">
                <h3>Filter Cars</h3>
                <div class="filters-grid">
                    <div class="filter-group">
                        <label>Series</label>
                        <select id="filter_series" class="form-control" onchange="filterCars()">
                            <option value="">All Series</option>
                            <option value="3">3 Series</option>
                            <option value="5">5 Series</option>
                            <option value="7">7 Series</option>
                            <option value="X">X Series</option>
                            <option value="M">M Series</option>
                            <option value="i">i Series (Electric)</option>
                            <option value="Z">Z Series</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Transmission</label>
                        <select id="filter_transmission" class="form-control" onchange="filterCars()">
                            <option value="">All</option>
                            <option value="automatic">Automatic</option>
                            <option value="manual">Manual</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Fuel Type</label>
                        <select id="filter_fuel" class="form-control" onchange="filterCars()">
                            <option value="">All</option>
                            <option value="petrol">Petrol</option>
                            <option value="diesel">Diesel</option>
                            <option value="electric">Electric</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Max Price/Day (₹)</label>
                        <input type="number" id="filter_price" class="form-control" placeholder="20000" onchange="filterCars()">
                    </div>
                </div>
            </div>

            <!-- Cars Grid -->
            <div class="cars-grid mt-3">
                <?php foreach($cars as $car): ?>
                    <div class="car-card" 
                         data-series="<?php echo strtolower($car['series']); ?>"
                         data-transmission="<?php echo strtolower($car['transmission']); ?>"
                         data-fuel="<?php echo strtolower($car['fuel_type']); ?>"
                         data-price="<?php echo $car['price_per_day']; ?>">
                        <?php 
                        $image = !empty($car['image_url']) ? $car['image_url'] : 'images/default_car.                       jpg';
                        ?>
                        <div class="car-image" style="
                            background-image: url('<?php echo $image; ?>');
                            background-size: cover;
                            background-position: center;">
                            <span class="car-badge"><?php echo $car['year']; ?></span>
                        </div>

                        <div class="car-info">
                            <h3><?php echo htmlspecialchars($car['model_name']); ?></h3>
                            <p class="car-series"><?php echo htmlspecialchars($car['series']); ?></p>
                            <div class="car-specs">
                                <span>⚙️ <?php echo $car['transmission']; ?></span>
                                <span>⛽ <?php echo $car['fuel_type']; ?></span>
                                <span>👥 <?php echo $car['seats']; ?> Seats</span>
                            </div>
                            <p class="car-description">
                                <?php echo htmlspecialchars(substr($car['description'], 0, 80)) . '...'; ?>
                            </p>
                            <div class="car-price">
                                <span class="price">₹<?php echo number_format($car['price_per_day']); ?></span>
                                <span class="per-day">/day</span>
                            </div>
                            <a href="car_details.php?id=<?php echo $car['car_id']; ?>" class="btn btn-primary btn-block">
                                View Details & Book
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    
    <style>
        .page-header {
            color: #fff;
            padding: 3rem 0;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: var(--bmw-blue);
        }
        .page-header p {
            color: var(--bmw-blue);
        }
        
        .cars-section {
            padding: 3rem 0;
        }
        
        .filters-container {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .car-description {
            color: var(--gray-color);
            font-size: 0.9rem;
            margin: 1rem 0;
            min-height: 60px;
        }
    </style>
</body>
</html>