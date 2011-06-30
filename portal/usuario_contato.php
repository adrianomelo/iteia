<?php
include_once('classes/bo/UsuarioContatoBO.php');
$contatobo = new UsuarioContatoBO;

$codusuario = (int)$_GET['cod'];
$dados = $contatobo->getDadosUsuario($codusuario);

switch($dados['cod_tipo']) {
	case 1:
		$bread = '<a href="/colaboradores.php">Colaboradores</a> <span class="marcador">»</span> <a href="/'.$dados['url'].'">'.$dados['nome'].'</a>';
		$mensagem = 'Esta é a página de contato do(a) colaborador(a) <a href="/'.$dados['url'].'">'.$dados['nome'].'</a> de <a href="/busca_resultado.php?colaboradores=1&amp;cidade[]='.$dados['cod_cidade'].'" title="Listar autores por cidade">'.$dados['cidade'].'</a> - <a href="/busca_resultado.php?colaboradores=1&amp;estado[]='.$dados['cod_estado'].'" title="Listar autores por estado">'.$dados['sigla'].'</a>';
		break;
	case 2:
		$bread = '<a href="/autores.php">Autores</a> <span class="marcador">»</span> <a href="/'.$dados['url'].'">'.$dados['nome'].'</a>';
		$mensagem = 'Esta é a página de contato do(a) autor(a) <a href="/'.$dados['url'].'">'.$dados['nome'].'</a> de <a href="/busca_resultado.php?autores=1&amp;cidade[]='.$dados['cod_cidade'].'" title="Listar autores por cidade">'.$dados['cidade'].'</a> - <a href="/busca_resultado.php?autores=1&amp;estado[]='.$dados['cod_estado'].'" title="Listar autores por estado">'.$dados['sigla'].'</a>';
		break;
}

$topo_class = 'cat-contato iteia';
$titulopagina = 'Contato';
$js_usuariocontato = 1;
include ('includes/topo.php');
?>



    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <?=$bread?> <span class="atual">Contato</span></div>
    <div id="conteudo">
      
      <div class="principal">
      <h2 class="midia">Contato</h2>
        <p class="caption"><?=$mensagem;?></p>
       
      <div id="comentar">
        <form action="javascript:;" id="form-contato" name="formcontato">
            <div id="resposta_contato"></div>
				<fieldset>
				<input type="hidden" value="<?=$codusuario?>" name="codusuario" />
                <label for="denuncia">Mensagem:</label><br />
                <textarea id="mensagem" name="mensagem" cols="30" rows="5"></textarea><br />
                <label for="seu-nome">Seu nome:</label><br />
                <input type="text" id="seu-nome" name="nome" class="txt" /><br />
                <label for="seu-email">Seu e-mail:</label><br />
                <input type="text" id="seu-email" name="email" class="txt" /><br />
                <input class="btn" type="image" onclick="javascript:enviarContato();" src="/img/botoes/bt_enviar.gif" />
            </fieldset>
          </form>
      </div>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
