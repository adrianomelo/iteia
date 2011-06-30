<?php
include("verificalogin.php");

$width = (int)$_GET['width'];
$height = (int)$_GET['height'];

if (!$width) $width = 144;
if (!$height) $height = 144;

include_once("classes/bo/ImagemTemporariaBO.php");
ImagemTemporariaBO::exibir($_GET["img"], $width, $height);
