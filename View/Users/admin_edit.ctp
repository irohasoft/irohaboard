<?= $this->element('admin_menu');?>
<?= $this->Html->css( 'select2.min.css');?>
<?= $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(['inline' => false]); ?>
	$(function (e) {
		$('#GroupGroup').select2({placeholder:   "<?= __('所属するグループを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		$('#CourseCourse').select2({placeholder: "<?= __('受講するコースを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		// パスワードの自動復元を防止
		setTimeout('$("#UserNewPassword").val("");', 500);
	});
<?php $this->Html->scriptEnd(); ?>
<div class="admin-users-edit">
<?= $this->Html->link(__('<< 戻る'), ['action' => 'index'])?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= $this->isEditPage() ? __('編集') :  __('新規ユーザ'); ?>
		</div>
		<div class="panel-body">
		<?php
			echo $this->Form->create('User', Configure::read('form_defaults'));
			
			$password_label = $this->isEditPage() ? __('新しいパスワード') : __('パスワード');
			
			echo $this->Form->input('id');
			echo $this->Form->input('username',				['label' => __('ログインID')]);
			echo $this->Form->input('User.new_password',	['label' => $password_label, 'type' => 'password', 'autocomplete' => 'new-password']);
			echo $this->Form->input('name',					['label' => __('氏名')]);
			
			// root アカウント、もしくは admin 権限以外の場合、権限変更を許可しない
			$disabled = (($username == 'root') || ($loginedUser['role'] != 'admin'));
			
			echo $this->Form->inputRadio('role',	['label' => __('権限'), 'options' => Configure::read('user_role')]);
			
			echo $this->Form->input('email',				['label' => __('メールアドレス')]);
			echo $this->Form->input('Group',				['label' => __('所属グループ')]);
			echo $this->Form->input('Course',				['label' => __('受講コース')]);
			echo $this->Form->input('comment',				['label' => __('備考')]);
			echo Configure::read('form_submit_before')
				.$this->Form->submit(__('保存'), Configure::read('form_submit_defaults'))
				.Configure::read('form_submit_after');
			echo $this->Form->end();
			
			if($this->request->data)
			{
				echo $this->Form->postLink(__('学習履歴を削除'),
					['action' => 'clear', $this->request->data['User']['id']],
					['class' => 'btn btn-default pull-right btn-clear'],
					__('学習履歴を削除してもよろしいですか？', $this->request->data['User']['name']));
			}
		?>
		</div>
	</div>
</div>
