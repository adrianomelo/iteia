<?php
include('verificalogin.php');

$editar = (int)$_POST['editar'];
$apagar = (int)$_GET["apagar"];
$palavrachave = strip_tags($_GET["palavrachave"]);
$codatividade = $_GET["codatividade"];

include_once("classes/bo/AtividadeBO.php");
$ativbo = new AtividadeBO;

if ($editar) {
	try {
		$cod_atividade = $ativbo->editar($_POST);
		
		Header("Location: conteudo_atividades.php");
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($apagar && !$palavrachave) {
	$ativbo->executaAcao($codatividade, 1);
	Header("Location: conteudo_atividades.php");
	exit();
}

$lista_atividades = $ativbo->getListaAtividades($palavrachave);

$paginatitulo = 'Atividades';
$item_menu = "conteudo";
$item_submenu = "atividades";
include('includes/topo.php');

?>
    <h3 class="titulo">Adicionar Atividades </h3>
    <div class="box">
      <form action="conteudo_atividades.php" method="post" id="frm-add-categoria">
        <fieldset>
        <input type="hidden" name="editar" value="1" />
        <input type="hidden" name="codatividade" value="0" />
        <label for="textfield">Nome<span>*</span></label>
        <br />
        <input type="text" name="atividade" class="txt" value="<?=htmlentities(stripslashes($ativbo->getValorCampo("atividade")))?>" <?=$ativbo->verificaErroCampo("atividade")?> id="textfield" />
       
        <input type="submit" value="Adicionar" class="bt-adicionar" />
        </fieldset>
      </form>
    </div>
    <h3 class="titulo">Atividades</h3>
<div id="resultado" class="box">
	<form method="get" id="form-result" action="conteudo_atividades.php">
	<input type="hidden" name="apagar" value="1" />
	<input type="hidden" id="acao" name="acao" value="0" />
	
	<p>
    <label for="textfield2">Filtrar atividades </label>
    <br />
    
      <input name="palavrachave" type="text" class="txt" id="textfield2" />
  </p>
	
  <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-conteudo">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />            </th>
            <th class="col-titulo" scope="col">T&iacute;tulo</th>
            <th class="col-editar" scope="col">Editar</th>
            <th class="col-remover" scope="col">Apagar</th>
          </tr>
        </thead>
        <tbody>
        	<?php
			foreach ($lista_atividades as $value):
				if (intval($value['cod'])):
			?>
          <tr>
            <td class="col-1"><input type="checkbox" name="codatividade[]" class="check" value="<?=$value['cod'];?>"  /></td>
            <td class="col-titulo"><?=$value['atividade'];?></td>
            <td class="col-editar"><a href="conteudo_atividades_editar.php?cod=<?=$value['cod'];?>&amp;height=100&amp;width=310" title="Editar tag" class="thickbox">Editar</a></td>
            <td class="col-remover"><a href="conteudo_atividades.php?apagar=1&amp;codatividade[]=<?=$value['cod'];?>">Apagar</a></td>
          </tr>
          	<?php
				endif;
			endforeach;
			?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro(1);">Apagar</a></td>
          </tr>
        </tfoot>
      </table>
      </form>
      <hr class="both" />
    </div>
  </div>

<?php include('includes/rodape.php'); ?>
