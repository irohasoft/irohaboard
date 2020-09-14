<div class="admin-users-login">
	<div class="panel panel-default form-signin">
		<div class="panel-heading">
			<?php echo __('管理者ログイン')?>
		</div>
		<div class="panel-body">
			<div class="text-right"><a href="<?php echo Router::url(array('action' => 'login', 'admin' => false)) ?>"><?php echo __('受講者ログインへ')?></a></div>
			<?php echo $this->Flash->render('auth'); ?>
			<?php echo $this->Form->create('User'); ?>
			
			<div class="form-group">
				<?php echo $this->Form->input('username', array('label' => __('ログインID'), 'class'=>'form-control')); ?>
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('password', array('label' => __('パスワード'), 'class'=>'form-control'));?>
			</div>
			<?php echo $this->Form->end(array('label' => __('ログイン'), 'class'=>'btn btn-lg btn-primary btn-block')); ?>
		</div>
	</div>
</div>
