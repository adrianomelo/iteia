<?php

class ConfigVO {
	const COD_SISTEMA = 6;
	const URL_SITE = "http://www.iteia.org.br/";
	//const DIR_SITE = "/home/ramom/penc/"; //local
	const DIR_SITE = "/home/pages/iteia4/"; //no ar
	const EMAIL = "contato@iteia.org.br"; //local
	//const DIR_CONTEUDO = "/home/ramom/penc/conteudo/"; //local
	//const DIR_CONTEUDO = "/mnt/penc_conteudo/"; //no ar
	const DIR_CONTEUDO = '/home/conteudo_midia/iteia/';

	public static function getDirPenc() {
		return self::DIR_SITE;
	}

	public static function getDirSite() {
		return self::DIR_SITE;
	}

	public static function getUrlSite() {
		return self::URL_SITE;
	}

	public static function getUrlSiteSemHttp() {
		return substr(self::URL_SITE, 7);
	}

	public static function getDirUtil() {
		return self::DIR_SITE.'classes/util/';
	}

	public static function getUrlImg() {
		return self::URL_SITE.'conteudo/imagens/';
	}

	public static function getDirImg() {
		return self::DIR_CONTEUDO.'imagens/';
	}
	// ===============================================================
    public static function getDirFotos() {
		return self::DIR_CONTEUDO.'imagens/fotos/';
	}
	public static function getUrlFotos() {
		return self::URL_SITE.'conteudo/imagens/fotos/';
	}
	// ===============================================================
    public static function getDirBanners() {
		return self::DIR_CONTEUDO.'imagens/banners/';
	}
	public static function getUrlBanners() {
		return self::URL_SITE.'conteudo/imagens/banners/';
	}
	// ===============================================================
    public static function getDirAgenda() {
		return self::DIR_CONTEUDO.'imagens/agenda/';
	}
	public static function getUrlAgenda() {
		return self::URL_SITE.'conteudo/imagens/agenda/';
	}
	// ===============================================================
	public static function getDirAudio() {
		return self::DIR_CONTEUDO.'audios/';
	}
	public static function getUrlAudio() {
		return self::URL_SITE.'conteudo/audios/';
	}
	// ===============================================================
	public static function getDirVideo() {
		return self::DIR_CONTEUDO.'videos/';
	}
	public static function getUrlVideo() {
		return self::URL_SITE.'conteudo/videos/convertidos/';
	}
	// ===============================================================
	public static function getDirTemp() {
		return self::DIR_CONTEUDO.'temp/';
	}
	public static function getUrlTemp() {
		return self::URL_SITE.'conteudo/temp/';
	}
    // ===============================================================
	public static function getDirArquivos() {
		return self::DIR_CONTEUDO.'arquivos/';
	}
	public static function getDirGaleria() {
		return self::DIR_CONTEUDO.'imagens/galeria/';
	}
	public static function getUrlGaleria() {
		return self::URL_SITE.'conteudo/imagens/galeria/';
	}
	public static function getDirImgCache() {
		return self::DIR_CONTEUDO.'img_cache/';
	}
	public static function getCodSistema() {
		return self::COD_SISTEMA;
	}

	public static function getEmail() {
		return self::EMAIL;
	}

}
