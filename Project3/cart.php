<?php
// Start the session to retrieve cart data from the products page, use it to access the shopping cart session variable $_SESSION['cart'] that 
// initialized and built on products page
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

// Set up being able to remove items from the cart once they're added
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
}

// Set up variable to store the total amount of cart so can display it on page
$cart_total = 0;

// Calculate total price if not already set
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_total += $item['price'] * $item['quantity'];
    }
}

// Handle everything for pressing the check out button: need to insert the cart into the orders DB and clear the cart and go to the thank you page
// Only do all of this if the cart isn't empty (if cart is empty, the check out button won't even pop up)
if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    // Get all of the info out that need to insert into the orders DB
    // Need get the order_date, items that store as json item and the order price which have stored as total_price in the DB
    $order_date = date('Y-m-d H:i:s');
    $items_json = json_encode($_SESSION['cart']);  // Get the cart (the order items) out of the session variable that got from the products page
    $total_price = $cart_total;

    // Make SQL statement to insert everything for the order into the orders DB 
    $sql_stmt = $DB_conn->prepare("INSERT INTO orders (order_date, items, total_price) VALUES (?, ?, ?)");
    $sql_stmt->bind_param("ssd", $order_date, $items_json, $total_price);
    $sql_stmt->execute();
    $order_id = $sql_stmt->insert_id;
    $sql_stmt->close();

    // Need to clear the cart
    unset($_SESSION['cart']);

    // And then need to open up the thank you page when the check out button is clicked! So redirect to that page here with the order id 
    // set for the order we just added to the DB (want to display info for the order just added to DB)
    header("Location: thank_you.php?order_id=$order_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart - Jane's Fav Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee&display=swap" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="stylesheets/cart.css">
</head>
<body>
    <!-- Display the navigation bar, exact same as products page!!! -->
    <div class="nav_bar">
        <div class="logo">
            Jane's Fav Books <img src="images/favicon.ico" alt="favicon">
        </div>

        <!-- Display the other items in the nav bar -->
        <div class="nav_bar_items">
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="orders.php">Orders</a>
        </div>
    </div>

    <!-- Create the actual cart object on page now -->
    <div class="cart_table">
        <!-- Check if there are any items in the cart yet bc if not, then display message that cart is empty -->
        <?php if (!empty($_SESSION['cart'])): ?>
            <table>
                <thead>
                    <!-- Set up all sections want on the cart -->
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Want to read book?</th>
                    </tr>
                </thead>

                <!-- Now actually add in cart rows have -->
                <tbody>
                    <!-- Fetch everything from session variable from products page -->
                    <?php foreach ($_SESSION['cart'] as $product_id => $item):
                        // Want display cart total on page so calculate that and update it every time add item to cart
                        // Calc it based on how many of each item have
                        $item_total = $item['price'] * $item['quantity'];
                    ?>

                        <!-- Now actually add all parts of the product in -->
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item_total, 2) ?></td>
                            <!-- Give option to remove book from cart using remove button -->
                            <td><a href="?remove=<?= $product_id ?>" class="remove_button">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Now display the total on the page -->
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td><strong>$<?= number_format($cart_total, 2) ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <!-- Put the 2 wanted buttons at the bottom of the cart -->
            <div class="cart_buttons">
                <a href="products.php">Continue Shopping</a>
                
                <form action="cart.php" method="POST">
                    <button type="submit" name="checkout">Check Out</button>
                </form>
            </div>

        <?php else: ?>
            <!-- Where go if the cart has no items in it -->
            <p class="empty_cart_message">Boo, there are no books in your cart, add some now and start reading!</p>
            
            <!-- Add option for user to easily get to products page to add their books in -->
            <div class="cart_buttons">
                <a href="products.php">Get Shopping</a>
            </div>
        <?php endif; ?> 
    </div>
</body>
</html>
