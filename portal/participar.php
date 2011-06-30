<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-suporte iteia';
$titulopagina = 'Como participar';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Como participar</span></div>
    <div id="conteudo">
      <h2 class="midia">Como participar</h2>

      <p class="caption">Você pode participar do iTEIA de várias formas.</p>
      <h3 class="midia">Como autor</h3>
      <p>O Autor é quem cria o conteúdo.</p>
      <p>Você poderá publicar conteúdos seus ou de terceiros, só não esqueça de colocar os créditos na Ficha Técnica. Portanto, você se cadastrando poderá postar o conteúdo que quiser! Basta respeitar os <a href="termos">Termos de Uso do iTeia.</a></p>
      <p>E tem mais... Cada pessoa terá uma página com seu cadastro, e com a senha de cadastro é simples e rápido administrar e publicar conteúdos no iTEIA. Será o seu perfil no iTeia!<br />
        Mas, lembre-se: Para você ser um Autor, é necessário aguardar que sua solicitação seja aprovada por algum dos nossos colaboradores, como também tudo que for publicado por você.</p>

    </div>
      <div class="caption">
        <ul>
          <li>› <a href="autores">Autores cadastrados</a></li>
          <li>› <a href="cadastro_autor">Cadastre-se como autor</a></li>
        </ul>
      </div>

      <h3 class="midia">Como colaborador</h3>
      <p>Você faz parte de alguma Instituição/Coletivo Cultural?</p>
      <p>Cadastre-a como Colaborador do iTeia!<br />
        Os Colaboradores além de publicar os conteúdos, também são os moderadores da rede reponsáveis por aprovar ou rejeitar conteúdos criados pelos autores.<br />
      </p>
      <p>Ex: para que Ítalo Souza seja um Autor, o Colaborador Engenho Cultural deve aceitá-lo como tal. Os conteúdos que Ítalo Souza postar irão para uma lista de espera que o Engenho Cultural terá acesso em sua página. Desta forma, o Engenho Cultural poderá aprovar, ou não, o conteúdo postado por Ítalo Souza, como conteúdo também do Engenho Cultural.<br />

      </p>
      <p>O Colaborador pode ter quantos Autores quiser!</p>

      <div class="caption">
        <ul>
          <li>› <a href="colaboradores">Colaboradores cadastrados</a></li>
          <li>› <a href="cadastro_colaborador">Cadastre-se como colaborador</a></li>

        </ul>
      </div>
      <h3 class="midia">Comentando</h3>
      <p>Em cada página de conteúdo existe um espaço para contato, os visitantes podem enriquecer os conteúdos com suas opiniões. Lembrando que não serão permitidos comentários que contenham palavras de baixo calão, publicidade, calúnia, injúria, difamação ou qualquer conduta que possa ser considerada criminosa. A equipe do portal iTEIA reserva-se no direito de apagar as mensagens.</p>
      <p class="caption"><img src="img/ajuda/comente.gif" alt="Imagem indica o link para o formulário de contato" width="267" height="43" class="img-border" /></p>
      <h3 class="midia">Compartilhando</h3>
      <p>O iTeia permite que você compartilhe os links pela Internet. Você vai encontrar, em cada página de conteúdo, o ícone Compartilhe, onde você poderá compartilhar as informações que estão na página do iTeia, com amigos e pessoas com interesses semelhantes, pela internet.</p>

      <p><img src="img/ajuda/compartilhe.gif" alt="Imagem indica onde pode ser feito o compartilhamento" width="262" height="43" class="img-border" /></p>
      <p class="caption">› <a href="bookmarks">Saiba mais sobre compartilhamento</a></p>

      <h3 class="midia">Denunciando</h3>

      <p>No iTeia você também é um moderador!</p>

<p>Você pode nos ajudar a manter os conteúdos do iTeia respeitando os Termos de Acesso. Basta denunciar o conteúdo indevido clicando no ícone Denuncie. Esse ícone se encontra nas páginas dos conteúdos e o levará para uma página de contato com a Equipe iTeia. </p>
<p ><img src="img/ajuda/denuncie.gif" alt="Imagem indica como denunciar conteúdos impróprios" width="262" height="43" class="img-border" /></p>

<?php
include ('includes/rodape.php');
