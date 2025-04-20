<?php
// Start the session to retrieve cart data from the products page, use it to access the shopping cart session variable $_SESSION['cart'] that 
// initialized and built on products page
session_start();

// Set up being able to remove items from the cart once they're added
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
}

// Set up variable to store the total amount of cart so can display it on page
$cart_total = 0;
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

    <!-- Create the actual cart object on page now -->
    <div class="cart_table">
        <!-- Check if there are any items in the cart yet bc if not, then display message that cart is empty -->
        <?php if (!empty($_SESSION['cart'])): ?>
            <!-- If there are items in cart then set up the cart table -->
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
                    <!-- Feth everything from session variable from products page -->
                    <?php foreach ($_SESSION['cart'] as $product_id => $item):
                        // Want display cart total on page so calculate that and update it every time add item to cart
                        // Calc it based on how many of each item have
                        $total = $item['price'] * $item['quantity'];
                        $cart_total += $total;
                    ?>

                        <!-- Now actually add all parts of the product in -->
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($total, 2); ?></td>
                            <td>
                                <!-- Give option to remove book from cart using remove button -->
                                <a href="?remove=<?php echo $product_id; ?>" class="remove_button">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Now display the total on the page -->
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($cart_total, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <!-- Put the 2 wanted buttons at the bottom of the cart -->
            <div class="cart_buttons">
                <a href="products.php">Continue Shopping</a>
                <a href="thankyou.php">Check Out</a>
            </div>

        <?php else: ?>
            <!-- Where go if the cart has no items in it -->
            <p class="empty_cart_message">Boo there are no books in your cart, add some now!</p>

            <!-- Add option for user to easily get to products page to add their books in -->
            <div class="cart_buttons">
            <a href="products.php">Get Shopping</a>
            </div>
        <?php endif; ?> 
    </div>
</body>
</html>
