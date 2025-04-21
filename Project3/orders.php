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
    die("Connection failed: " . $DB_conn->connect_error);
}

// Fetch all orders from DB, make sure the most recent order put in the DB is first
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $DB_conn->query($sql);

if (!$result) {
    die("Unable to pull up order history: " . $DB_conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History - Jane's Fav Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee&display=swap" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="stylesheets/orders.css">
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

    <!-- Center everything on the page -->
    <div class="center_page">
        <!-- Display all orders -->
        <div class="orders_section">
            <h1>Order History</h1>

            <!-- Display the orders if there are even orders to display -->
            <?php if ($result->num_rows > 0): ?>
                <!-- Loop through all orders until have gone through them all -->
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div>
                        <h2>
                            <!-- Display order num, date, order total -->
                            Order #<?= $order['id'] ?> — <?= date("F j, Y", strtotime($order['order_date'])) ?>, 
                            $<?= number_format($order['total_price'], 2) ?>
                        </h2>

                        <p class="center_this"><strong>Items Ordered:</strong></p>

                        <!-- Display the book, quantity, price + total for each order -->
                        <table class="order_items">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php
                                // Decode all of the items from the order items col: it's a json item
                                $items = json_decode($order['items'], true);
                                foreach ($items as $item):
                                    $item_total = $item['price'] * $item['quantity'];
                                ?>
                                    
                                    <tr>
                                        <td><?= $item['name'] ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>$<?= number_format($item['price'], 2) ?></td>
                                        <td>$<?= number_format($item_total, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- What to do if there are zero orders, then put this -->
                <p class="empty_orders_message">No orders have been placed yet! Go to the products page to buy some books!</p>
                <div class="cart_buttons">
                    <a href="products.php">Start Shopping</a>
                </div>
            <?php endif; ?>

            <?php $DB_conn->close(); ?>
        </div>
    </div>
</body>
</html>
