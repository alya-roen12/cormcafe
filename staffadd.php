<?php
// Assign data from staff form into variables
$sid = $_POST['fStaffID'];
$sname = $_POST['fStaffName'];
$sphone = $_POST['fStaffPhone'];
$saddress = $_POST['fStaffAddress'];
$sdob = $_POST['fStaffDOB'];

// Connection to the server and database
$dbc = mysqli_connect("localhost", "root", "", "sales");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// SQL statement to insert data from form into staff table
$sql = "INSERT INTO `staff`(`StaffID`, `StaffName`, `Phone`, `Address`, `DOB`) 
        VALUES ('$sid', '$sname', '$sphone', '$saddress', '$sdob')";

$results = mysqli_query($dbc, $sql);

if ($results) {
    mysqli_commit($dbc);
    // Display message box
    print '<script>alert("Record Has Been Added");</script>';
    // Redirect back to form
    print '<script>window.location.assign("frmstaff.php");</script>';
} else {
    mysqli_rollback($dbc);
    // Display error message box
    print '<script>alert("Data Is Invalid, No Record Has Been Added");</script>';
    print '<script>window.location.assign("frmstaff.php");</script>';
}
?>
