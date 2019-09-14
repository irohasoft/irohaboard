<?php echo $this->Html->css('elements');?>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav">
			<?php
			$is_active = ($this->name=='my_page') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('マイページ'), '/').'</li>';

			$is_active = ($this->name=='Enquete_input') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('アンケート記入'), array('controller' => 'enquete', 'action' => 'index')).'</li>';

			$is_active = ($this->name=='Enquete_history') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('アンケート履歴'), array('controller' => 'enquete', 'action' => 'records')).'</li>';
			?>
		</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
