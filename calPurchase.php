<?php
/**************************************
This file must be called from index.php

Variables that carried over from
index.php
----------------------------
$matType = fetches the material Type

**************************************/

//create purchase object
$calPurchase = new Purchases();
//call main variables
$PurchaseArray = $calPurchase->purchases_list_type2($matType);
$PurchaseRows = $calPurchase->purchases_list_numrows_type2($matType);


if ($PurchaseRows>0){
	$rows = $PurchaseArray;
	$rowDimension = new Dimensions();
	$count = 0;
	foreach ($rows as $row) {
		#line below is still in progress#
		$dimensions = $rowDimension->split_dimension_to_array($row); //--> split description_2 into each array

		//try inserting current row into calpurchase table
		/**********************************************
		 $row[] = current purchase row
		 $dimensions[] = the dimensions of the current purchase row
		**********************************************/
		#echo "<pre>".print_r($calPurchaseArray)."</pre>";
		$calPurchaseRows = $calPurchase->calpurchase_list_one_row($row['id']);
		#echo $calPurchaseRows;

		if ($calPurchaseRows==0) {
			#foreach ($calPurchaseArray as $calRow) { //--> loop this every row in calpurchase table
			#	echo "\$row['id'] = {$row['id']} && \$calRow['pid'] = {$calRow['pid']}";
			#	if ($row['id'] != $calRow['pid']) {  //--> check if the current selected row already exists in table
					$calPurchaseInsert = $calPurchase->calpurchase_create($matType,$row,$dimensions);
					$count++;
			#	}
			#}
		}
		

	}
	echo "Found and inserted $count new data into calPurchase table";

}

?>