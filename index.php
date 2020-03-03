<?php
include "header.php";
include_once "class/purchases.inc.php";

#create new object
$purchases = new Purchases();
$matType = (isset($_GET['type'])) ? $_GET['type'] : "Plate";
if ($matType == "Plate") {
	$btnPlate = 'Sorted by Plate';
	$btnShaft = '<a href="index.php?type=Shaft" class="btn btn-info">Sort by Shaft</a>';
	include_once "calPurchase.php"; //--> check and input all type into database
}elseif ($matType =="Shaft") {
	$btnPlate = '<a href="index.php?type=Plate" class="btn btn-info">Sort by Plate</a>';
	$btnShaft = 'Sorted by Shaft';
	include_once "calPurchase.php"; //--> check and input all type into database
}else{
	
}
$url = "index.php?type={$matType}"; //--> get url
/*if (isset($_GET['type'])) {
	$type = $_GET['type'];
	$btnPlate = '<a href="index.php" class="btn btn-info">Sort by Plate</a>';
	$btnShaft = 'Sorted by Shaft';
}else{
	$type = NULL;
	$btnPlate = 'Sorted by Plate';
	$btnShaft = '<a href="index.php?type=Dia" class="btn btn-info">Sort by Shaft</a>';
}*/


//# insert pagination code here #//
include_once "pagination.php";
#$purchases_list = $purchases->calpurchase_list_type2($matType);
#$purchases_numrows = $purchases->calpurchase_list_numrows_type2($matType);
$purchases_list = $purchases->calpurchase_list_limit_type2($matType,$startLimit,$rowPage);
$purchases_numrows = $purchases->calpurchase_list_numrows_type2($matType);


#echo $purchases_numrows."<br>";
?>
<div class="container">
	<div class="row content">
		<h3>Purchases List</h3>
		<div>
			<?php
			echo $btnPlate."    ".$btnShaft;
			  
			?></div>
			<div>
				<?php echo $textPagination; ?>
			</div><br>
		<table class="table">
			<thead>
				<th>Doc. No</th>
				<th>Company Name</th>
				<th>Item Code</th>
				<th>Description</th>
				<th>Thickness</th>
				<th>Width</th>
				<th>Length</th>
				<th>Volume</th>
				<th>Weight</th>
				<th style="text-align: center">Action</th>
			</thead>
			<tbody>
				<?php
				if ($purchases_numrows>0) {
					$rows = $purchases_list;
					foreach ($rows as $row) {
						#line below is still in progress#
						#print_r($rows);		
						echo "<tr>
								  <td style='width: 8%'>{$row['Doc_No']}</td>
								  <td style='width: 37%'>{$row['Company_Name']}</td>
								  <td style='width: 12%'>{$row['Item_Code']}</td>
								  <td>{$row['Description_2']}</td>
								  <td>{$row['thickness']}</td>
								  <td>{$row['width']}</td>
								  <td>{$row['length']}</td>
								  <td>{$row['volume']}</td>
								  <td>{$row['weight']}</td>
								  <td style='width: 25%; text-align:center'>
								  <a href=\"javascript:delValidate('delete-purchases.php?id={$row['id']}','#')\" class='btn btn-danger' id='delPurchase'>Delete</a>
								  <a href='update-purchases.php?id={$row['id']}' class='btn btn-info'>Edit</a>
								  <a href='details-purchases.php?id={$row['id']}' class='btn btn-primary'>Details</a>
								  <a href=\"javascript:cloneValidate('clone-purchases.php?id={$row['id']}','#')\" class='btn btn-success'>Clone Record</a></td> 
								  </tr>
								";
					}
				}
				?>
				
			</tbody>
		</table>
	</div>
</div>
<?php include "footer.php"; ?>