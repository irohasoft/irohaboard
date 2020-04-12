<?php $this->start('menu'); ?>
<ul class="navbar-nav mr-auto mt-2 mt-sm-0">
	<?php $is_active = ($this->name=='UsersCourses') ? ' active' : ''; ?>
	<li class="nav-item <?php echo $is_active; ?>">
		<?php echo $this->Html->link(__('ダッシュボード'), '/', array('class' => 'nav-link')); ?>
	</li>
	<?php $is_active = ($this->name=='RecentStates') ? ' active' : ''; ?>
	<li class="nav-item dropdown <?php echo $is_active; ?>">
		<a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			受講生近況
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<?php echo $this->Html->link(__('グループ'), array('controller' => 'recentstates', 'action' => 'find_by_group'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('個人'), array('controller' => 'recentstates', 'action' => 'find_by_student'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('全受講生'), array('controller' => 'recentstates', 'action' => 'admin_all_view'), array('class' => 'dropdown-item')); ?>
		</div>
	</li>
	<?php
		$is_active = ($this->name=='Data' or $this->name=='Records' or $this->name=='SoapRecords' or
									$this->name=='Enquete' or $this->name=='Attendances' or
								 ($this->name=='AdminManages' && $this->action=='admin_download')) ? ' active' : '';
	?>
	<li class="nav-item dropdown <?php echo $is_active; ?>">
		<a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			データ一覧
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<?php echo $this->Html->link(__('学習履歴'), array('controller' => 'records', 'action' => 'index'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('SOAP'), array('controller' => 'soaprecords', 'action' => 'submission_status'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('アンケート'), array('controller' => 'enquete', 'action' => 'submission_status'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('出欠席'), array('controller' => 'attendances', 'action' => 'attendance_status'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('授業データ'), array('controller' => 'adminmanages', 'action' => 'download'), array('class' => 'dropdown-item')); ?>
		</div>
	</li>
	<?php $is_active = ($this->name=='Soaps') ? ' active' : ''; ?>
	<li class="nav-item dropdown <?php echo $is_active; ?>">
		<a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			SOAP記入
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<?php echo $this->Html->link(__('グループ'), array('controller' => 'soaps', 'action' => 'find_by_group'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('個人'), array('controller' => 'soaps', 'action' => 'find_by_student'), array('class' => 'dropdown-item')); ?>
		</div>
	</li>
	<?php
		$is_active = ($this->name=='Managements' or $this->name=='Settings' or
									$this->name=='Users' or $this->name=='Groups' or
									$this->name=='Courses' or $this->name=='Infos' or
									$this->name=='Dates' or $this->name=='Lessons' or
								 ($this->name=='AdminManages' && $this->action=='admin_index')) ? ' active' : '';
	?>
	<li class="nav-item dropdown <?php echo $is_active; ?>">
		<a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			各種管理
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<?php echo $this->Html->link(__('ユーザ'), array('controller' => 'users', 'action' => 'index'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('グループ'), array('controller' => 'groups', 'action' => 'index'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('コース'), array('controller' => 'courses', 'action' => 'index'), array('class' => 'dropdown-item')); ?>
			<?php echo $this->Html->link(__('授業日'), array('controller' => 'dates', 'action' => 'index'), array('class' => 'dropdown-item')); ?>
			<div class="dropdown-divider"></div>
			<?php echo $this->Html->link(__('その他管理'), array('controller' => 'managements', 'action' => 'other_index'), array('class' => 'dropdown-item')); ?>
		</div>
	</li>
</ul>
<?php $this->end(); ?>
