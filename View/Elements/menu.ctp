<?php $this->start('menu'); ?>
<?php echo $this->Html->css('elements');?>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
	<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#MenuBar" aria-controls="Navber" aria-expanded="false" aria-label="ナビゲーションの切替">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="MenuBar">
		<ul class="navbar-nav">
			<?php
				$is_active = ($this->name=='UsersCourses') ? ' active' : '';
				echo '<li class="nav-item '.$is_active.'">'.$this->Html->link(__('　マイページ　'), '/').'</li>';

				$is_active = ($this->name=='Enquete' && $this->action=='index') ? ' active' : '';
				echo '<li class="nav-item '.$is_active.'">'.$this->Html->link(__('　アンケート記入　'), array('controller' => 'enquete', 'action' => 'index')).'</li>';

				$is_active = ($this->name=='Enquete' && $this->action=='records') ? ' active' : '';
				echo '<li class="nav-item '.$is_active.'">'.$this->Html->link(__('　アンケート履歴　'), array('controller' => 'enquete', 'action' => 'records')).'</li>';
			?>
		</ul>
	</div>
</nav>
<?php $this->end(); ?>
