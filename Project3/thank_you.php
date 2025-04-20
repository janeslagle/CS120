<?php
// Start the php session
session_start();

// Have connect to the DB to be able to do anything
$host = "localhost";
$database = "dbuolfd7bidaqc";
$username = "uzjxte4jj5gyg";
$password = "hyfirofzudwo";

$DB_conn = new mysqli($host, $username, $password, $database);

if ($DB_conn->connect_error) {
    die("Failed to connect to dbulfd7bidaqc (Project 3) database because of the following error: " . $DB_conn->connect_error);
}

// Get the order id out from cart page so that can display the info from it
$order_id = $_GET['order_id'] ?? null;

// Make sure the order id is valid before try to display anything
if (!$order_id) {
    die("There was an error processing your order, please try placing it again. Sorry for the inconveience.");
}

// Make SQL statement to get the info want from order out to display on the page
// Use the order id from the order from the cart page
$sql_stmt = $DB_conn->prepare("SELECT * FROM orders WHERE id = ?");
$sql_stmt->bind_param("i", $order_id);
$sql_stmt->execute();
$result = $sql_stmt->get_result();
$order = $result->fetch_assoc();
$sql_stmt->close();

// Get the items out from the order
// items col from orders DB is json object so make it an array
$items = json_decode($order['items'], true); 

// Calculate the expected ship date = 2 days from the current date
// Display it in a way such that the day of the week comes before the actual date
$expected_ship_date = date('l, F j, Y', strtotime('+2 days')); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Thank You - Jane's Fav Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee&display=swap" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="stylesheets/thank_you.css">
</head>
<body>
    <!-- Display the navigation bar --> 
    <div class="nav_bar">
        <div class="logo">
            Jane's Fav Books
            <img src="images/favicon.ico" alt="favicon">
        </div>

        <!-- Display the other items in the nav bar -->
        <div class="nav_bar_items">
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="orders.php">Orders</a>
        </div>
    </div>

    <!-- Display the thank you message -->
    <div class="thank_you_message">
        <h1>Thank you <i>so much </i>for your order!</h1>

        <!-- Display the order total and the expected ship date, using the total_price and expected_ship_date from the carts page -->
        <p><strong>Order Total:</strong> $<?= number_format($order['total_price'], 2) ?></p>
        <p><strong>Expected Shipping Date:</strong> <?= $expected_ship_date ?></p>
        <p class="smaller_note"><i>Don't worry, you won't have to wait too long to start reading your brand new books!</i></p>

        <!-- Put 2 buttons, one that will take you back to the products page + one that will take you to the orders page -->
        <div class="thank_you_buttons">
            <a href="products.php" class="button">Buy More Books</a>
            <a href="orders.php" class="button">Order History</a>
        </div>
    </div>
</body>
</html>
