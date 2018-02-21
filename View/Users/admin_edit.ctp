<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css( 'select2.min.css');?>
<?php echo $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
	$(function (e) {
		$('#GroupGroup').select2({placeholder:   "所属するグループを選択して下さい。(複数選択可)", closeOnSelect: <?php echo (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		$('#CourseCourse').select2({placeholder: "受講するコースを選択して下さい。(複数選択可)", closeOnSelect: <?php echo (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		// パスワードの自動復元を防止
		setTimeout('$("#UserNewPassword").val("");',100);
	});
<?php $this->Html->scriptEnd(); ?>
<div class="users form">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規ユーザ'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('User', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('username',				array('label' => 'ログインID'));
				echo $this->Form->input('User.new_password',	array('label' => 'パスワード', 'type' => 'password', 'autocomplete' => 'off'));
				echo $this->Form->input('name',					array('label' => '氏名'));
				
				// root アカウント、もしくは admin 権限以外の場合、権限変更を許可しない
				$disabled = (($username == 'root')||($loginedUser['role']!='admin'));
				
				echo $this->Form->input('role',	array(
					'type' => 'radio',
					'before' => '<label class="col col-md-3 col-sm-4 control-label">権限</label>',
					'separator'=>"　", 
					'disabled'=>$disabled, 
					'legend' => false,
					'class' => false,
					'options' => Configure::read('user_role')
					)
				);
				
				echo $this->Form->input('email',				array('label' => 'メールアドレス'));
				echo $this->Form->input('comment',				array('label' => '備考'));
				echo $this->Form->input('Group',				array('label' => '所属グループ',	'size' => 20));
				echo $this->Form->input('Course',				array('label' => '受講コース',		'size' => 20));
			?>
			<div class="form-group">
				<div class="col col-md-9 col-md-offset-3">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
