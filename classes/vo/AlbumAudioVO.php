<?php
include_once("ConteudoVO.php");

class AlbumAudioVO extends ConteudoVO {
    
    protected $cod_formato = 3;
    private $lista_audios = array();
    
    public function setListaAudios($lista_audios) {
		$this->lista_audios = $lista_audios;
	}
	public function getListaAudios() {
		return $this->lista_audios;
	}
    
}