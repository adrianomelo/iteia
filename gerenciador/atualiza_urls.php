<?php
include_once('../classes/dao/ConexaoDB.php');
include_once('../classes/util/Util.php');

class Atualiza {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
		
		$sql = "SELECT t1.cod_usuario, t1.nome FROM Usuarios AS t1 INNER JOIN Usuarios_Niveis AS t2 ON (t1.cod_usuario=t2.cod_usuario) WHERE t2.nivel='1' and t1.cod_sistema='1'";
		$query = $this->banco->executaQuery($sql);
		while ($row = $this->banco->fetchArray($query)) {
			
			$sql1 = "select cod_item from Urls WHERE cod_item='".$row['cod_usuario']."' and tipo='2' and cod_sistema='1'";
			if (!$this->banco->numRows($this->banco->executaQuery($sql1))) {
			
				echo $row["nome"].' - ';
				
				$i = 0;
				$url = Util::geraUrlTitulo(substr(trim($row["nome"]), 0, 30));
				do {
					if ($i)
						$url = $url.$i;
						$sql = "insert into Urls values ('".$url."', '".$row['cod_usuario']."', '2', '1')";
						$tenta = $this->banco->executaQuery($sql);
						$i++;
				}
				while (!$tenta);
				
				echo $url.'<br />';
			
		 	}
			
		}
		
	}

}

$c = new Atualiza;
echo 'ok';