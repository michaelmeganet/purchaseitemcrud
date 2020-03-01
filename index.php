<?php
include "header.php";
include_once "class/purchases.inc.php";

#create new object
$purchases = new Purchases();
if (isset($_GET['type'])) {
	$type = $_GET['type'];
	$btnPlate = '<a href="index.php" class="btn btn-info">Sort by Plate</a>';
	$btnShaft = 'Sorted by Shaft';
}else{
	$type = NULL;
	$btnPlate = 'Sorted by Plate';
	$btnShaft = '<a href="index.php?type=Dia" class="btn btn-info">Sort by Shaft</a>';
}
$purchases_list = $purchases->purchases_list_type($type);
$purchases_numrows = $purchases->purchases_list_numrows_type($type);
echo $purchases_numrows."<br>";
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
				<th>Action</th>
			</thead>
			<tbody>
				<?php
				if ($purchases_numrows>0) {
					$rows = $purchases_list;
					foreach ($rows as $row) {
						#line below is still in progress#
					echo "<tr>
						  <td style='width: 8%'>{$row['Doc_No']}</td>
						  <td style='width: 40%'>{$row['Company_Name']}</td>
						  <td style='width: 12%'>{$row['Item_Code']}</td>
						  <td>{$row['Description_2']}</td>
						  <td><a href='delete-purchases.php?id={$row['id']}' class=''>Delete</a></td> 
						  <td><a href='delete-purchases.php?id={$row['id']}' class=''>Delete</a></td>
						  <td><a href='delete-purchases.php?id={$row['id']}' class=''>Delete</a></td> 
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