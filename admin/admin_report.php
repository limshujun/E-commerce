<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_login'])) {
    header("location: login.php");
    exit();
} else {
    $user = $_SESSION['user_login'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE id='$user' AND isAdmin=1");
    $num_rows = mysqli_num_rows($result);
    if ($num_rows !== 1) {
        // Redirect if the user is not an admin
        header("location: index.php");
        exit();
    }
    $get_user_email = mysqli_fetch_assoc($result);
    $uname_db = $get_user_email['firstname'];
}
$search_value = "";
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Report - Nita's Online Grocery</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        input[type=button],
        input[type=submit] {
            color: #EFE4D9;
            background: #9A3B3B;
            border-radius: 25px;
        }

        input[type=text] {
            background: #EFE4D9 border-radius: 25px;
        }
    </style>
</head>

<body>
    <?php include("admin_header.php"); ?>
    <h1 style="position: relative; padding-left: 10px;">
        <a href="index.php" style="text-decoration: none; color: black;">&lt;</a>
        Admin Report
    </h1>

    <div style="padding:0 10px;">
        <div style="text-align: right;">
            <form action="admin_report.php" method="post">
                <label for="selected_month">Select Month:</label>
                <input type="month" id="selected_month" name="selected_month" required>
                <input type="submit" value="Apply Filter">
            </form>
        </div>

        <table border="1" width="100%" id="admin_report_table">
            <tr>
                <th>User ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
            </tr>

            <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedMonth = $_POST["selected_month"];

    // Validate and sanitize the selected month (you can customize this based on your requirements)
    $selectedMonth = mysqli_real_escape_string($conn, $selectedMonth);

    // Convert the selected month to a date range (e.g., first day of the month to last day of the month)
    $startDate = date('Y-m-01', strtotime($selectedMonth));
    $endDate = date('Y-m-t', strtotime($selectedMonth));

    $query = "SELECT o.userid, p.productname, p.description, p.price, oi.quantity, oi.quantity * p.price AS totalprice, o.orderdate
              FROM order_items oi
              INNER JOIN orders o ON oi.order_id = o.order_id
              INNER JOIN products p ON oi.productid = p.id
              WHERE o.orderdate BETWEEN '$startDate' AND '$endDate'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Set message based on result availability
    $message = mysqli_num_rows($result) > 0 ? "" : "No data available for the selected month.";

    // Initialize $totalSales to 0
    $totalSales = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $totalSales += $row['totalprice'];
            echo "<tr>
                    <td>{$row['userid']}</td>
                    <td>{$row['productname']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['totalprice']}</td>
                    <td>{$row['orderdate']}</td>
                </tr>";
        }
        echo "<tr>
                <td colspan='4'></td>
                <td>Total Sales:</td>
                <td>" . number_format($totalSales, 2) . "</td>
                <td colspan='1'></td>
            </tr>";
    } else {
        // Display the message if there is no data
        echo "<tr><td colspan='7'>$message</td></tr>";
    }
}
?>

        </table>
    </div>
</body>

</html>
