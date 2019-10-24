<?php echo $this->Html->css('elements');?>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav">
			<?php
			$is_active = ($this->name=='RecentStates') ? ' active' : '';
			echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('近況状況'), array('controller' => 'recentstates', 'action' => 'index'));
			echo '<ul class="menu__second-level">';
			echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'recentstates', 'action' => 'find_by_group')).'</li>';
			echo '<li>'.$this->Html->link(__('個人'), array('controller' => 'recentstates', 'action' => 'find_by_student')).'</li>';
			echo '<li>'.$this->Html->link(__('全受講生'), array('controller' => 'recentstates', 'action' => 'admin_all_view')).'</li>';
			echo '</ul></li>';

			$is_active = ($this->name=='Records' or $this->name=='SoapRecords' or $this->name=='Enquete' or $this->name=='Attendances') ? ' active' : '';
			echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('検索一覧'), array('controller' => 'records', 'action' => 'index'));
			echo '<ul class="menu__second-level">';
      echo '<li>'.$this->Html->link(__('クイズ'), array('controller' => 'records', 'action' => 'index')).'</li>';
      echo '<li>'.$this->Html->link(__('SOAP'), array('controller' => 'soaprecords', 'action' => 'index')).'</li>';
			echo '<li>'.$this->Html->link(__('アンケート'), array('controller' => 'enquete', 'action' => 'index')).'</li>';
			echo '<li>'.$this->Html->link(__('出欠席'), array('controller' => 'attendances', 'action' => 'index')).'</li>';
			echo '</ul></li>';

			$is_active = ($this->name=='Soaps') ? ' active' : '';
			echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('SOAP記入'), array('controller' => 'soaps', 'action' => 'index'));
			echo '<ul class="menu__second-level">';
			echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'soaps', 'action' => 'find_by_group')).'</li>';
			echo '<li>'.$this->Html->link(__('個人'), array('controller' => 'soaps', 'action' => 'find_by_student')).'</li>';
			echo '</ul></li>';

			$is_active = ($this->name=='Infos') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('お知らせ'), array('controller' => 'infos', 'action' => 'index')).'</li>';

			if($loginedUser['role']=='admin')
			{
				$is_active = ($this->name=='Settings' or $this->name=='Users' or $this->name=='Groups' or $this->name=='Courses' or $this->name=='AdminManages') ? ' active' : '';
				echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('システム設定'), array('controller' => 'settings', 'action' => 'index'));
				echo '<ul class="menu__second-level">';
				echo '<li>'.$this->Html->link(__('ユーザ'), array('controller' => 'users', 'action' => 'index')).'</li>';
				echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'groups', 'action' => 'index')).'</li>';
				echo '<li>'.$this->Html->link(__('コース'), array('controller' => 'courses', 'action' => 'index')).'</li>';
				echo '<li>'.$this->Html->link(__('管理者ページ'), array('controller' => 'adminmanages', 'action' => 'index')).'</li>';
				echo '</ul></li>';
			}
			?>
		</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
