<?php
session_start();

$host= "localhost";
$database = "dbuolfd7bidaqc";
$username = "uzjxte4jj5gyg";
$password = "hyfirofzudwo";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Failed to connect to dbulfd7bidaqc (Project 3) database because of the following error: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];
    $quantity = max(1, intval($_POST["quantity"]));

    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    if (isset($_SESSION["cart"][$product_id])) {
        $_SESSION["cart"][$product_id] += $quantity;
    } else {
        $_SESSION["cart"][$product_id] = $quantity;
    }

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
        function toggleDescription(id) {
            const desc = document.getElementById("desc-" + id);
            desc.style.display = desc.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>

<div class="nav_bar">
    <div class="logo">
        Jane's Fav Books
        <img src="images/favicon.ico" alt="favicon">
    </div>
    
    <div class="nav_bar_items">
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="orders.php">Orders</a>
    </div>
</div>

<div class="indiv_product_box">
    <?php
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        echo '<div class="indiv_product">';
        echo '<img src="' . $row["image_url"] . '" alt="' . htmlspecialchars($row["name"]) . '">';
        echo '<h3>' . $row["name"] . '</h3>';
        echo '<p>$' . number_format($row["price"], 2) . '</p>';
        echo '<form method="POST" class="product_buttons">';
        echo '<input type="hidden" name="product_id" value="' . $id . '">';
        echo '<label>Qty: <select name="quantity">';
        
        for ($i = 1; $i <= 5; $i++) {
            echo "<option value=\"$i\">$i</option>";
        }

        echo '</select></label><br><br>';
        echo '<button type="submit">Add to Cart</button>';
        echo '<button type="button" onclick="toggleDescription(' . $id . ')">More</button>';
        echo '</form>';
        echo '<div class="description" id="desc-' . $id . '" style="display:none;">' . $row["description"] . '</div>';
        echo '</div>';
    }

    $conn->close();
    ?>
</div>

</body>
</html>
