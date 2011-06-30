<?php
include_once("UsuarioDAO.php");

class FormularioDAO extends UsuarioDAO {

	public function cadastrar(&$usuariovo) {
	    $codusuario = $this->cadastrarUsuario($usuariovo);
	    if ($codusuario) {
	    	sort($usuariovo->getInteresses());
            $this->banco->sql_insert('Usuarios_Informacoes', array('cod_usuario' => $codusuario, 'interesses' => implode(',', $usuariovo->getInteresses()), 'cpf' => $usuariovo->getCPF()));

        	if ($usuariovo->getCodTipo() == 1)
        		$this->banco->sql_insert('Colaboradores', array('cod_usuario' => $codusuario));

        	if ($usuariovo->getCodTipo() == 2)
        		$this->banco->sql_insert('Autores', array('cod_usuario' => $codusuario));

        	if ($usuariovo->getCodTipo() == 3)
        		$this->banco->sql_insert('Grupos', array('cod_usuario' => $codusuario));

        	$i = 0;
			$url = $usuariovo->getUrl();
			do {
				if ($i)
					$url = $url.$i;
					$sql = "insert into Urls values ('".$url."', '".$codusuario."', '".$usuariovo->getCodTipo()."', '".ConfigVO::getCodSistema()."')";
					$tenta = $this->banco->executaQuery($sql);
					$i++;
			}
			while (!$tenta);

			$this->banco->sql_insert('Usuarios_Estatisticas', array('cod_usuario' => $codusuario));
		}
        return $codusuario;
    }

}