<?php echo $this->Html->css('elements');?>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav menu-bar">
			<?php
			$is_active = ($this->name=='UsersCourses') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('　マイページ　'), '/').'</li>';

			$is_active = ($this->name=='Enquete' && $this->action=='index') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('　アンケート記入　'), array('controller' => 'enquete', 'action' => 'index')).'</li>';

			$is_active = ($this->name=='Enquete' && $this->action=='records') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('　アンケート履歴　'), array('controller' => 'enquete', 'action' => 'records')).'</li>';
			?>
		</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
