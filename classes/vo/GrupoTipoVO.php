<?php
class GrupoTipoVO {
	
	private $cod_tipo = 0;
	private $tipo = '';
	
	public function setCodTipo($cod_tipo) {
		$this->cod_tipo = $cod_tipo;
	}
	public function getCodTipo() {
		return $this->cod_tipo;
	}
	
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}
	public function getTipo() {
		return $this->tipo;
	}
	
}