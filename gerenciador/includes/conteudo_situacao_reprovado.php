<p><a href="index_lista_recentes.php" class="voltar" title="Voltar">Voltar para lista de conte&uacute;dos recentes</a></p>
    <strong>Situa&ccedil;&atilde;o:</strong>
    <div class="box box-alerta">
      <p>Este conte&uacute;do foi rejeitado pelo colaborador &quot;<strong><?=$motivo['colaborador'];?></strong>&quot;</p>
      <p><strong>Motivo da rejei&ccedil;&atilde;o:</strong></p>
      <?=Util::iif($motivo['comentario'], $motivo['comentario'], '<i>Motivo não informado</i>');?>
	</div>
	