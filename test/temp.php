<?php
include_once "class/materials.inc.php";
session_start();
/*
1. Fetch list of rows based on matCategory and matCode
*/
$materialObj = new Material();
$listCategory = $materialObj->getCategory();


if (isset($_POST['getCategory'])) {
	$_SESSION['categoryMaterial'] = $_POST['categoryMaterial'];
}
if (isset($_POST['getShapeCode'])) {
	$_SESSION['shapecodeMaterial'] = $_POST['shapecodeMaterial'];
		echo "Category = {$_SESSION['categoryMaterial']}<br>Shape Code = {$_SESSION['shapecodeMaterial']}";
}
if (isset($_POST['reset'])){
	unset($_SESSION);
}

?>
<form method="POST">
<select name="categoryMaterial">
		<?php
		foreach ($listCategory as $rowCategory) {
			if (isset($_SESSION['categoryMaterial'])) {
				if ($rowCategory['category']==$_SESSION['categoryMaterial']) {
				$selected = "selected";
				}else{
					$selected = "";
				}
			}
			echo "<option $selected value='{$rowCategory['category']}'>{$rowCategory['category']}</option>";
		}
		?>
</select> 
<?php 
if (isset($_SESSION['categoryMaterial'])) {
	$listShapeCode = $materialObj->getShapeCode($_SESSION['categoryMaterial']);
	echo "<select name ='shapecodeMaterial'>";
		foreach ($listShapeCode as $rowShapeCode) {
			$Shape_Code = $rowShapeCode['Shape_Code'];
			echo "<option value ='{$Shape_Code}'>{$Shape_Code}</option>";
		}
	echo "</select>";		
	echo "<input type='submit' name='getShapeCode' value='submitAll'>";
}else{
	echo '<input type="submit" name="getCategory" value="Submit">';
}
?>
</form>
<form method="POST"><input type='submit' name='reset' value='reset'></form>