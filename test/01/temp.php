<?php
include_once "materials.inc.php";
/*
  1. Fetch list of rows based on matCategory and matCode
 */
$materialObj = new Material();
$listCategory = $materialObj->getCategory();

if (isset($_POST['getCategory'])) {
    $finCategory = preg_split("/(-)/", $_POST['categoryMaterial']);
    echo "Category : " . $finCategory['0'] . "<br>";
    echo "Shape Code : " . $finCategory['1'] . "<br>";
}
?>
<form method="POST">
    <select name="categoryMaterial">
<?php
foreach ($listCategory as $rowCategory) {
    $listCode = $materialObj->getShapeCode($rowCategory['category']);
    foreach ($listCode as $rowCode) {
        $text = $rowCategory['category'] . '-' . $rowCode['Shape_Code'];
        echo "<option value='{$text}'>{$text}</option>";
    }
}
?>
        <option val></option>
    </select> <input type="submit" name="getCategory" value="Submit">
    <form>