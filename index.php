<?php
include "header.php";
include_once "class/purchases.inc.php";
include_once "class/materials.inc.php";

#create new object
$purchases = new Purchases();
$material = new material();
$matCategory = (isset($_GET['cat'])) ? $_GET['cat'] : "Plate";
$ShapeCode1 = $material->getShapeCode($matCategory);
$ShapeCode2 = $ShapeCode1['0'];
$matShapeCode = (isset($_GET['shapecode'])) ? $_GET['shapecode'] : $ShapeCode2['Shape_Code'];
switch ($matCategory) {
	case 'Plate':
		$btnPlate = 'Sorted by Plate';
		$btnShaft = '<a href="index.php?cat=Rod" class="btn btn-info">Sort by Shaft</a>';
		$btnIrregular = '<a href="index.php?cat=Irregular" class="btn btn-info">Sort by Irregular</a>';
		$tableHeader = "Thickness";
		break;
	case 'Rod':
		$btnPlate = '<a href="index.php?cat=Plate" class="btn btn-info">Sort by Plate</a>';
		$btnShaft = 'Sorted by Shaft';
		$btnIrregular = '<a href="index.php?cat=Irregular" class="btn btn-info">Sort by Irregular</a>';
		$tableHeader = "Diameter";
		break;
	case 'Irregular':
		$btnPlate = '<a href="index.php?cat=Plate" class="btn btn-info">Sort by Plate</a>';
		$btnShaft = '<a href="index.php?cat=Rod" class="btn btn-info">Sort by Shaft</a>';
		$btnIrregular = 'Sorted by Irregular';
		$tableHeader = "Thickness";		
		break;
}


include_once "calPurchase.php"; //--> check and input all type into database
$url = "index.php?cat={$matCategory}"; //--> get url

echo $matCategory."<br>";
echo $matShapeCode."<br>";

//# insert pagination code here #//
include_once "pagination.php";
#$purchases_list = $purchases->calpurchase_list_type2($matType);
#$purchases_numrows = $purchases->calpurchase_list_numrows_type2($matType);
$purchases_list = $purchases->calpurchase_list_limit_type3($matCategory,$matShapeCode,$startLimit,$rowPage);
$purchases_numrows = $purchases->calpurchase_list_numrows_type3($matCategory,$matShapeCode);


#echo $purchases_numrows."<br>";
?>
<div class="container">
	<div class="row content">
		<h3>Purchases List</h3>
		<div>
			<?php
			echo $btnPlate."    ".$btnShaft."    ".$btnIrregular;
			  
			?>
			<form method="GET" action="<?php echo $url; ?>">
				<input type="hidden" name="cat" value="<?php echo $matCategory;?>">
				<select name='shapecode'>
					<?php
						foreach ($ShapeCode1 as $value) {
							$SC = $value['Shape_Code'];
							if ($SC == $matShapeCode) {
								echo "<option value{$SC}' selected>{$SC}</option>";
							}else{
								echo "<option value{$SC}'>{$SC}</option>";
							}
							
						}
					?>
				</select><input type="submit" value="Submit">
			</form>
		</div>
			<div>
				<?php echo $textPagination; ?>
			</div><br>
		<table class="table">
			<thead>
				<th>Doc. No</th>
				<th>Company Name</th>
				<th>Item Code</th>
				<th>Description</th>
				<th><?php echo $tableHeader; ?></th>
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