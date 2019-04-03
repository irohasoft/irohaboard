<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav">
			<?php
			$is_active = (($this->name=='Users')&&($this->params["action"]!='admin_password')) ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('ユーザ'), array('controller' => 'users', 'action' => 'index')).'</li>';

			$is_active = ($this->name=='Groups') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('グループ'), array('controller' => 'groups', 'action' => 'index')).'</li>';

			$is_active = (($this->name=='Courses')||($this->name=='Contents')||($this->name=='ContentsQuestions')) ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('コース'), array('controller' => 'courses', 'action' => 'index')).'</li>';

			$is_active = ($this->name=='Infos') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('お知らせ'), array('controller' => 'infos', 'action' => 'index')).'</li>';

			$is_active = ($this->name=='Records') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('学習履歴'), array('controller' => 'records', 'action' => 'index')).'</li>';

			if($loginedUser['role']=='admin')
			{
				$is_active = ($this->name=='Settings') ? ' active' : '';
				echo '<li class="'.$is_active.'">'.$this->Html->link(__('システム設定'), array('controller' => 'settings', 'action' => 'index')).'</li>';
			}
			?>
		</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
