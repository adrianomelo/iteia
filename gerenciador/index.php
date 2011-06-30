<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/PrincipalBO.php");
$indexbo = new PrincipalBO;

$usuariodados = $indexbo->getUsuarioDados();

// notificacoes (autorizacoes)
include_once("classes/bo/NotificacaoBO.php");
$notifbo = new NotificacaoBO;
$notificacoes = $notifbo->getListaNotificacao(0, 6);

// listapublica
include_once("classes/bo/ListaPublicaBO.php");
$listabo = new ListaPublicaBO;
$listapublica = $listabo->getListaPublica(0, 2);

// aguardando minha aprovação
include_once("classes/bo/AguardandoAprovacaoBO.php");
$aprovacaobo = new AguardandoAprovacaoBO;
$listaaprovacao = $aprovacaobo->getListaAguardandoAprovacao(0, 2);

// recentes
include_once("classes/bo/ConteudoRecenteBO.php");
$recentebo = new ConteudoRecenteBO;
$listarecente = $recentebo->getListaConteudoRecente(0, 6);

$paginatitulo = 'Painel';
$item_menu = $item_submenu = 'index';
include('includes/topo.php');
?>
<script type="text/javascript" src="jscripts/comentarios.js"></script>

    <h2>Painel</h2>

<?php include('includes/index_painel.php'); ?>
    
    <div id="painel">
    
    <div id="shortcut">
        <div id="insert">
          <div id="insert-title">Clique nos &iacute;cones abaixo para inserir:</div>
          <ul>
            <li class="icon-img"><a href="conteudo_edicao_imagem.php" title="Inserir nova imagem">Inserir nova imagem</a></li>
            <li class="icon-video"><a href="conteudo_edicao_video.php" title="Inserir novo v&iacute;deo">Inserir novo v&iacute;deo</a></li>
            <li class="icon-audio"><a href="conteudo_edicao_audio.php" title="Inserir novo &aacute;udio">Inserir novo &aacute;udio</a></li>
            <li class="icon-text"><a href="conteudo_edicao_texto.php" title="Inserir novo texto">Inserir novo texto</a></li>
            <?php if ($_SESSION['logado_dados']['nivel'] < 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
            	<li class="entenda"><a href="conteudo_tipo.php">saiba mais</a></li>
            <?php endif; ?>
            
            <?php if ($_SESSION['logado_dados']['nivel'] >= 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
			<li class="icon-news"><a href="noticia_edicao.php" title="Inserir nova not&iacute;cia">Inserir nova not&iacute;cia</a></li>
            <?php endif; ?>
			<?php /*if (count($_SESSION['logado_dados']['grupo_responsavel'])): /*if ($_SESSION['logado_como'] > 1):*/ ?>
            <li class="icon-event"><a href="agenda_edicao.php" title="Inserir novo evento">Inserir novo evento</a></li>
            <?php /*endif;*/ ?>
            <?php if ($_SESSION['logado_dados']['nivel'] >= 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
            <li class="icon-adv"><a href="banners_edicao.php" title="Inserir novo an&uacute;ncio">Inserir novo an&uacute;ncio</a></li>
            <?php endif; ?>
          </ul>
          <div class="separador-hr"></div>
        </div>
        <div id="manage">
          <div id="manage-title">Clique nos &iacute;cones abaixo para gerenciar:</div>
          <ul>
          <?php if ($_SESSION['logado_dados']['nivel'] >= 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
				<li class="icon-author"><a href="cadastro.php" title="Gerenciar usu&aacute;rios">Gerenciar usu&aacute;rios</a></li>
            <!--
<li class="icon-coll"><a href="cadastro.php" title="Gerenciar colaboradores">Gerenciar colaboradores</a></li>
-->
				<li class="icon-coll"><a href="cadastro_colaborador.php" title="Gerenciar colaboradores">Gerenciar colaboradores</a></li>
			<?php endif; ?>
          </ul>
        </div>
        <div class="separador-hr"></div>
      </div>
    
<?php if (($_SESSION['logado_dados']['nivel'] >= 5) && (isset($_SESSION['logado_dados']['cod_colaborador']))): ?>

    <div id="authoring" class="data-box">
        <div id="authoring-border" class="data-pending"> <span class="data-title">Lista de autoriza&ccedil;&otilde;es</span><br />
            <div class="data-description">
            </div>
          <div class="overflow">
              
              <table width="100%" border="1" cellspacing="0" cellpadding="0" >
                <tbody>
                <?php
                foreach ($notificacoes as $key => $value):
                	if ((int)$value['cod_tipo']):
                		if (($value['cod_tipo'] == 150) or ($value['cod_tipo'] == 250)):
                ?>
                  <tr>
                  <td class="col-alerta"></td>
                  <td class="col-ico"><span class="<?=($value['cod_tipo'] == 250 ? 'colaborador' : 'autor'); ?>">Autor</span></td>
                  <td class="col-msg"><? if($value['cod_tipo'] == 250){ echo "Colaborador:"; } else { echo "Autor:"; } ?> <?=htmlentities($value['nome']);?><br />
                    Solicita aprova&ccedil;&atilde;o de cadastro</td>
                  <td class="col-ver"><a href="<? if($value['cod_tipo']==150){ echo $value['url_arquivo']; } else {  echo "index_exibir_colaborador_pendente.php?cod=$value[cod_colaborador]"; }?>" title="Clique para visualizar">Ver</a></td>
                </tr>
                <?php
                		else:
           		?>
				<tr>
                  <td class="col-alerta"><?=Util::iif($value['cod_colaborador'], '<span>alerta</span>', '&nbsp;');?></td>
                  <td class="col-ico"><?=$value['img_formato'];?></td>
                  <td class="col-msg">Autor: <a href="<?=ConfigVO::URL_SITE.$value['url'];?>"><?=htmlentities($value['nome']);?></a><br />
                  <?php if ($value['cod_tipo'] == 5): ?>
				  Modificou este conteúdo e pediu nova aprovação:
				  <?php endif; ?>
                    <strong><?=htmlentities($value['titulo']);?></strong></td>
                  <td class="col-ver"><a href="<?=$value['url_arquivo'];?>" title="Clique para visualizar">Ver</a></td>
                </tr>
           		<?php
                		endif;
                	endif;
                endforeach;
                ?>

                </tbody>
              </table>
          </div>
        </div>
        <div id="authoring-all" class="data-complete right"><a href="index_lista_notificacao.php">lista completa &raquo;</a></div>
        <div id="authoring-alert">Mensagens encaminhadas para serem analisadas diretamente pelo colaborador que voc&ecirc; representa.</div>
      </div>

<?php endif; ?>

      <div id="recent" class="data-box">
        <div id="recent-border" class="data-pending"><span class="data-title">Conte&uacute;dos recentes</span><br />
          <div class="data-description">Situa&ccedil;&atilde;o dos &uacute;ltimos conte&uacute;dos postados por voc&ecirc;</div>
          <div class="overflow">
          <span class="col-msg"><br />
          </span>
          <table width="100%" border="1" cellspacing="0" cellpadding="0" >
                <tbody>
                <?php
                foreach($listarecente as $key => $value):
                	if ((int)$value['cod_conteudo']):
                ?>
                  <tr>
                    <td class="col-ico"><?=$value['formato'];?></td>
                    <td class="col-msg"><a href="<?=$value['url_arquivo'];?>"><?=$value['titulo'];?></a></td>
                    <td class="col-data"><?=$value['data'];?></td>
                    <td class="col-situacao"><?=$value['situacao'];?></td>
                  </tr>
                <?php
                	endif;
                endforeach;
                ?> 
                </tbody>
              </table>
          </div>
        </div>
        <div id="recent-all" class="data-complete right"><a href="index_lista_recentes.php">lista completa &raquo;</a></div>
      </div>
      
      <div id="mostra_resultados_comentarios"></div>
    	      
    </div>
  </div>
  
<script language="javascript" type="text/javascript">
carregaComentariosIndex();
</script>
  
<?php include('includes/rodape.php'); ?>