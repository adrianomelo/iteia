<?php
if (count($_SESSION["sess_conteudo_autores_ficha"][$this->dados_get['sessao_id']])) {
?>
            <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-cadastro">
            <caption>
              Ficha t&eacute;cnica desta obra
              </caption>
              <thead>
              <tr>
                <th class="col-titulo" scope="col">Nome</th>
                <th class="col-tipo" scope="col">Atividade</th>
                <th class="col-uf" scope="col">Estado</th>
<?php
	//if ($_SESSION['logado_dados']['nivel'] >= 5) {
?>
                <th class="col-editar" scope="col">Editar</th>
<?php
	//}
?>
                <th class="col-remover" scope="col">Remover</th>
              </tr>
            </thead>
            <tbody>
<?php
	foreach ($_SESSION["sess_conteudo_autores_ficha"][$this->dados_get['sessao_id']] as $autor) {
?>
              <tr>
                <td class="col-titulo"><?=htmlentities(stripcslashes($autor['nome']))?></td>
                <td class="col-tipo"><?=htmlentities(stripcslashes($autor['nome_atividade']))?></td>
                <td class="col-uf"><?=$autor['estado']?></td>
                <td class="col-editar">
<?php
		if ($autor['wiki']) {
?>
                <a href="conteudo_ficha_tecnica.php?cod=<?=$autor['codautor']?>&height=430&width=570&sessao_id=<?=$this->dados_get['sessao_id'];?>" title="Editar Ficha t&eacute;cnica" title="Editar" class="thickbox">Editar</a>
                <!--
<a href="javascript:abreEdicaoAutorFicha('<?=$autor['codautor']?>');" title="Editar">Editar</a>
-->
<?php
		}
?>
				</td>
                <td class="col-remover"><a href="javascript:removerAutorFicha('<?=$autor['codautor']?>')" title="Remover">Remover</a></td>
              </tr>
<?php
	}
?>
            </tbody>
          </table>	
<?php
}
?>

<script type="text/javascript" src="jscripts/thickbox/thickbox-compressed.js"></script>
<script type="text/javascript" src="jscripts/mini_scripts.js"></script>
