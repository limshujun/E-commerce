<?php
include("../config.php");

// Fetch data for the list of products
$query = "SELECT id AS product_id, productname AS product_name, description, price, availableunit, item, productcode, image
          FROM products";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin List of Products - Nita's Online Grocery</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body style="min-width: 980px;">
	<div class="admin-product-list-container">
	<header>
            <h1>List of Products - Nita's Online Grocery</h1>
    </header>
	<div class="admin-navigation">
        <a href="admin_list_of_product.php">List of Products</a>
        <a href="admin_report.php">Sales Reports</a>
        <a href="admin_customers.php">All Customers</a>
    </div>

    <!-- Admin Product List Section -->
        <h2>Admin List of Products</h2>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Available Units</th>
                <th>Item</th>
                <th>Product Code</th>
                <th>Image</th>
            </tr>

            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['product_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['availableunit']}</td>
                        <td>{$row['item']}</td>
                        <td>{$row['productcode']}</td>
                        <td><img src='{$row['image']}' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>
                    </tr>";
            }
            ?>
        </table>
    </div>

</body>

</html>
