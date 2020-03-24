<?php $this->start('menu'); ?>
<ul class="navbar-nav mr-auto mt-2 mt-sm-0">
	<?php $is_active = ($this->name=='UsersCourses') ? ' active' : ''; ?>
	<li class="nav-item <?php echo $is_active; ?>">
		<?php echo $this->Html->link(__('ダッシュボード'), '/', array('class' => 'nav-link')); ?>
	</li>
	<?php $is_active = ($this->name=='Enquete') ? ' active' : ''; ?>
	<li class="nav-item dropdown <?php echo $is_active; ?>">
		<a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			アンケート
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<?php echo $this->Html->link(__('記入'), array('controller' => 'enquete', 'action' => 'index'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('履歴'), array('controller' => 'enquete', 'action' => 'records'), array('class' => 'dropdown-item')); ?>
		</div>
	</li>
</ul>
<?php $this->end(); ?>
