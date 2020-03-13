<?php

$comparePurchase = compare_table_row_count();

if ($comparePurchase) { //-->if the number of column is the same
    $objPurchase = new Purchase();
    $rowPurchasef = $objPurchase->purchase_fetch_one_row_ordered('first');
    $rowPurchasel = $objPurchase->purchase_fetch_one_row_ordered('last');
    $rowCalPurchasef = $objPurchase->calpurchase_fetch_one_row_ordered('first');
    $rowCalPurchasel = $objPurchase->calpurchase_fetch_one_row_ordered('last');
    $compareResultf = compareArray($rowPurchasef, $rowCalPurchasef);
    $compareResultl = compareArray($rowPurchasel, $rowCalPurchasel);

    if (($compareResultf == 'array is same') &&
            ($compareResultl == 'array is same')) {
        # do nothing
    } else {
        #<-- insert 'truncate function' here -->
        #<-- insert 'convert function' here -->
        #<-- insert 'insert data function' here -->
    }
} else { //-->if the number of column is not the same
    #<-- insert 'truncate function' here -->
    #<-- insert 'convert function' here -->
    #<-- insert 'insert data function' here -->
}
?>