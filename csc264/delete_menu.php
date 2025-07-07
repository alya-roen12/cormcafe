<?php
// Connect to database
$dbc = mysqli_connect("localhost", "root", "", "corm");

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if MenuID is provided
if (isset($_GET['id'])) {
    $menuID = intval($_GET['id']);

    // Step 1: Get current image filename
    $imageQuery = "SELECT MenuImage FROM menu WHERE MenuID = $menuID";
    $imageResult = mysqli_query($dbc, $imageQuery);

    if ($imageResult && mysqli_num_rows($imageResult) > 0) {
        $row = mysqli_fetch_assoc($imageResult);
        $imageFile = 'uploads/' . $row['MenuImage'];

        // Step 2: Delete image file from folder
        if (file_exists($imageFile)) {
            unlink($imageFile);
        }

        // Step 3: Delete the menu item from database
        $deleteQuery = "DELETE FROM menu WHERE MenuID = $menuID";
        $deleteResult = mysqli_query($dbc, $deleteQuery);

        if ($deleteResult) {
            echo "<script>
                alert('Menu deleted successfully.');
                window.location.href = 'editmenu.php';
            </script>";
        } else {
            echo "<script>
                alert('Failed to delete the menu.');
                window.location.href = 'editmenu.php';
            </script>";
        }

    } else {
        echo "<script>
            alert('Menu not found.');
            window.location.href = 'editmenu.php';
        </script>";
    }

} else {
    echo "<script>
        alert('Invalid request.');
        window.location.href = 'editmenu.php';
    </script>";
}
?>
