<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once('classes/bo/BuscaBO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$js_busca = true;
$topo_class = 'iteia avancada';
$titulopagina = 'Busca avançada';
include ('includes/topo.php');
$buscabo = new BuscaBO();
?>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/edicao.js"></script>
<script type="text/javascript">
function executaBusca() {
	var url = 'busca_action.php?buscar=1';
	if ($('#pchave').val()) url += '&palavra=' + $('#pchave').val();
	if ($('#dFrom').val()) url += '&data1=' + $('#dFrom').val();
	if ($('#dTo').val()) url += '&data2=' + $('#dTo').val();
	if ($('#estado').val()) url += '&estados=' + $('#estado').val();
	if ($('#selectcidade').val()) url += '&cidades=' + $('#selectcidade').val();
	
	url += '&formatos=';
	var formatos = new Array();
	
	if ($('#audios:checked').val()) formatos.push(2);
	if ($('#videos:checked').val()) formatos.push(3);
	if ($('#textos:checked').val()) formatos.push(4);
	if ($('#imagens:checked').val()) formatos.push(5);
	if ($('#noticias:checked').val()) formatos.push(6);
	if ($('#eventos:checked').val()) formatos.push(7);
	if ($('#autores:checked').val()) formatos.push(9);
	if ($('#colaboradores:checked').val()) formatos.push(10);
	url += formatos.join(',');
	
	if ($('input[name="licenca"]:checked').val()) url += '&direito=' + $('input[name="licenca"]:checked').val();
	document.location = url;
}
</script>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Busca avançada</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Busca avançada</h2>
        <p class="caption">A busca pode ser feita com apenas um campo preenchido.<br />
          Para buscar textos contendo mais de uma palavra, digite-as separadas por um espaço.</p>
		<form action="/busca_action.php" id="form-busca-avancada" onsubmit="return false;">
		<input type="hidden" name="buscar" value="1" />
          <label for="pchave" class="titulo">Palavra-chave:</label>
          <br />
          <input type="text" id="pchave" name="palavra" class="txt" />
          <fieldset id="periodo">
          <legend>Por Período</legend>
		   <p><small>A busca pode ser feita em períodos de até 12 meses</small></p>
          <label for="dFrom" class="none">Data inicial</label>
          <br />
          <input type="text" name="de" id="dFrom" class="txt date small"/><small> ex. 12/05/2010</small>
          <br />
          <label for="dTo" class="none">Data final</label>
          <br />
          <input type="text" name="ate" id="dTo" class="txt date small"/><small> ex. 12/05/2010</small>
         
          </fieldset><fieldset id="local">
          <legend>Por local</legend>
          <label for="state">Estado:</label><br />
          <span style="display: inline;">
          <select name="estado[]" id="estado" onchange="obterCidades(this, this.value)" class="slc">
          <?php
		echo "<option value=\"0\"";
		if (!$codestado)
			echo " selected=\"selected\"";
		echo ">Selecione o Estado</option>\n";
		$lista_estados = $buscabo->getListaEstados();
				foreach ($lista_estados as $estado) {
			echo "<option value=\"".$estado["cod_estado"]."\"";
			if ($estado["cod_estado"] == 0)
				echo " selected=\"selected\"";
			echo ">".$estado["sigla"]."</option>\n";
		}
		?>
          </select>  
          </span>          <br/>
          <label for="city">Cidade:</label><br />
          <select name="cidade[]" id="selectcidade" class="slc">
				<option value="0">Todas</option>
			</select>
			
          </fieldset>
          <fieldset id="tipo">
          <legend>Por tipo de conteúdo</legend>
          <ul>
            <li>
              <label>
              <input type="checkbox" name="audios" id="audios" class="chk" value="1" />
              Áudios</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="videos" id="videos" class="chk" value="1" />
              Vídeos</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="textos" id="textos" class="chk" value="1" />
              Textos</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="imagens" id="imagens" class="chk" value="1" />
              Imagens</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="noticias" id="noticias" class="chk" value="1" />
              Notícias</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="agenda" id="eventos" class="chk" value="1" />
              Eventos</label>
            </li>
          </ul>
          </fieldset>
               <fieldset id="licenca">
          <legend>Por licença</legend>
          <ul>
            <li>
              <label>
              <input type="radio" name="licenca" class="radio" value="0" />
              Todas</label>
            </li>
            <li>
              <label>
              <input type="radio" class="radio" name="licenca" value="1" />
              Para uso comercial</label>
            </li>
            <li>
              <label>
              <input type="radio" class="radio" name="licenca" value="2" />
              Para adaptação e modificação</label>
            </li>
          </ul>
          </fieldset>
          <fieldset id="usuarios">
          <legend>Por tipo de usuário</legend>
          <ul>
            <li>
              <label>
              <input type="checkbox" name="autores" id="autores" class="chk" value="1" />
              Autores</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="colaboradores" id="colaboradores" class="chk" value="1" />
              Colaboradores</label>
            </li>
          </ul>
          </fieldset>
          <br />
          <input class="btn" type="image" onclick="javascript:executaBusca();" src="/img/botoes/bt_buscar.gif" />
        </form>
      </div>
    </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
<?php
include ('includes/rodape.php');
