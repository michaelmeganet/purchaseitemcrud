<?php
include_once "dbh.inc.php";
include_once "variables.inc.php";

Class Purchases{

	protected $postdata;
	function __construct(){
		$this->postdata = [];
	}

	function purchases_list(){
		$qr = "SELECT * FROM purchase ORDER BY id ASC";
		$objSQL = new SQL($qr);
		$result = $objSQL->getResultRowArray();
		return $result;
	}

	function purchases_list_type2($type){
		$qrType = '"%'.$type.'%"';
		$qr = "SELECT * FROM purchase 
			   LEFT JOIN material
			   ON purchase.Item_Code = material.material
			   WHERE purchase.Item_Code LIKE {$qrType} 
			   AND 
			   purchase.Description_2 NOT LIKE '%Dia%'
			   AND
			   purchase.Description_2 NOT LIKE '%od%'";
		echo $qr."<br>";

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

		echo $qr."<br>";

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
		echo $qr."<br>";
		$objSQL = new SQL($qr);
		$result = $objSQL->getRowCount();
		return $result;
	}	


}

?>