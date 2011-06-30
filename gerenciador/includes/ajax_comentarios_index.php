<div id="commentary" class="data-box">
        <div id="commentary-border" class="data-pending"> <span class="data-title">Coment&aacute;rios recentes</span><?php if((int)$total_comentarios):?><span class="comm">(<a href="comentarios.php" class="comm-link"><?=$total_comentarios;?></a>)</span><?php endif; ?><br />
          <div class="data-description">
              <!--Coment&aacute;rios aguardando aprova&ccedil;&atilde;o-->
            </div>
          <div class="overflow">
            <ul class="data-list">
            <?php
            //print_r($lista_comentarios);
            foreach($lista_comentarios as $key => $value):
            	if ((int)$value['cod']):
            ?>
              <li class="text<?=Util::iif($i % 2, ' bg');?>">
              <p><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Este link ser&aacute; aberto numa nova janela" target="_blank">.../<?=$value['url'];?></a><br />
                  <?=$value['comentario'];?><br />
                    <span class="sign"><strong><?=$value['autor'];?></strong> comentou em <?=date('d.m.Y - H\\hi', strtotime($value['data']));?></span><br />
                    <?=$value['html_index'];?><br />
                  </p>
              </li>
              <?php
              			$i++;
              		endif;
              endforeach;
			  ?>
			  </ul>
          </div>
        </div>
        <div id="commentary-all" class="data-complete right"><a href="comentarios.php">lista completa &raquo;</a></div>
      </div>