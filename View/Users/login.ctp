<?php $this->start('css-embedded'); ?>
	<style type='text/css'>
		#remember_me
		{
			margin-left	: 10px;
		}
	</style>
<?php $this->end(); ?>
<div class="users form">
	<div class="panel panel-info form-signin">
		<div class="panel-heading">
			受講者ログイン
		</div>
		<div class="panel-body">
			<div class="text-right"><a href="../admin/users/login">管理者ログインへ</a></div>
			<?php echo $this->Flash->render('auth'); ?>
			<?php echo $this->Form->create('User'); ?>
			
			<div class="form-group">
				<?php echo $this->Form->input('username', array('label' => __('ログインID'), 'class'=>'form-control')); ?>
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('password', array('label' => __('パスワード'), 'class'=>'form-control'));?>
				<input type="checkbox" name="data[User][remember_me]" checked="checked" value="1" id="remember_me"><?php echo __('ログイン状態を保持')?>
			</div>
			<?php echo $this->Form->end(array('label' => 'ログイン', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
		</div>
	</div>
</div>
