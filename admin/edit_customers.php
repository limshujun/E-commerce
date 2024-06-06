<?php
include("../config.php");

// Handle form submission for updating isAdmin status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['userid']) && isset($_POST['newIsAdmin'])) {
        $userid = $_POST['userid'];
        $newIsAdmin = $_POST['newIsAdmin'];

        // Update isAdmin status in the database
        $updateQuery = "UPDATE user SET isAdmin = '$newIsAdmin' WHERE id = '$userid'";
        $updateResult = mysqli_query($conn, $updateQuery);

        if (!$updateResult) {
            // Display error if update fails
            echo "Error updating isAdmin status: " . mysqli_error($conn);
            exit();
        }
        // Confirmation message upon successful update
        echo "isAdmin status updated successfully!";
        exit();
    }
}

// Fetch user data for the table
$query = "SELECT id AS user_id, firstname, lastname, email, phoneno, isAdmin FROM user";
$result = mysqli_query($conn, $query);
if (!$result) {
    // Terminate script if the query fails
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>

<head>
    <script>
        function toggleEdit(button, userId, currentIsAdmin, cell) {
            if (button.textContent === "Edit") {
                // Create select element for isAdmin editing
                var select = document.createElement("select");
                select.innerHTML = "<option value='1'>Yes</option><option value='0'>No</option>";
                select.value = currentIsAdmin;

                // Create Save button for updating isAdmin
                var saveButton = document.createElement("button");
                saveButton.textContent = "Save";
                saveButton.onclick = function () {
                    // Retrieve new isAdmin value
                    var newIsAdmin = select.value;

                    // Perform AJAX call to update isAdmin
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            // Show response message and refresh the page
                            alert(this.responseText);
                            location.reload();
                        }
                    };
                    xhttp.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("userid=" + userId + "&newIsAdmin=" + newIsAdmin);
                };

                // Create Cancel button for reverting changes
                var cancelButton = document.createElement("button");
                cancelButton.textContent = "Cancel";
                cancelButton.onclick = function () {
                    // Revert cell content and button text to initial state
                    cell.innerHTML = currentIsAdmin === '1' ? 'Yes' : 'No';
                    button.textContent = "Edit";
                    button.parentNode.replaceChild(button.cloneNode(true), button);
                };

                // Replace cell content with select element
                cell.innerHTML = "";
                cell.appendChild(select);

                // Replace button content with Save and Cancel buttons
                button.textContent = "";
                button.appendChild(saveButton);
                button.appendChild(cancelButton);
            }
        }
    </script>
</head>

<body style="min-width: 980px;">
    <div class="admin-customers-container">
        <h2>Admin Customers</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>isAdmin</th>
                <th>Edit</th>
            </tr>

            <?php
            // Output user data as table rows
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['firstname']}</td>
                        <td>{$row['lastname']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phoneno']}</td>
                        <td>" . ($row['isAdmin'] === '1' ? 'Yes' : 'No') . "</td>
                        <td><button onclick=\"toggleEdit(this, {$row['user_id']}, '{$row['isAdmin']}', this.parentElement.previousElementSibling)\">Edit</button></td>
                    </tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>