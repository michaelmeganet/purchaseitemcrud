<?php

include_once 'materials.inc.php';


$h = 25.4;
$length = 1000;
$Volume = Hexagon_Volume(hexagonArea($h), $length);

echo "Volume = $Volume<br>";


$density = 0.00000785000;

$weight = $density * $Volume;

echo "\$weight = $weight <br>";
