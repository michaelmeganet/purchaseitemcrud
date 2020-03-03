<?php
include_once 'header.php';
include_once 'class/purchases.inc.php';
$purchase_obj = new purchases();
if (isset($_GET['id'])) {
    $delPurchases = $purchase_obj->calpurchase_delete($_GET['id']);
} else {
    header('Location: index.php');
}

if ($delPurchases) {
	$message = "Delete Successful. Returning to Purchase List";
}else{
	$message = "Delete Failed. Returning to Purchase List";
}
	echo "<script type='text/javascript'>redirectToIndex('$message');</script>";
?>