<?php echo $this->element('admin_menu'); ?>
<?php echo $this->Html->css('custom'); ?>
<div class="admin-users-setting full-view">
	<div class="breadcrumb">
		<?php
			$this->Html->addCrumb('HOME', array(
				'controller' => 'recentstates',
				'action' => 'index'
			));
			echo $this->Html->getCrumbs(' / ');
		?>
	</div>
	<?php echo $this->Html->link(__('<< 戻る'), array('controller' => 'recentstates', 'action' => 'index'))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			設定
		</div>
		<div class="panel-body">
		  <button type = "button" class = "btn btn-primary select-btn"
		    onclick = "location.href = '<?php echo Router::url(array('action' => 'email_setting'))?>'">
				メールアドレス設定
			</button>
		  <button type = "button" class = "btn btn-primary select-btn"
		    onclick = "location.href = '<?php echo Router::url(array('action' => 'password_setting'))?>'">
					パスワード設定
			</button>
		</div>
	</div>
</div>
