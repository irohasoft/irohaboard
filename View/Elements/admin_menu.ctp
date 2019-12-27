<?php $this->start('menu'); ?>
<?php echo $this->Html->css('elements');?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">


			<?php
			/** 受講生近況 */
			$is_active = ($this->name=='RecentStates') ? ' active' : '';
			$rs_url = $this->Html->url(array(
				'controller' => 'recentstates',
				'action' => 'index'
			));
			?>

			<?php echo '<li class = "'.$is_active.' dropdown">';?>
        <a href=<?php echo $rs_url;?> class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">受講生近況<span class="caret"></span></a>
					<?php
						echo '<ul class = "dropdown-menu" style = "font-size : 15px;">';
						echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'recentstates', 'action' => 'find_by_group')).'</li>';
						echo '<li>'.$this->Html->link(__('個人'), array('controller' => 'recentstates', 'action' => 'find_by_student')).'</li>';
						echo '<li>'.$this->Html->link(__('全受講生'), array('controller' => 'recentstates', 'action' => 'admin_all_view')).'</li>';
					?>
        </ul>
			</li>

			<?php
				/** データ一覧 */
				$is_active = ($this->name=='Data' or $this->name=='Records' or $this->name=='SoapRecords' or
										$this->name=='Enquete' or $this->name=='Attendances' or
										($this->name=='AdminManages' && $this->action=='admin_download')) ? ' active' : '';
				$d_url = $this->Html->url(array(
					'controller' => 'data',
					'action' => 'index'
				));
			?>

			<?php echo '<li class = "'.$is_active.' dropdown">';?>
        <a href=<?php echo $d_url;?> class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">データ一覧<span class="caret"></span></a>
					<?php
						echo '<ul class = "dropdown-menu" style = "font-size : 15px;">';
						echo '<li>'.$this->Html->link(__('学習履歴'), array('controller' => 'records', 'action' => 'index')).'</li>';
      			echo '<li>'.$this->Html->link(__('SOAP'), array('controller' => 'soaprecords', 'action' => 'index')).'</li>';
						echo '<li>'.$this->Html->link(__('アンケート'), array('controller' => 'enquete', 'action' => 'index')).'</li>';
						echo '<li>'.$this->Html->link(__('出欠席'), array('controller' => 'attendances', 'action' => 'index')).'</li>';
						echo '<li>'.$this->Html->link(__('授業データ'), array('controller' => 'adminmanages', 'action' => 'download')).'</li>';
					?>
        </ul>
			</li>

			<?php
				/** SOAP記入 */
				$is_active = ($this->name=='Soaps') ? ' active' : '';
				$soap_url = $this->Html->url(array(
					'controller' => 'soaps',
					'action' => 'index'
				));
			?>

			<?php echo '<li class = "'.$is_active.' dropdown">';?>
        <a href=<?php echo $soap_url;?> class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">SOAP記入<span class="caret"></span></a>
					<?php
						echo '<ul class = "dropdown-menu" style = "font-size : 15px;">';
						echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'soaps', 'action' => 'find_by_group')).'</li>';
						echo '<li>'.$this->Html->link(__('個人'), array('controller' => 'soaps', 'action' => 'find_by_student')).'</li>';
					?>
        </ul>
			</li>

			<?php
				/** 各種管理 */
				if($loginedUser['role']=='admin'){
					$is_active = ($this->name=='Managements' or $this->name=='Settings' or
												$this->name=='Users' or $this->name=='Groups' or
												$this->name=='Courses' or $this->name=='Infos' or
												($this->name=='AdminManages' && $this->action=='admin_index')) ? ' active' : '';
					$mg_url = $this->Html->url(array(
						'controller' => 'managements',
						'action' => 'index'
					));
			?>

					<?php echo '<li class = "'.$is_active.' dropdown">';?>
      		  <a href=<?php echo $mg_url;?> class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">各種管理<span class="caret"></span></a>
							<?php
								echo '<ul class = "dropdown-menu" style = "font-size : 15px;">';
								echo '<li>'.$this->Html->link(__('ユーザ'), array('controller' => 'users', 'action' => 'index')).'</li>';
								echo '<li>'.$this->Html->link(__('グループ'), array('controller' => 'groups', 'action' => 'index')).'</li>';
								echo '<li>'.$this->Html->link(__('コース'), array('controller' => 'courses', 'action' => 'index')).'</li>';
								echo '<li>'.$this->Html->link(__('その他管理'), array('controller' => 'managements', 'action' => 'other_index')).'</li>';
							?>
      		  </ul>
					</li>

				<?php } ?>

</nav>
<?php $this->end(); ?>
