<?php
// Start the php session
session_start();

// Have connect to the DB to be able to do anything
$host= "localhost";
$database = "dbuolfd7bidaqc";
$username = "uzjxte4jj5gyg";
$password = "hyfirofzudwo";

$DB_conn = new mysqli($host, $username, $password, $database);

if ($DB_conn->connect_error) {
    die("Failed to connect to dbulfd7bidaqc (Project 3) database because of the following error: " . $DB_conn->connect_error);
}

// Handle everything for the add to cart button
// Check if add to cart button was hit and there is even a product to add to the cart
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"])) {
    // Grab the id out so know what product are adding to cart
    $product_id = $_POST["product_id"];

    // Make sure the quantity is at least 1!!!
    $quantity = max(1, intval($_POST["quantity"]));

    // Check if cart already exists because if it is, then just want to add to the existing quantity for the product, don't want to create a 
    // whole new row for it in cart
    // So only create new section in cart for product if this is our very first time adding it to the cart
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    // If already have product in cart, then just add on to its existing quantity to make it like a real life website!!!
    if (isset($_SESSION["cart"][$product_id])) {
        $_SESSION["cart"][$product_id]["quantity"] += $quantity;
    } else {
        // Otherwise, if this is the first time adding it to the cart then have walk through getting everything from it
        // Fetch product details from the database
        // Get the name, price from the product from the DB (told in spec that don't want to show the image with it)
        $sql_stmt = $DB_conn->prepare("SELECT name, price FROM products WHERE id = ?");
        $sql_stmt->bind_param("i", $product_id);
        $sql_stmt->execute();

        // Get the result out from doing sql statement to get the wanted info out of product adding to cart
        $sql_result = $sql_stmt->get_result();
        $product_adding = $sql_result->fetch_assoc();

        // Now make session variable with the product info just got out of DB using sql statement
        $_SESSION["cart"][$product_id] = [
            "name" => $product_adding["name"],
            "price" => $product_adding["price"],
            "quantity" => $quantity,
        ];
        
        $sql_stmt->close();
    }

    // Send it to the cart page when hit the add to cart button
    header("Location: cart.php");
    exit();
} 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products - Jane's Fav Books</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee&display=swap" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="stylesheets/products.css">

    <script>
        // For when click the more button for each product
        function showBookDescription(id) {
            // Feed the row id to this function
            // Using that, create a unique id for each book so that grab the right one for each description display
            const book_description = document.getElementById("description-" + id);

            // Set it initially as none and then when click the more button, shows the block and then can click it again to make it go away, etc.
            book_description.style.display = book_description.style.display === "none" ? "block" : "none";
        }
    </script>
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

    <!-- Now display all products on the page -->
    <div class="indiv_product_box">
        <?php
        // Connect to the DB and display all of the products!
        $sql_DB = "SELECT * FROM products";
        $result = $DB_conn->query($sql_DB);

        // Loop through and display everything for each product
        while ($DB_row = $result->fetch_assoc()) {
            // Identify each row with its unique id from the DB
            $id = $DB_row["id"];

            // Display in this order: image, name, price, qty, add to cart, more buttons
            echo '<div class="indiv_product">';
            echo '<img src="' . $DB_row["image_url"] . '" alt="' . $DB_row["name"] . '">';
            echo '<h3>' . $DB_row["name"] . '</h3>';
            echo '<p>$' . number_format($DB_row["price"], 2) . '</p>';
            echo '<form method="POST" class="product_buttons">';
            echo '<input type="hidden" name="product_id" value="' . $id . '">';

            // Make the available quantity up to 5
            echo '<label>Qty: <select name="quantity">';
            for ($i = 1; $i <= 5; $i++) {
                echo "<option value=\"$i\">$i</option>";
            }

            echo '</select></label><br><br>';
            echo '<button type="submit">Add to Cart</button>';

            // Call function wrote for more button when create the more button
            echo '<button type="button" onclick="showBookDescription(' . $id . ')">More</button>';
            echo '</form>';

            // Have make initial display none otherwise have double click more button for the description to actually show
            echo '<div class="description" id="description-' . $id . '" style="display:none;">' . $DB_row["description"] . '</div>';
            echo '</div>';
    }

    // At this point, don't need anything else from the DB so close the connection to it
    $DB_conn->close();
    ?>
</div>
</body>
</html>
