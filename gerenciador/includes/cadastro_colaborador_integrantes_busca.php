<div class="box" id="ficha-tecnica">
        <fieldset>
        <legend>Integrantes</legend>
          <?php
          if ($mostrar_colaborador): ?>
          	<p>Digite abaixo o nome do autor que deseja vincular a esse colaborador. Ele precisa j� estar cadastrado no sistema.</p>
          <?php else: ?>
          	<p>Digite abaixo o nome do autor que deseja vincular a esse grupo. Ele precisa j� estar cadastrado no sistema.</p>
          <?php endif; ?>
          
            <div class="campos">
            <label for="label">Nome original ou art�stico</label>
            <br />
            <input type="text" name="nome_integrante" class="txt" id="nome_integrante" size="60" />
             <strong><a href="javascript:adicionarIntegrante();" class="add">[+] Adicionar</a></strong>
         </div>
            
            <div id="mostrar_colaborador_intergrantes"></div>
            
        </fieldset>
      </div>