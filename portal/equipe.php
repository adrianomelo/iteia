<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-equipe iteia';
$titulopagina = 'Equipe';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/index.php" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Equipe</span></div>
    <div id="conteudo"><div class="principal">

        <h2 class="midia">Equipe</h2>
        
        <h3 class="sub">Coordena��o do Projeto</h3>
        <p><strong>Coordena��o Executiva</strong><br />

          Paulo Ramalho - Instituto InterCidadania<br />
Gest�o administrativa e coordena��o das atividades do projeto.<br />
        </p>

        <p><strong>Coordena��o de Forma��o e Articula��o</strong><br />
          Pedro Jatob� - Instituto InterCidadania<br />
Coordena a integra��o entre os benefici�rios / colaboradores (Pontos de Cultura e iniciativas culturais independentes) e equipe do projeto iTEIA.</p>
        <p><strong>Coordena��o de Desenvolvimento</strong><br />
          Billy Blay - Instituto InterCidadania<br />
          Coordena o desenvolvimento dos suportes tecnol�gicos necess�rios � execu��o do projeto.        </p>

          
          <p><strong>Coordena��o de Jornalismo</strong><br />
Jo�o Paulo Seixas - Instituto InterCidadania<br />
Coordena a manuten��o do Jornal iteia, modera��o dos conte�dos enviados pelos colaboradores e cria��o de pautas.</p>
        <h3 class="sub">Desenvolvimento Web</h3>

        <p><strong>Coordena��o de Produ��o</strong><br />
          Billy Blay - Instituto InterCidadania
            <br />

Coordena��o da equipe de Desenvolvimento Web, planejamento das intera��es.</p>
        <p><strong>Design</strong><br />
        Tales Pereira<br />

        Cria��o dos padr�es visuais, interfaces gr�ficas do  Portal.</p>
        <p><strong>Desenvolvimento Webstandards</strong><br />
          Billy Blay - Instituto InterCidadania<br />

          Implementa��o das interfaces em camadas de conte�do (XHTML), apresenta��o (CSS) e comportamento (JavaScript). � respons�vel pela manuten��o da acessibilidade do portal a portadores de defici�ncia e por meio de dispositivos m�veis.<br />
        </p>
        <h3 class="sub">Desenvolvimento dos Sistemas</h3>
        <p><strong>Coordena��o de Tecnologia</strong><br />
          Kerchenn Elteque - KMF<br />
          Coordena��o da equipe de Tecnologia, desenvolvimento e implementa��o dos sistemas que possibilitam o funcionamento do portal e do gerenciamento de conte�do.</p>

        <p><strong>Coordena��o de Projetos</strong><br />
        Jonas Lucena - KMF</p>
        <p><strong>Coordena��o de Engenharia de Software</strong><br />
        Mozart de Melo - KMF</p>
        <p><strong>L�der de Programa��o</strong><br />

        Marcel Ramos Cavalcante - KMF</p>
        <p><strong>Programa��o</strong><br />
          Eduardo Douglas - KMF<br />
          </p>
        <h3 class="sub"> Desenvolvimento dos Modulos Multimidia</h3>

        <p><strong>Coordena��o de Desenvolvimento</strong><br />
          Felipe Machado - Cultura Digital<br />
        Coordena a equipe de desenvolvimento da solu��o multim�dia em software livre necess�rios � execu��o do projeto.</p>
        <p><strong>Desenvolvimento</strong><br />

          Cl�udio da Silveira - Cultura Digital<br />

          L�cio Corr�a - Cultura Digital<br />
        Pesquisa, desenvolvimento e testes.</p>
        <p><strong>Documenta��o e Processos</strong><br />
          Thiago Moreira - Cultura Digital<br />

        An�lise, modelagem e desenvolvimento de processos.</p>

        <h3 class="sub">Libera��o dos C�digos</h3>

        <p><strong>Coordena��o</strong><br /> 
Anderson Goulart
  <br />
  Marcelo Soares Souza
  <br />
  Equipe respons�vel pela abertura dos c�digos e publica��o na internet</p>
      </div>


      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
    </div>
<?php
include ('includes/rodape.php');
