<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");

class DirecionarBO {

    public static function getTipoConteudo($endereco, $tipo) {
        $contdao = new ConteudoExibicaoDAO;
        return $contdao->getTipoConteudo($endereco, $tipo);
    }

}