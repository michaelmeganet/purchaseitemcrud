<?php
include_once "class/dbh.inc.php";
include_once "variables.inc.php";
/**
 * 
 */
class Material 
{
	
	function __construct()
	{
		# code...
	}

	function getCategory(){
		$qr = "SELECT DISTINCT category FROM purchase";

		$sqlObj = new SQL($qr);
		$result = $sqlObj->getResultRowArray();

		return $result;

	}

	function getShapeCode($category){
		$qr = "SELECT DISTINCT Shape_Code FROM purchase WHERE category ='{$category}'";

		$sqlObj = new SQL($qr);
		$result = $sqlObj->getResultRowArray();

		return $result;
	}
}

?>