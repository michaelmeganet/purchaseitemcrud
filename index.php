<?php
include "header.php";
include_once "class/purchases.inc.php";
include_once "class/variables.inc.php";

#create new object
$purchases = new Purchases();
$matType = (isset($_GET['type'])) ? $_GET['type'] : "Plate";
if ($matType == "Plate") {
	$btnPlate = 'Sorted by Plate';
	$btnShaft = '<a href="index.php?type=Shaft" class="btn btn-info">Sort by Shaft</a>';
}else{
	$btnPlate = '<a href="index.php?type=Plate" class="btn btn-info">Sort by Plate</a>';
	$btnShaft = 'Sorted by Shaft';
}
/*if (isset($_GET['type'])) {
	$type = $_GET['type'];
	$btnPlate = '<a href="index.php" class="btn btn-info">Sort by Plate</a>';
	$btnShaft = 'Sorted by Shaft';
}else{
	$type = NULL;
	$btnPlate = 'Sorted by Plate';
	$btnShaft = '<a href="index.php?type=Dia" class="btn btn-info">Sort by Shaft</a>';
}*/
include_once "calPurchase.php"; //--> check and input all type into database
$purchases_list = $purchases->calpurchase_list_type2($matType);
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
		<table class="table">
			<thead>
				<th>Doc. No</th>
				<th>Company Name</th>
				<th>Item Code</th>
				<th>Description</th>
				<th>Thickness</th>
				<th>Width</th>
				<th>Length</th>
				<th>Action</th>
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
								  <td><a href='delete-purchases.php?id={$row['id']}' class='btn btn-danger'>Delete</a></td> 
								  <td><a href='update-purchases.php?id={$row['id']}' class='btn btn-info'>Edit</a></td>
								  <td><a href='details-purchases.php?id={$row['id']}' class='btn btn-primary'>Details</a></td> 
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