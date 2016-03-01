<?php echo $this->element('menu');?>
<div class="users form">
	<div class="panel panel-default">
		<div class="panel-heading">
			設定
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('User'); ?>
			<div class="form-group">
				<label for="vs1" class="control-label col-sm-2">パスワード</label>
				<div class="col-sm-8">
					<?php 
					echo $this->Form->input('User.new_password', 
					array(
						'label' => false,
						'div' => false,
						'type' => 'password',
						'autocomplete' => 'off',
						'class' => 'form-control'
					));
					?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-8">
					<?php echo $this->Form->end(array('label' => '保存', 'class'=>'btn btn-default')); ?>
				</div>
			</div>
		</div>
	</div>
</div>