<?php
session_start();
unset($_SESSION['cart']);
$_SESSION['toast'] = "Cart cleared!";
header("Location: homepageaftersignin.php");
exit();
?>
