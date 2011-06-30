<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-faq iteia';
$titulopagina = 'Social bookmarks';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Social bookmarks</span></div>
    <div id="conteudo">

      <div class="principal">
      <h2 class="midia">Social bookmarks</h2>
           <p>O iTEIA permite que você  armazene, compartilhe links  pela Internet.Você pode compartilhar estas informações com  amigos e pessoas com interesses semelhantes. Você também pode acessar as suas ligações a partir de qualquer computador com acesso à internet.</p>
        <p>Assim, se você se deparar com um conteúdo que  achar interessante e quiser salvar para referência futura ou compartilhar com outras pessoas, basta clicar em um desses links para adicionar à sua lista.</p>
          <p>Depois de ter registrado, você pode começar o bookmarking, escolha o serviço que se adapte  melhor às suas necessidades.</p>
          <p>Você pode descobrir mais sobre<a href="http://pt.wikipedia.org/wiki/Social_bookmarks"> social bookmarking na Wikipédia</a>.</p>
          
             <ul>
               <li id="b-twitter"><a href="http://twitter.com">twitter</a></li>
                  <li id="b-delicious"><a href="http://del.icio.us">delicious</a></li>
                  <!--<li id="b-google"><a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=http://endereco_do_conteudo">google bookmarks</a></li>-->
                  <li id="b-facebook"><a href="http://www.facebook.com">facebook</a></li>
                  <!--<li id="b-live"><a href="https://favorites.live.com/quickadd.aspx?marklet=1&amp;mkt=en-us&amp;url=http://endereco_do_conteudo">windows live</a></li>-->
                  <li id="b-yahoo"><a href="http://buzz.yahoo.com/buzz">Yahoo buzz</a></li>
                  <li id="b-digg"><a href="http://digg.com">digg it </a></li>
             </ul>
             
           
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
    </div>
<?php
include ('includes/rodape.php');
