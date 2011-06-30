<?php
include('verificalogin.php');
if (count($_SESSION['sessao_conteudo_relacionamento'])):
?>
<div class="box">
    <h3>Lista de conte&uacute;dos relacionados</h3>
    <ul class="lista-remover">
    	<?php foreach ($_SESSION['sessao_conteudo_relacionamento'] as $codconteudo => $value): ?>
            <li><span><?=Util::getFormatoConteudo($value['cod_formato']);?> - <?=$value['titulo'];?></span> <a href="javascript:removerConteudoRelacionamento(<?=$codconteudo;?>);" title="Remover" class="remover">Remover</a></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>