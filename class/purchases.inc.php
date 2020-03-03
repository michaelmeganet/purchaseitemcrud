<?php
include_once "dbh.inc.php";
include_once "variables.inc.php";

Class Purchases{

	protected $postdata;
	function __construct(){
		$this->postdata = [];
	}
	function calpurchase_clone($purchase_array=array()){
		unset($purchase_array['id']);

		$arrayKeys = array_keys($purchase_array);    //--> fetches the keys of array
        $lastArrayKey = array_pop($arrayKeys); //--> fetches the last key of the compiled keys of arra
		
		$qr2 = "INSERT INTO calpurchase SET ";
		foreach ($purchase_array as $key => $value) {
            ${$key} = trim($value);
            $columnHeader = $key; // creates new variable based on $key values
            #echo $columnHeader." = ".$$columnHeader."<br>";
            $qr2 .= $columnHeader."='{$$columnHeader}'";     //--> adds the key as parameter

            if ($columnHeader != $lastArrayKey) { 
                $qr2 .= ", ";      //--> if not final key, writes comma to separate between indexes
            }else{
                #do nothing         //--> if yes, do nothing
            }
        }
        echo $qr2;
        $objSQL = new SQL($qr2);
        if ($objSQL->InsertData() == "insert ok!"){
        	$result = TRUE;
        }else{
        	$result = FALSE;
        }
        return $result;


	}
	function calpurchase_create($matType,$purchase_array=array(),$dimension_array=array()){
		$thickness = $dimension_array['0'];
		$length = $dimension_array['1'];
		//begin attaching each array to coresponding variables
		foreach ($purchase_array as $key => $value) {
			${$key} = $value;
		}
		/*
		Data needed :
		pid
		Date
		Doc_No
		Company_Name
		Item_Code
		Description_2
		Qty
		thickness -> from dimensions
		width     -> from dimensions
		length    -> from dimensions
		density   -> Plate = 0.00000785000 | Shaft = 0.00000625000
		volume	  -> thick * width * length
		weight    -> volume * density
		UOM
		Unit_Price
		DISC
		SubTotal
		*/
		//get the correct density and sizes
		if ($matType == "Plate") {
			$width = $dimension_array['2'];
			$density = number_format(0.00000785,8,'.','');
			#volume
			$volume = number_format($thickness * $width * $length,2,'.','');
		}elseif ($matType == "Shaft") {

			$width = "";
			$density = number_format(0.00000625,8,'.','');
			#volume
			$volume = number_format($thickness * $length * $length,2,'.','');
		}

		#weight
		$weight = number_format($volume * $density,2,'.','');

		$qr = "INSERT INTO calpurchase
			   SET
			   pid = {$id},
			   Date = '{$Date}',
			   Doc_No = '{$Doc_No}',
			   Company_Name = '{$Company_Name}',
			   Item_Code = '{$Item_Code}',
			   Description_2 = '{$Description_2}',
			   Qty = '{$Qty}',
			   thickness = '{$thickness}',
			   width = '{$width}',
			   length = '{$length}',
			   density = '{$density}',
			   volume = '{$volume}',
			   weight = '{$weight}',
			   UOM = '{$UOM}',
			   Unit_Price = '{$Unit_Price}',
			   DISC = '{$DISC}',
			   SubTotal = '{$SubTotal}'";
		#echo $qr."<br>";
		$objSQL = new SQL($qr);
		$result = $objSQL->InsertData();
		if ($result == 'insert ok!') {
			return true;
		}else{
			return false;
		}

	}

	function calpurchase_update($post_data=array()){
		$this->getPostData = $post_data;
        /*$dbg = new DEBUG();     //--> creates object for debugging*/

        //print_r($post_data);
        #-----------------------------
        # preparation to do looping
        //fetch the id value and deletes it from array
        /**/   $id = $post_data['id'];
        /**/   unset($post_data['id']);

        $arrayKeys = array_keys($post_data);    //--> fetches the keys of array
        $lastArrayKey = array_pop($arrayKeys); //--> fetches the last key of the compiled keys of array
        # end preparation
        #-----------------------------
        #------------------------------------------------------
        # Begin loop process
        $qr2 = "UPDATE calpurchase SET "; //--> creates main body for query
        foreach ($post_data as $key => $value) {

            ${$key} = trim($value);
            $columnHeader = $key; // creates new variable based on $key values
            #echo $columnHeader." = ".$$columnHeader."<br>";

        /*$dbg->review($columnHeader." = ".$$columnHeader."<br>");*/ //this is for debugging, not yet implemented

            $qr2 .= $columnHeader."=:{$columnHeader}";     //--> adds the key as parameter
            if ($columnHeader != $lastArrayKey) { 
                $qr2 .= ", ";      //--> if not final key, writes comma to separate between indexes
            }else{
                #do nothing         //--> if yes, do nothing
            }
        }
        # end loop
        #------------------------------------------------------
        $qr2 .= " WHERE id = $id";
        #echo "<br><br><br>" . $qr2 . "<br>";
        /*$dbg->review($qr2);*/
        $objSQL = new SQLBINDPARAM($qr2,$post_data);
        $result = $objSQL->UpdateData2();

        if ($result == 'Update ok!') {

            $_SESSION['message'] = "Successfully Created Student Info";
            $_SESSION['success'] = true;
            #echo "Successfully Created Customer Info<br>";

            //header('URL=./index.php');          //redirects
            /*echo "<pre>"; print_r($dbg->showToConsole()); echo"</pre>";*/
        } else {
            $error = "Fail to Created Customer Info <br>";
            $_SESSION['message'] = "Please check this \$sql -> $qr2";
            $_SESSION['success'] = false;
            $url = "customercreatefail.php?err=$error";
            //    redirect($url);
            //header('Location: customercreatefail.php?err=$error');
        }
    }
    function calpurchase_delete($id){
    	$qr = "DELETE FROM calpurchase WHERE id = {$id}";

    	$objSQL = new SQL($qr);
    	if ($objSQL->getDelete()=="delete ok") {
    		$result = true;
    	}else{
    		$result = false;
    	}
    	return $result;
    }
	function calpurchase_list_byid($id){
		$qr = "SELECT * FROM calpurchase WHERE id = {$id}";
		$objSQL = new SQL($qr);
		$result = $objSQL->getResultOneRowArray();
		return $result;
	}
	function calpurchase_list(){
		$qr = "SELECT * FROM calpurchase"; 
		$objSQL = new SQL($qr);
		$result = $objSQL->getResultRowArray();
		return $result;
	}

	function calpurchase_list_one($id){
		$qr = "SELECT * FROM calpurchase WHERE pid = {$id}";

		$objSQL = new SQL($qr);
		$result = $objSQL->getResultRowArray();
		return $result;
	}

	function calpurchase_list_one_row($id){
		$qr = "SELECT COUNT(*) FROM calpurchase WHERE pid = {$id}";
		$objSQL = new SQL($qr);
		$result = $objSQL->getRowCount();
		return $result;
	}
	function calpurchase_list_type2($type){
		if ($type == "Plate") {
			$qrWhere = " AND calpurchase.Description_2 NOT LIKE '%Dia%' AND calpurchase.Description_2 NOT LIKE '%od%'"; 
		}elseif ($type == "Shaft") {
			$qrWhere = " AND calpurchase.Description_2 LIKE '%Dia%'";
		}

		$qrType = '"%'.$type.'%"';
		$qr = "SELECT * FROM calpurchase 
			   LEFT JOIN material
			   ON calpurchase.Item_Code = material.material
			   WHERE calpurchase.Item_Code LIKE {$qrType}".$qrWhere;

		#echo $qr."<br>";

		$objSQL = new SQL($qr);
		$result = $objSQL->getResultRowArray();
		return $result;
	}
	function calpurchase_list_limit_type2($type,$offset,$limit){
		if ($type == "Plate") {
			$qrWhere = " AND calpurchase.Description_2 NOT LIKE '%Dia%' AND calpurchase.Description_2 NOT LIKE '%od%'"; 
		}elseif ($type == "Shaft") {
			$qrWhere = " AND calpurchase.Description_2 LIKE '%Dia%'";
		}

		$qrType = '"%'.$type.'%"';
		$qr = "SELECT * FROM calpurchase 
			   LEFT JOIN material
			   ON calpurchase.Item_Code = material.material
			   WHERE calpurchase.Item_Code LIKE {$qrType}".$qrWhere." LIMIT $offset,$limit";

		#echo $qr."<br>";

		$objSQL = new SQL($qr);
		$result = $objSQL->getResultRowArray();
		return $result;
	}

	function calpurchase_list_numrows_type2($type){
		$qrType = '"%'.$type.'%"';
		$qr = "SELECT COUNT(*) FROM calpurchase WHERE Item_Code LIKE {$qrType} ORDER BY id";
		#echo $qr."<br>";
		$objSQL = new SQL($qr);
		$result = $objSQL->getRowCount();
		return $result;
	}

	function calpurchase_list_numrows_limit_type2($type,$offset,$limit){
		$qrType = '"%'.$type.'%"';
		$qr = "SELECT COUNT(*) FROM calpurchase WHERE Item_Code LIKE {$qrType} ORDER BY id LIMIT $offset,$limit";
		#echo $qr."<br>";
		$objSQL = new SQL($qr);
		$result = $objSQL->getRowCount();
		return $result;
	}



	function purchases_list(){
		$qr = "SELECT * FROM purchase ORDER BY id ASC";
		$objSQL = new SQL($qr);
		$result = $objSQL->getResultRowArray();
		return $result;
	}

	function purchases_list_type2($type){
		if ($type == "Plate") {
			$qrWhere = " AND purchase.Description_2 NOT LIKE '%Dia%' AND purchase.Description_2 NOT LIKE '%od%'"; 
		}elseif ($type == "Shaft") {
			$qrWhere = " AND purchase.Description_2 LIKE '%Dia%'";
		}

		$qrType = '"%'.$type.'%"';
		$qr = "SELECT * FROM purchase 
			   LEFT JOIN material
			   ON purchase.Item_Code = material.material
			   WHERE purchase.Item_Code LIKE {$qrType}".$qrWhere;

		#echo $qr."<br>";

		$objSQL = new SQL($qr);
		$result = $objSQL->getResultRowArray();
		return $result;
	}
	function purchases_list_type($type){

		if (is_null($type)) {
			$qrType = "IS NULL";
		}else{
			$qrType = "= '".$type."'";
		}
		$qr = "SELECT *, material.shaft, material.shaftindicator FROM purchase LEFT JOIN material ON purchase.Item_Code = material.material WHERE material.shaftindicator {$qrType}  ORDER BY id ASC ";

		#echo $qr."<br>";

		$objSQL = new SQL($qr);
		$result = $objSQL ->getResultRowArray();
		return $result;
	}
	function purchases_list_limit($offset, $limit){
		$qr = "SELECT * FROM purchase ORDER BY id ASC LIMIT $offset, $limit";
		$objSQL = new SQL($qr);
		$result = $objSQL->getResultRowArray();
		return $result;
	}

	function purchases_list_numrows(){
		$qr = "SELECT COUNT(*) FROM purchase";
		$objSQL = new SQL($qr);
		$result = $objSQL->getRowCount();
		return $result;
	}
	function purchases_list_numrows_type2($type){
		$qrType = '"%'.$type.'%"';
		$qr = "SELECT COUNT(*) FROM purchase WHERE Item_Code LIKE {$qrType} ORDER BY id";
		#echo $qr."<br>";
		$objSQL = new SQL($qr);
		$result = $objSQL->getRowCount();
		return $result;
	}
	function purchases_list_numrows_type($type){
		if (is_null($type)) {
			$qrType = "IS NULL";
		}else{
			$qrType = "= '".$type."'";
		}
		$qr = "SELECT COUNT(*) FROM purchase LEFT JOIN material ON purchase.Item_Code = material.material WHERE material.shaftindicator {$qrType}";
		#echo $qr."<br>";
		$objSQL = new SQL($qr);
		$result = $objSQL->getRowCount();
		return $result;
	}	


}

?>