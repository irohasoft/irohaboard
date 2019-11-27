<?php echo $this->Html->css('elements');?>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav menu-bar">
			<?php
			$is_active = ($this->name=='RecentStates') ? ' active' : '';
			echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('　受講生近況　'), array('controller' => 'recentstates', 'action' => 'index'));
			echo '<ul class="menu__second-level">';
			echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'recentstates', 'action' => 'find_by_group')).'</li>';
			echo '<li>'.$this->Html->link(__('個人'), array('controller' => 'recentstates', 'action' => 'find_by_student')).'</li>';
			echo '<li>'.$this->Html->link(__('全受講生'), array('controller' => 'recentstates', 'action' => 'admin_all_view')).'</li>';
			echo '</ul></li>';

			$is_active = ($this->name=='Records' or $this->name=='SoapRecords' or
										$this->name=='Enquete' or $this->name=='Attendances') ? ' active' : '';
			echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('　データ一覧　'), array('controller' => 'data', 'action' => 'index'));
			echo '<ul class="menu__second-level">';
      echo '<li>'.$this->Html->link(__('学習履歴'), array('controller' => 'records', 'action' => 'index')).'</li>';
      echo '<li>'.$this->Html->link(__('SOAP'), array('controller' => 'soaprecords', 'action' => 'index')).'</li>';
			echo '<li>'.$this->Html->link(__('アンケート'), array('controller' => 'enquete', 'action' => 'index')).'</li>';
			echo '<li>'.$this->Html->link(__('出欠席'), array('controller' => 'attendances', 'action' => 'index')).'</li>';
			echo '<li>'.$this->Html->link(__('授業データ'), array('controller' => 'adminmanages', 'action' => 'download')).'</li>';
			echo '</ul></li>';

			$is_active = ($this->name=='Soaps') ? ' active' : '';
			echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('　SOAP記入　'), array('controller' => 'soaps', 'action' => 'index'));
			echo '<ul class="menu__second-level">';
			echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'soaps', 'action' => 'find_by_group')).'</li>';
			echo '<li>'.$this->Html->link(__('個人'), array('controller' => 'soaps', 'action' => 'find_by_student')).'</li>';
			echo '</ul></li>';

			if($loginedUser['role']=='admin'){
				$is_active = ($this->name=='Settings' or $this->name=='Users' or
											$this->name=='Groups' or $this->name=='Courses' or
											$this->name=='Infos' or $this->name=='AdminManages') ? ' active' : '';
				echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('　各種管理　'), array('controller' => 'managements', 'action' => 'index'));
				echo '<ul class="menu__second-level">';
				echo '<li>'.$this->Html->link(__('ユーザ'), array('controller' => 'users', 'action' => 'index')).'</li>';
				echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'groups', 'action' => 'index')).'</li>';
				echo '<li>'.$this->Html->link(__('コース'), array('controller' => 'courses', 'action' => 'index')).'</li>';
				echo '<li>'.$this->Html->link(__('授業日程'), array('controller' => 'lessons', 'action' => 'index')).'</li>';
				echo '<li>'.$this->Html->link(__('その他管理'), array('controller' => 'managements', 'action' => 'other_index')).'</li>';
				echo '</ul></li>';
			}
			?>
		</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
