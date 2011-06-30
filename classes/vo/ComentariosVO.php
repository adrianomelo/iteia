<?php

class ComentariosVO {
    
    private $cod_conteudo = 0;
    private $cod_comentario = 0;
    private $comentario = "";
    private $nome = "";
    private $email = "";
    private $site = "";
    
    public function setCodConteudo($cod_conteudo) {
        $this->cod_conteudo = $cod_conteudo;
    }
    public function getCodConteudo() {
        return $this->cod_conteudo;
    }
    
    public function setCodComentario($cod_comentario) {
        $this->cod_comentario = $cod_comentario;
    }
    public function getCodComentario() {
        return $this->cod_comentario;
    }
    
    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }
    public function getComentario() {
        return $this->comentario;
    }
    
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function getNome() {
        return $this->nome;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    public function getEmail() {
        return $this->email;
    }
    
    public function setSite($site) {
        $this->site = $site;
    }
    public function getSite() {
        return $this->site;
    }
    
}