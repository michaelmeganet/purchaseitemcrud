<?php
include 'header.php';
include_once 'class/purchases.inc.php';
$purchase_obj = new purchases();
if (isset($_GET['id'])) {
    $detailPurchaseArray = $purchase_obj->calpurchase_list_byid($_GET['id']);
} else {
    header('Location: index.php');
}

?>
<div class="container " >

    <div class="row content">

        <a  href="index.php"  class="button button-purple mt-12 pull-right">View List Purchases</a>

        <h3>View Customer Info</h3>


        <hr/>

    <?php 
       echo '<table border="0" cellspacing="2" cellpadding="2">';
        foreach ($detailPurchaseArray as $key => $value) {
            $keyname = $key;
            $keyvalue = $value;

            
            #echo "$keyname = $keyvalue <br>";
        
            echo "<tr><td><label >$keyname</label></td><td> : </td><td style='padding-left:10px'>$value</td></tr> ";
        }
        echo "</table>";
       
    ?>

  



        <a href="<?php echo 'update-purchases.php?id=' . $detailPurchaseArray["id"] ?>" class="button button-blue">Edit</a>





    </div>

</div>
<hr/>
<?php
include 'footer.php';
?>

