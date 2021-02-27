<div class="users-login">
	<div class="panel panel-info form-signin">
		<div class="panel-heading">
			<?= __('受講者ログイン')?>
		</div>
		<div class="panel-body">
			<?php if(Configure::read('show_admin_link')) {?>
			<div class="text-right"><a href="<?= Router::url(['action' => 'login', 'admin' => true]) ?>"><?= __('管理者ログインへ')?></a></div>
			<?php }?>
			<?= $this->Flash->render('auth'); ?>
			<?= $this->Form->create('User'); ?>
			
			<div class="form-group">
				<?= $this->Form->input('username', ['label' => __('ログインID'), 'class'=>'form-control', 'value' => $username]); ?>
			</div>
			<div class="form-group">
				<?= $this->Form->input('password', ['label' => __('パスワード'), 'class'=>'form-control', 'value' => $password]);?>
				<input type="checkbox" name="data[User][remember_me]" checked="checked" value="1" id="remember_me"><?= __('ログイン状態を保持')?>
				<?= $this->Form->unlockField('remember_me'); ?>
			</div>
			<?= $this->Form->end(['label' => __('ログイン'), 'class'=>'btn btn-lg btn-primary btn-block']); ?>
		</div>
	</div>
</div>
