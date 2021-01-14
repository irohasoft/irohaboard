<div class="admin-users-login">
	<div class="panel panel-default form-signin">
		<div class="panel-heading">
			<?= __('管理者ログイン')?>
		</div>
		<div class="panel-body">
			<div class="text-right"><a href="<?= Router::url(['action' => 'login', 'admin' => false]) ?>"><?= __('受講者ログインへ')?></a></div>
			<?= $this->Flash->render('auth'); ?>
			<?= $this->Form->create('User'); ?>
			
			<div class="form-group">
				<?= $this->Form->input('username', ['label' => __('ログインID'), 'class'=>'form-control']); ?>
			</div>
			<div class="form-group">
				<?= $this->Form->input('password', ['label' => __('パスワード'), 'class'=>'form-control']);?>
			</div>
			<?= $this->Form->end(['label' => __('ログイン'), 'class'=>'btn btn-lg btn-primary btn-block']); ?>
		</div>
	</div>
</div>
