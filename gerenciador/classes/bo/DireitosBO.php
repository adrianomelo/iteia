<?php

class DireitosBO {

	public function setDadosForm(&$dadosform) {
		$dadosform["direitos"] = (int)$dadosform["direitos"];
		$dadosform["cc_usocomercial"] = (int)$dadosform["cc_usocomercial"];
		$dadosform["cc_obraderivada"] = (int)$dadosform["cc_obraderivada"];
	}

	public function validaDados(&$dadosform) {
		$erromsg = array();

		if (!$dadosform["direitos"])
			$erromsg[] = "Selecione um tipo de Direito Autoral";
		elseif ($dadosform["direitos"] == 3) {
			if (!$dadosform["cc_usocomercial"])
				$erromsg[] = "Defina se permite o uso comercial da sua obra";
			if (!$dadosform["cc_obraderivada"])
				$erromsg[] = "Defina se permite modificações em sua obra";
		}

		return $erromsg;
	}

	public function getCodLicenca($direitos, $cc_usocomercial, $cc_obraderivada) {
		if ($direitos == 1) //dominio publico
			return 7;
		if ($direitos == 2) //direitos reservados
			return 8;
		if ($direitos == 3) { //creative commons
			if ($cc_usocomercial == 1) { //uso comercial
				if ($cc_obraderivada == 1) //permite modificacoes
					return 1;
				if ($cc_obraderivada == 2) //permite modificacoes - mesma licença
					return 2;
				if ($cc_obraderivada == 3) //nao permite modificacoes
					return 3;
			}
			if ($cc_usocomercial == 2) { //nao uso comercial
				if ($cc_obraderivada == 1) //permite modificacoes
					return 4;
				if ($cc_obraderivada == 2) //permite modificacoes - mesma licença
					return 5;
				if ($cc_obraderivada == 3) //nao permite modificacoes
					return 6;
			}
		}
	}

	public function setDadosCamposEdicao($codlicenca) {
		$dados_direito = array("direitos" => 0, "cc_usocomercial" => 0, "cc_obraderivada" => 0);
		switch ($codlicenca) {
			case 1:
				$dados_direito["direitos"] = 3;
				$dados_direito["cc_usocomercial"] = 1;
				$dados_direito["cc_obraderivada"] = 1;
				break;
			case 2:
				$dados_direito["direitos"] = 3;
				$dados_direito["cc_usocomercial"] = 1;
				$dados_direito["cc_obraderivada"] = 2;
				break;
			case 3:
				$dados_direito["direitos"] = 3;
				$dados_direito["cc_usocomercial"] = 1;
				$dados_direito["cc_obraderivada"] = 3;
				break;
			case 4:
				$dados_direito["direitos"] = 3;
				$dados_direito["cc_usocomercial"] = 2;
				$dados_direito["cc_obraderivada"] = 1;
				break;
			case 5:
				$dados_direito["direitos"] = 3;
				$dados_direito["cc_usocomercial"] = 2;
				$dados_direito["cc_obraderivada"] = 2;
				break;
			case 6:
				$dados_direito["direitos"] = 3;
				$dados_direito["cc_usocomercial"] = 2;
				$dados_direito["cc_obraderivada"] = 3;
				break;
			case 7:
				$dados_direito["direitos"] = 1;
				break;
			case 8:
				$dados_direito["direitos"] = 2;
				break;
		}
		return $dados_direito;
	}

}