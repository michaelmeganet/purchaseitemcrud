<?php

include_once '../../class/dbh.inc.php';
include_once '../../class/variables.inc.php';

function compare_table_row_count() {
    $objPurchase = new Purchase();

    $countPurchase = $objPurchase->purchase_count_rowtest();
    $countCalPurchase = $objPurchase->calpurchase_count_rowtest();

    if ($countCalPurchase == $countPurchase) {
        $result = 'count is the same';
    } else {
        $result = 'count is not same';
    }
    return $result;
}

function compareArray($array1, $array2) {
    $arraydiff = array_diff($array1, $array2);

    if (count($arraydiff) == 0) {
        $result = 'array is same';
    } else {
        $result = 'array not same';
    }

    return $result;
}

function calpurchase_count_rowtest() {
    $qr = "SELECT COUNT(*) FROM calpurchase
    WHERE Shape_Code = 'PLATE'
    OR Shape_Code = 'O'
    AND Item_Code NOT LIKE 'Upkeep%'";

    $objSQL = new SQL($qr);
    $result = $objSQL->getRowCount();

    return $result;
}

function purchase_count_rowtest() {
    $qr = "SELECT COUNT(*) FROM purchase
    WHERE Shape_Code = 'PLATE'
    OR Shape_Code = 'O'
    AND Item_Code NOT LIKE 'Upkeep%'";

    $objSQL = new SQL($qr);
    $result = $objSQL->getRowCount();

    return $result;
}

function purchase_fetch_one_row_ordered($selectRow) {
    if ($selectRow == 'first') {
        $ordered = 'ASC';
    } elseif ($selectRow == 'last') {
        $ordered = 'DESC';
    }
    $qr = "SELECT Doc_No, Item_Code, Shape_Code, Qty, SubTotal
    FROM purchase
    WHERE Shape_Code = 'PLATE'
    OR Shape_Code = 'O'
    AND Item_Code NOT LIKE 'Upkeep%'
    ORDER BY id {$ordered}
    LIMIT 1";

    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();

    return $result;
}

function calpurchase_fetch_one_row_ordered($selectRow) {
    if ($selectRow == 'first') {
        $ordered = 'ASC';
    } elseif ($selectRow == 'last') {
        $ordered = 'DESC';
    }
    $qr = "SELECT Doc_No, Item_Code, Shape_Code, Qty, SubTotal
    FROM calpurchase
    WHERE Shape_Code = 'PLATE'
    OR Shape_Code = 'O'
    AND Item_Code NOT LIKE 'Upkeep%'
    ORDER BY pid {$ordered}
    LIMIT 1";

    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();

    return $result;
}
