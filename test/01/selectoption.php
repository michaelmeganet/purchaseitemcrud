<?php
include_once '../../class/dbh.inc.php';
include_once '../../class/variables.inc.php';
?>
<form method = "POST">
    <select name = "categoryMaterial">
        <option value = 'rod'>rod</option>
        <option value = 'plate'>plate</option>
        <option value = 'irregular'>irregular</option>
    </select>

    <?php
    echo "<br>";

    $sql = 'SELECT DISTINCT Shape_code FROM purchase ';
    $objShapeObj = new SQL($sql);
    $result = $objShapeObj->getResultRowArray();

    ?>
    <select name = 'shapecodeMaterial'>
        <?php
        foreach ($result as $array) {
            foreach ($array as $key => $value){
                echo "<option value = $value >$value</option>" ; 
            }
             
         }
            
        ?>
       
<!--         <option value = ''>NULL</option>
        <option value = 'O'>O</option>
        <option value = 'PLATE'>PLATE</option>
        <option value = 'PLATEC'>PLATEC</option>
        <option value = 'L'>L</option>
        <option value = 'HEX'>HEX</option>
        <option value = 'HS'>HS</option>
        <option value = 'SS'>SS</option>
        <option value = 'HP'>HP</option> -->

    </select>

</select>
<input type = 'submit' name = 'getShapeCode' value = 'submitAll'></form>
<form method = "POST"><input type = 'submit' name = 'reset' value = 'reset'>
</form>

