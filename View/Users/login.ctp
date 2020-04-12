<div class="users-login full-view">
	<div class="panel panel-info form-signin">
		<div class="panel-body">
			<?php echo $this->Flash->render('auth'); ?>
			<?php echo $this->Form->create('User'); ?>

			<div class="form-group">
				<?php echo $this->Form->input('username',
					array(
						'label' => array(
							'text'=>__('ログインID'),
							'style'=>'font-size : 20px'
						),
						'class'=>'form-control',
						'value' => $username
					)); ?>
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('password', array(
					'label' => array(
						'text'=>__('パスワード'),
						'style'=>'font-size : 20px'
					),
					'class'=>'form-control',
					'value' => $password
				));?>
				<input type="checkbox" name="data[User][remember_me]" checked="checked" value="1" id="remember_me"><?php echo __('ログイン状態を保持')?>
				<?php echo $this->Form->unlockField('remember_me'); ?>
			</div>
			<?php echo $this->Form->end(array('label' => 'ログイン', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
		</div>
	</div>
</div>
