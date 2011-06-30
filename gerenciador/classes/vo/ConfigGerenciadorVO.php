<?php
require_once("../classes/vo/ConfigVO.php");

class ConfigGerenciadorVO {

	const CODPAIS_BRASIL = 2;

	public static function getDirClassesGerenciador() {
		return ConfigVO::DIR_SITE."gerenciador/classes/";
	}
	
	public static function getDirClassesRaiz() {
		return ConfigVO::DIR_SITE."classes/";
	}

	public static function getCodPaisBrasil() {
		return self::CODPAIS_BRASIL;
	}

}