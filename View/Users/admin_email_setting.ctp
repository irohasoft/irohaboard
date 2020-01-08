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
	<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'setting'))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			メールアドレス設定
		</div>
		<div class="panel-body">
			<div class="current-setting">
			現在のメールアドレス<br/>
			<div class="current-address"><?php echo h($email_address); ?></div>
			</div>
			<?php
				echo $this->Form->create('User', Configure::read('form_defaults'));
				echo $this->Form->input('email', array(
					'type' => 'email',
					'label' => false,
					'placeholder' => '新しいメールアドレス',
					'autocomplete' => 'new-address'
				));

				echo $this->Form->input('email_confirm', array(
					'type' => 'email',
					'label' => false,
					'placeholder' => '新しいメールアドレス (確認用)',
					'autocomplete' => 'new-address'
				));
			?>
			<div class="form-group">
				<div class="col col-sm-9 col-sm-offset-8">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
