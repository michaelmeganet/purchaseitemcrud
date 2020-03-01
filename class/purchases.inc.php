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