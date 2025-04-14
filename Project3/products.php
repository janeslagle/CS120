<?php
session_start();

// DB connection
$host = "localhost";
$username = "uzjxte4jj5gyg";
$password = "hyfirofzudwo";
$database = "dbuolfd7bidaqc";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];
    $quantity = max(1, intval($_POST["quantity"])); // Default to 1

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
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .nav {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }

        .nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
            gap: 20px;
        }

        .product {
            width: 220px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .product img {
            width: 150px;
            height: 200px;
            object-fit: cover;
        }

        .description {
            display: none;
            margin-top: 10px;
            font-size: 0.9em;
            color: #555;
        }

        .buttons {
            margin-top: 10px;
        }
    </style>
    <script>
        function toggleDescription(id) {
            const desc = document.getElementById("desc-" + id);
            desc.style.display = desc.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>

<div class="nav">
    <a href="products.php">Products</a>
    <a href="cart.php">Cart</a>
    <a href="orders.php">Orders</a>
</div>

<h2 style="text-align:center;">Book Store</h2>

<div class="product-container">
    <?php
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        echo '<div class="product">';
        echo '<img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
        echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
        echo '<p>$' . number_format($row["price"], 2) . '</p>';
        echo '<form method="POST" class="buttons">';
        echo '<input type="hidden" name="product_id" value="' . $id . '">';
        echo '<label>Qty: <select name="quantity">';
        for ($i = 1; $i <= 10; $i++) {
            echo "<option value=\"$i\">$i</option>";
        }
        echo '</select></label><br><br>';
        echo '<button type="submit">Add to Cart</button>';
        echo '<button type="button" onclick="toggleDescription(' . $id . ')">More</button>';
        echo '</form>';
        echo '<div class="description" id="desc-' . $id . '">' . htmlspecialchars($row["description"]) . '</div>';
        echo '</div>';
    }

    $conn->close();
    ?>
</div>

</body>
</html>
