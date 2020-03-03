<?php
include_once 'header.php';
include_once 'class/purchases.inc.php';
$purchase_obj = new purchases();
if (isset($_GET['id'])) {
    $purchases_array = $purchase_obj->calpurchase_list_byid($_GET['id']);
    $clonePurchases = $purchase_obj->calpurchase_clone($purchases_array);
} else {
    header('Location: index.php');
}

if ($clonePurchases) {
	$message = "Clone Successful. Returning to Purchase List";
}else{
	$message = "Clone Failed. Returning to Purchase List";
}
	echo "<script type='text/javascript'>redirectToIndex('$message');</script>";
?>