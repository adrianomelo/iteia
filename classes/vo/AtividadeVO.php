<?php
class AtividadeVO {
	
	private $cod_atividade = 0;
	private $atividade = '';
	
	public function setCodAtividade($cod_atividade) {
		$this->cod_atividade = $cod_atividade;
	}
	public function getCodAtividade() {
		return $this->cod_atividade;
	}
	
	public function setAtividade($atividade) {
		$this->atividade = $atividade;
	}
	public function getAtividade() {
		return $this->atividade;
	}
	
}