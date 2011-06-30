<?php
class TagsVO {
	
	private $cod_tag = 0;
	private $tag = '';
	
	public function setCodTag($cod_tag) {
		$this->cod_tag = $cod_tag;
	}
	public function getCodTag() {
		return $this->cod_tag;
	}
	
	public function setTag($tag) {
		$this->tag = $tag;
	}
	public function getTag() {
		return $this->tag;
	}
	
}