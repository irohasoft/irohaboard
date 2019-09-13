<?php echo $this->Html->css('elements');?>
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

			$is_active = ($this->name=='Records' or $this->name=='SoapRecords') ? ' active' : '';
			echo '<li class="'.$is_active.' menu__single">'.$this->Html->link(__('検索一覧'), array('controller' => 'records', 'action' => 'index'));
			echo '<ul class="menu__second-level">';
      echo '<li>'.$this->Html->link(__('クイズ'), array('controller' => 'records', 'action' => 'index')).'</li>';
      echo '<li>'.$this->Html->link(__('SOAP'), array('controller' => 'soaprecords', 'action' => 'index')).'</li>';
			echo '<li>'.$this->Html->link(__('アンケート'), array('controller' => 'enquete', 'action' => 'index')).'</li>';
      echo '</ul></li>';

			$is_active = ($this->name=='RecentStates') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('近況状況'), array('controller' => 'recentstates', 'action' => 'index')).'</li>';

			$is_active = ($this->name=='Soaps') ? ' active' : '';
			echo '<li class="'.$is_active.'">'.$this->Html->link(__('SOAP記入'), array('controller' => 'soaps', 'action' => 'index')).'</li>';

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
