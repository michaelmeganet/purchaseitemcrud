<?php
include_once "dbh.inc.php";
include_once "variables.inc.php";

Class Purchases{

	protected $postdata;
	function __construct(){
		$this->postdata = [];
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
			echo "Insert Successful<br>";
		}else{
			echo "Insert Failed.<br>";
		}

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
	function calpurchase_list_numrows_type2($type){
		$qrType = '"%'.$type.'%"';
		$qr = "SELECT COUNT(*) FROM calpurchase WHERE Item_Code LIKE {$qrType} ORDER BY id";
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
		echo $qr."<br>";
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