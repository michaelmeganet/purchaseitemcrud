<?php

include_once "class/dbh.inc.php";
include_once "variables.inc.php";

/**
 *
 */
function calPlateVolume($thick, $width, $length) {

    $volume = $thick * $width * $length;
    return $volume;
}

function calRodVolume($diameter, $length) {
// $Area = cross section area
    $radius = $diameter / 2;
    $Area = pi() * $radius * $radius;
    $volume = $Area * $length;
    return $volume;
}

function circularArea($diameter) {
// calculate cross section area for ROD (cylindrical cross section)
    $radius = $diameter / 2;
    $Area = pi() * $radius * $radius;
    return $Area;
}

function hexagonArea($h) {

    $S = (float) 2.0 * (float) $h / (float) sqrt(3.0); //edge to edge measurement $S
    // to the measurement of thickness of hexagon ($h)
    $Area = $S * $S(3.0 * sqrt(3.0)) / 2; //Area of Hexagon

    return $Area;
}

function Hexagon_Volume($Area, $length) {

}

//$h= 6.0;
//$Hex_bar = Hexagon_Volume(hexagonArea($h), $length);

function PLATE_Circle_Volume($diameter, $thick) {

    //radius = $diameter / 2;

    $Area = circularArea($diameter);
    $volume = $Area * $thick;
    return $volume;
}

function PLATE_Circle_withHole_Volume($OD, $ID, $thick) {
// PLATECO category
    $Radius_OD = $OD / 2;
    $Radius_ID = $ID / 2;

    $Area = (pi() * $Radius_OD * $Radius_OD) - (pi() * $Radius_ID * $Radius_ID );

    $Volume = $Area * $thick;
    return $Volume;
}

function weight($Volume, $material_acc) {

    // implement get material type by SQL
    //SELECT * FROM material  WHERE material_acc = 'KD21 - Plate'
    $sqlmat = "SELECT materialtype FROM material WHERE material_acc = '$material_acc'";
    $objMat = new SQL($sqlmat);
    $result = $objMat->getResultOneRowArray();
    $materialtype = $result['materialtype'];
    $sqldensity = "SELECT * FROM material_density WHERE materialtype = '$materialtype'";
    $objDen = new SQL($sqldensity);
    $resultden = $objDen->getResultOneRowArray();
    $density = $resultden['plate'];
    //$mat_type = 'ts';
    $weight = $Volume * $density;
    return weight;
}

class Material {

    function __construct() {
# code...
    }

    function getCategory() {
        $qr = "SELECT DISTINCT category FROM purchase";

        $sqlObj = new SQL($qr);
        $result = $sqlObj->getResultRowArray();

        return $result;
    }

    function getShapeCode($category) {
        $qr = "SELECT DISTINCT Shape_Code FROM purchase WHERE category ='{$category}'";

        $sqlObj = new SQL($qr);
        $result = $sqlObj->getResultRowArray();

        return $result;
    }

}

?>