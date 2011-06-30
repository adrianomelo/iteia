<?php
$banners_exibidos = array(0);
include_once('classes/bo/BannerExibicaoBO.php');
$banbo = new BannerExibicaoBO;
?>
<div id="banners" class="vertical"><a href="/publicidade" title="Saiba mais sobre a publicidade no iTEIA">Publicidade Colaborativa</a> 
<?=$banbo->getHtmlBannersLaterais();?>    
</div>