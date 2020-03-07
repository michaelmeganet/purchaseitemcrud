<?php

include_once "dbh.inc.php";
include_once "variables.inc.php";

Class Purchases {

    protected $postdata;

    function __construct() {
        $this->postdata = [];
    }

    function calpurchase_clone($purchase_array = array()) {
        unset($purchase_array['id']);

        $arrayKeys = array_keys($purchase_array);    //--> fetches the keys of array
        $lastArrayKey = array_pop($arrayKeys); //--> fetches the last key of the compiled keys of arra

        $qr2 = "INSERT INTO calpurchase SET ";
        foreach ($purchase_array as $key => $value) {
            ${$key} = trim($value);
            $columnHeader = $key; // creates new variable based on $key values
            #echo $columnHeader." = ".$$columnHeader."<br>";
            $qr2 .= $columnHeader . "='{$$columnHeader}'";     //--> adds the key as parameter

            if ($columnHeader != $lastArrayKey) {
                $qr2 .= ", ";      //--> if not final key, writes comma to separate between indexes
            } else {
                #do nothing         //--> if yes, do nothing
            }
        }
        echo $qr2;
        $objSQL = new SQL($qr2);
        if ($objSQL->InsertData() == "insert ok!") {
            $result = TRUE;
        } else {
            $result = FALSE;
        }
        return $result;
    }

    function calpurchase_create($category, $shapecode, $purchase_array = array(), $dimension_array = array()) {
        //checks if the array contains "dia"
        if (preg_grep('/(dia)/', $dimension_array)) {
            $diaKey = key(preg_grep('/(dia)/', $dimension_array)); //find out what's the index of 'dia'
            $diaText = $dimension_array[$diaKey];     //take the value of the index
            //shifts the diameter that's found into the first index
            unset($dimension_array[$diaKey]);
            array_unshift($dimension_array, $diaText);
            $diaCheck = TRUE;
        } elseif ($category == 'Rod') {
            $diaCheck = TRUE;
        } else {
            $diaCheck = FALSE;
        }
        //check if the array contains "hex"
        if (preg_grep('/(hex)/', $dimension_array)) {
            $hexKey = key(preg_grep('/(hex)/', $dimension_array));
            unset($dimension_array[$hexKey]);
            $dimension_array = array_values($dimension_array);
            $hexCheck = TRUE;
        } else {
            $hexCheck = FALSE;
        }
        //check if the array contains
        #	echo "purchase array = ";
        #	print_r($purchase_array);
        #	echo "<br>";
        #	echo "dimension array = ";
        #	print_r($dimension_array);
        #	echo "<br>";
        //delete all texts from dimension array, and return numbers only
        $replace_array = array("hex", "dia,", "dia;", "dia:", "dia.", "dia", "mm"); //--> to lists what need to be replaced.
        $newDimensionArray = str_replace($replace_array, "", $dimension_array); //--> replaces the text
        #	print_r($newDimensionArray);
        #	echo "<br>";
        if ($diaCheck) {
            $thickness = floatval($newDimensionArray['0']); //diameter
            $length = floatval($newDimensionArray['1']); //length
            $qrext = "";
        } else {
            $thickness = floatval($newDimensionArray['0']); //thickness
            $width = floatval($newDimensionArray['1']); //width
            $length = floatval($newDimensionArray['2']); //length
            $qrext = ", width = {$width}";
        }

        //begin attaching each array to coresponding variables
        foreach ($purchase_array as $Pkey => $Pvalue) {
            $var = "var_" . $Pkey;
            ${$var} = $Pvalue;
        }

        //getDensity
        if ($var_is_shaft == 'no') {
            $density = floatval($var_plate);
        } else {
            $density = floatval($var_shaft);
        }


        switch ($category) {
            case 'Plate':
                switch ($shapecode) {
                    case 'PLATE':
                        if ($diaCheck) {
                            $radius = $thickness / 2;
                            $area = pi() * ($radius ^ 2);
                            $volume = $area * $length;
                            echo "length = $length, thick/dia = $thickness, denisty = $density <br>";
                        } else {
                            $area = $width * $length;
                            $volume = $area * $thickness;
                            echo "width = $width, length = $length, thick/dia = $thickness, denisty = $density <br>";
                        }

                        break;

                    case 'PLATEC':
                        //not yet implemented
                        $errormsg = "not yet implemented";
                        break;
                }
                break;

            case 'Rod':
                switch ($shapecode) {
                    case 'O':
                        if ($diaCheck) {
                            if ($hexCheck) {
                                //not yet implemented
                                $errormsg = "not yet implemented";
                            } else {
                                $radius = $thickness / 2;
                                $area = pi() * ($radius ^ 2);
                                $volume = $area * $length;
                            }
                            echo "length = $length, thick/dia = $thickness, denisty = $density <br>";
                        } else {

                        }
                        break;
                }
                break;

            case 'Irregular':
                switch ($shapecode) {
                    case 'L':
                        //not yet implemented
                        $errormsg = "not yet implemented";
                        break;
                    case 'HEX':
                        //not yet implemented
                        $errormsg = "not yet implemented";
                        break;
                    case 'HS':
                        //not yet implemented
                        $errormsg = "not yet implemented";
                        break;
                    case 'SS':
                        //not yet implemented
                        $errormsg = "not yet implemented";
                        break;
                    case 'HP':
                        //not yet implemented
                        $errormsg = "not yet implemented";
                        break;
                    case 'PLATE':
                        //not yet implemented
                        $errormsg = "not yet implemented";
                        break;
                }
                break;
        }

        $weight = $volume * $density;

        if (isset($errormsg)) { //==> this is for debugging, since this is not yet completed
            echo $errormsg . "<br>";
        } else {
            $qr = "INSERT INTO calpurchase
				   SET
				   pid = {$var_id},
				   Date = '{$var_Date}',
				   Doc_No = '{$var_Doc_No}',
				   Company_Name = '{$var_Company_Name}',
				   Item_Code = '{$var_Item_Code}',
				   Description_2 = '{$var_Description_2}',
				   Qty = '{$var_Qty}',
				   thickness = '{$thickness}',
				   length = '{$length}',
				   volume = '{$volume}',
				   weight = '{$weight}',
				   UOM = '{$var_UOM}',
				   Unit_Price = '{$var_Unit_Price}',
				   DISC = '{$var_DISC}',
				   SubTotal = '{$var_SubTotal}',
				   category = '{$var_category}',
				   Shape_Code = '{$var_Shape_Code}'" . $qrext;
            #echo $qr."<br>";
            $objSQL = new SQL($qr);
            $result = $objSQL->InsertData();
            if ($result == 'insert ok!') {
                return true;
            } else {
                return false;
            }
        }
    }

    function calpurchase_update($post_data = array()) {
        $this->getPostData = $post_data;
        /* $dbg = new DEBUG();     //--> creates object for debugging */

        //print_r($post_data);
        #-----------------------------
        # preparation to do looping
        //fetch the id value and deletes it from array
        /**/ $id = $post_data['id'];
        /**/ unset($post_data['id']);

        $arrayKeys = array_keys($post_data);    //--> fetches the keys of array
        $lastArrayKey = array_pop($arrayKeys); //--> fetches the last key of the compiled keys of array
        # end preparation
        #-----------------------------
        #------------------------------------------------------
        # Begin loop process
        $qr2 = "UPDATE calpurchase SET "; //--> creates main body for query
        foreach ($post_data as $key => $value) {

            ${$key} = trim($value);
            $columnHeader = $key; // creates new variable based on $ key values
            #echo $columnHeader." = ".$$columnHeader."<br>";

            /* $dbg->review($columnHeader." = ".$$columnHeader."<br>"); */ //this is for debugging, not yet implemented

            $qr2 .= $columnHeader . "=:{$columnHeader}";     //--> adds the key as parameter
            if ($columnHeader != $lastArrayKey) {
                $qr2 .= ", ";      //--> if not final key, writes comma to separate between indexes
            } else {
                #do nothing         //--> if yes, do nothing
            }
        }
        # end loop
        #------------------------------------------------------
        $qr2 .= " WHERE id = $id";
        #echo "<br><br><br>" . $qr2 . "<br>";
        /* $dbg->review($qr2); */
        $objSQL = new SQLBINDPARAM($qr2, $post_data);
        $result = $objSQL->UpdateData2();

        if ($result == 'Update ok!') {

            $_SESSION['message'] = "Successfully Created Student Info";
            $_SESSION['success'] = true;
            #echo "Successfully Created Customer Info<br>";
            //header('URL=./index.php');          //redirects
            /* echo "<pre>"; print_r($dbg->showToConsole()); echo"</pre>"; */
        } else {
            $error = "Fail to Created Customer Info <br>";
            $_SESSION['message'] = "Please check this \$sql -> $qr2";
            $_SESSION['success'] = false;
            $url = "customercreatefail.php?err=$error";
            //    redirect($url);
            //header('Location: customercreatefail.php?err=$error');
        }
    }

    function calpurchase_delete($id) {
        $qr = "DELETE FROM calpurchase WHERE id = {$id}";

        $objSQL = new SQL($qr);
        if ($objSQL->getDelete() == "delete ok") {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    function calpurchase_list_byid($id) {
        $qr = "SELECT * FROM calpurchase WHERE id = {$id}";
        $objSQL = new SQL($qr);
        $result = $objSQL->getResultOneRowArray();
        return $result;
    }

    function calpurchase_list() {
        $qr = "SELECT * FROM calpurchase";
        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function calpurchase_list_one($id) {
        $qr = "SELECT * FROM calpurchase WHERE pid = {$id}";

        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function calpurchase_list_one_row($id) {
        $qr = "SELECT COUNT(*) FROM calpurchase WHERE pid = {$id}";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

    function calpurchase_list_type2($type) {
        if ($type == "Plate") {
            $qrWhere = " AND calpurchase.Description_2 NOT LIKE '%Dia%' AND calpurchase.Description_2 NOT LIKE '%od%'";
        } elseif ($type == "Shaft") {
            $qrWhere = " AND calpurchase.Description_2 LIKE '%Dia%'";
        }

        $qrType = '"%' . $type . '%"';
        $qr = "SELECT * FROM calpurchase
			   LEFT JOIN material
			   ON calpurchase.Item_Code = material.material
			   WHERE calpurchase.Item_Code LIKE {$qrType}" . $qrWhere;

        #echo $qr."<br>";

        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function calpurchase_list_type3($category, $shapecode) {
        $qrType = '"%' . $type . '%"';
        $qr = "SELECT * FROM calpurchase
			   WHERE category = {$category}
			   AND Shape_Code = {$shapecode}";

        #echo $qr."<br>";

        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function calpurchase_list_limit_type3($category, $shapecode, $offset, $limit) {
        $qr = "SELECT * FROM calpurchase "
                . "WHERE category = '{$category}' "
                . "AND Shape_Code = '{$shapecode}' LIMIT $offset,$limit";

        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function calpurchase_list_numrows_type3($category, $shapecode) {
        $qr = "SELECT COUNT(*) FROM calpurchase "
                . "WHERE category = '{$category}' "
                . "AND Shape_Code = '{$shapecode}'";
        #echo $qr."<br>";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

    function calpurchase_list_numrows_limit_type3($category, $shapecode, $offset, $limit) {
        $qr = "SELECT COUNT(*) FROM calpurchase
			   WHERE category = '{$category}'
			   AND Shape_Code = '{$shapecode}'
			   ORDER BY id LIMIT $offset,$limit";
        #echo $qr."<br>";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

    function purchases_list_type3($category, $shape_code) {
        $qr = "SELECT purchase.*, material.shaft AS is_shaft, "
                . "material_density.plate , material_density.shaft FROM purchase "
                . "LEFT JOIN material "
                . "ON purchase.Item_Code = material.material_acc "
                . "LEFT JOIN material_density "
                . "on material.materialtype = material_density.materialtype "
                . "WHERE category = '{$category}' "
                . "AND Shape_Code = '{$shape_code}'";
        #echo $qr."<br>";

        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function purchases_list_numrows_type3($category, $shape_code) {
        $qr = "SELECT COUNT(*) FROM purchase "
                . "WHERE category = '{$category}'"
                . "AND Shape_Code = '{$shape_code}'";
        #echo $qr."<br>";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

    function calpurchase_list_limit_type2(
    $type, $offset, $limit) {
        if ($type == "  Plate") {
            $qrWhere = " AND calpurchase.Description_2 NOT LIKE '%Dia%' AND calpurchase.Description_2 NOT LIKE '%od%'";
        } elseif ($type == "Shaft") {
            $qrWhere = " AND calpurchase.Description_2 LIKE '%Dia%'";
        }

        $qrType = '"%' . $type . ' % "';
        $qr = "SELECT * FROM calpurchase
                LEFT JOIN material
                ON calpurchase.Item_Code = material.material
                WHERE calpurchase.Item_Code LIKE {$qrType}" . $qrWhere . " LIMIT $offset, $limit";

        #echo $qr."<br>";

        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function calpurchase_list_numrows_type2(
    $type) {
        $qrType = '"%' . $type . '%"';
        $qr = "SELECT COUNT(*) FROM calpurchase WHERE Item_Code LIKE {$qrType} ORDER BY id";
        #echo $qr."<br>";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

    function calpurchase_list_numrows_limit_type2(
    $type, $offset, $limit) {
        $qrType = '"%' . $type . '%"';
        $qr = "  SELECT COUNT(*) FROM calpurchase WHERE Item_Code LIKE {$qrType} ORDER BY id LIMIT $offset, $limit";
        #echo $qr."<br>";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

    function purchases_list() {


        $qr = "SELECT * FROM purchase ORDER BY id ASC";
        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function purchases_list_type2(
    $type) {
        if ($type == "Plate") {
            #SELECT * FROM purchase LEFT JOIN material ON purchase.Item_Code = material.material
            #WHERE purchase.Item_Code LIKE
            $qrWhere = " WHERE category";
        } elseif ($type == "Shaft") {
            $qrWhere = " AND purchase.Description_2 LIKE '%Dia%'";
        }

        $qrType = '"%' . $type . ' % "';
        $qr = "SELECT * FROM purchase" . $qrWhere;

        #echo $qr."<br>";

        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function purchases_list_type(
    $type) {

        if (is_null($type)) {
            $qrType = "IS NULL";
        } else {
            $qrType = " = '" . $type . "'";
        }
        $qr = "SELECT *, material.shaft, material.shaftindicator FROM purchase LEFT JOIN material ON purchase.Item_Code = material.material WHERE material.shaftindicator {$qrType} ORDER BY id ASC ";

        #echo $qr."<br>";

        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function purchases_list_limit(
    $offset, $limit) {
        $qr = " SELECT * FROM purchase ORDER BY id ASC LIMIT $offset, $limit";
        $objSQL = new SQL($qr);
        $result = $objSQL->getResultRowArray();
        return $result;
    }

    function purchases_list_numrows() {


        $qr = "SELECT COUNT(*) FROM purchase";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

    function purchases_list_numrows_type2(
    $type) {
        $qrType = '"%' . $type . '%"';
        $qr = "SELECT COUNT(*) FROM purchase WHERE Item_Code LIKE {$qrType} ORDER BY id";
        #echo $qr."<br>";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

    function purchases_list_numrows_type(
    $type) {
        if (is_null($type)) {
            $qrType = "IS NULL";
        } else {
            $qrType = " = '" . $type . "'";
        }
        $qr = "SELECT COUNT(*) FROM purchase LEFT JOIN material ON purchase.Item_Code = material.material WHERE material.shaftindicator {$qrType}";
        #echo $qr."<br>";
        $objSQL = new SQL($qr);
        $result = $objSQL->getRowCount();
        return $result;
    }

}

?>