<?php
##################################################
# Variable lists:
#  currently selected page 		: $page
#  number of rows per page 		: $rowPage
#  Starting number of records 	: $startLimit
##################################################

//start pagination

    //set to first page if no page selected
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    //set number of rows per page
    $rowPage = 20;

    //calculate for limit clause
    $startLimit = ($rowPage * $page) - $rowPage;


//end setting pagination

//page url
$pageurl = $url;

//get number of records in the table
$rowCounter = $purchases->calpurchase_list_numrows_type2($matType);
#echo $rowCounter."<br>";

//get number of pages based on number of rows per page
$totPages = ceil($rowCounter/$rowPage);
#echo $totPages;

//set number of links to be shown on each side
$pageLimit = 3;

//set link page settings
$initPagesNum = $page - $pageLimit;
$lastPagesNum = ($page + $pageLimit) + 1;

//set the first and last number of records
$firstRecord = $startLimit + 1;
$lastRecord = (($firstRecord + $rowPage)<$rowCounter) ? $firstRecord + $rowPage : $rowCounter; //if it's larger than the number of rows, use number of rows instead.

//set the prev and after page number
$prevPage = $page-1;
$nextPage = $page+1;

//create variable to account for text
$textPagination = "";
//begin making pagination
# << < Page 1 of XXXX Pages. Displaying 1-15 of XXXXXX results. > >>
if($page>1){			//--> create prev & first button if not first page
	$textPagination .=  "<a href='{$pageurl}&page=1'><< </a>
	      		     	 <a href='{$pageurl}&page={$prevPage}'> <  </a>";
}
$textPagination .=  " Page {$page} of {$totPages}. Displaying {$firstRecord}-{$lastRecord} of {$rowCounter} results.";
if($page<$totPages){    //--> create next & last button if not last page
	$textPagination .= "<a href='{$pageurl}&page={$nextPage}'>  >  </a>
	                	<a href='{$pageurl}&page={$totPages}'> >></a>";
}


//end pagination


/**
//start making pagination
echo "<table><tr>";
//check if current page is not the first page


if($page>1){
	//if not first page, then create a pagination to the First Page
	echo "<td style='padding-right: 6px; padding-bottom: 10px'>";
		echo "<a href='{$pageurl}page=1' class='btnpage'>First</a>";
	echo "</td>";
}

for ($x=$initPagesNum; $x<$lastPagesNum; $x++){
	//check if current page
	if (($x>0) && ($x<=$totPages)) {
		if ($x == $page) {
			echo "<td style='padding-right: 6px; padding-bottom: 10px'";
			echo "<a class='btnlist'><b style='font-weight:bold'>$x</b></a>";
			echo "</td>";
		}else{
			echo "<td style='padding-right: 6px; padding-bottom: 10px'>";
			echo "<a href='{$pageurl}page=$x' class='btnlist'>$x</a>";
			echo "</td>";
		}
	}
		

}
//create Last Link
if ($page<$totPages) {
	echo "<td style='padding-bottom:10px'><a href={$pageurl}page={$totPages} class='btnpage'>Last</a>";
}
echo "</tr></table>"
**/
?>
