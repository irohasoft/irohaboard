<?= $this->element('admin_menu');?>
<?= $this->Html->css( 'select2.min.css');?>
<?= $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
	$(function (e) {
		$('#GroupGroup').select2({placeholder:   "<?= __('所属するグループを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		$('#CourseCourse').select2({placeholder: "<?= __('受講するコースを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		// パスワードの自動復元を防止
		setTimeout('$("#UserNewPassword").val("");', 500);
	});
<?php $this->Html->scriptEnd(); ?>
<div class="admin-users-edit">
<?= $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= ($this->request->data) ? __('編集') :  __('新規ユーザ'); ?>
		</div>
		<div class="panel-body">
			<?= $this->Form->create('User', Configure::read('form_defaults')); ?>
			<?php
				$password_label = ($this->request->data) ? __('新しいパスワード') : __('パスワード');
				
				echo $this->Form->input('id');
				echo $this->Form->input('username',				array('label' => __('ログインID')));
				echo $this->Form->input('User.new_password',	array('label' => $password_label, 'type' => 'password', 'autocomplete' => 'new-password'));
				echo $this->Form->input('name',					array('label' => __('氏名')));
				
				// root アカウント、もしくは admin 権限以外の場合、権限変更を許可しない
				$disabled = (($username == 'root')||($loginedUser['role']!='admin'));
				
				echo $this->Form->input('role',	array(
					'type' => 'radio',
					'before' => '<label class="col col-sm-3 control-label">権限</label>',
					'separator'=>"　", 
					'disabled'=>$disabled, 
					'legend' => false,
					'class' => false,
					'options' => Configure::read('user_role')
					)
				);
				
				echo $this->Form->input('email',				array('label' => __('メールアドレス')));
				echo $this->Form->input('Group',				array('label' => __('所属グループ'),	'size' => 20));
				echo $this->Form->input('Course',				array('label' => __('受講コース'),		'size' => 20));
				echo $this->Form->input('comment',				array('label' => __('備考')));
			?>
			<div class="form-group">
				<div class="col col-sm-9 col-sm-offset-3">
					<?= $this->Form->submit(__('保存'), Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?= $this->Form->end(); ?>
			<?php
			if($this->request->data)
			{
				echo $this->Form->postLink(__('学習履歴を削除'), array(
					'action' => 'clear',
					$this->request->data['User']['id']
				), array(
					'class' => 'btn btn-default pull-right btn-clear'
				), __('学習履歴を削除してもよろしいですか？', $this->request->data['User']['name']));
			}
			?>
		</div>
	</div>
</div>
