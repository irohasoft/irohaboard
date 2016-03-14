<?php echo $this->element('admin_menu');?>
<div class="users index">
	<div class="ib-page-title"><?php echo __('ユーザ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add"
			onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>
	<table>
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('username', 'ログインID'); ?></th>
				<th><?php echo $this->Paginator->sort('name', '氏名'); ?></th>
				<th><?php echo $this->Paginator->sort('role', 'ロール'); ?></th>
				<th><?php echo $this->Paginator->sort('email', 'メールアドレス'); ?></th>
				<th><?php echo $this->Paginator->sort('UserGroup.group_count', '所属グループ数'); ?></th>
				<th><?php echo $this->Paginator->sort('UserCourse.course_count', '受講コース数'); ?></th>
				<th class="ib-col-datetime"><?php echo $this->Paginator->sort('last_logined', '最終ログイン日時'); ?></th>
				<th class="ib-col-datetime"><?php echo $this->Paginator->sort('created', '作成日時'); ?></th>
				<th class="ib-col-action"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['name']); ?></td>
		<td><?php echo h(Configure::read('user_role.'.$user['User']['role'])); ?>&nbsp;</td>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($user['UserGroup']['group_count']); ?>&nbsp;</td>
		<td><?php echo h($user['UserCourse']['course_count']); ?>&nbsp;</td>
		<td class="ib-col-datetime"><?php echo h(Utils::getYMDHN($user['User']['last_logined'])); ?>&nbsp;</td>
		<td class="ib-col-datetime"><?php echo h(Utils::getYMDHN($user['User']['created'])); ?>&nbsp;</td>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success"
				onclick="location.href='<?php echo Router::url(array('action' => 'edit', $user['User']['id'])) ?>'">編集</button>
			<?php

echo $this->Form->postLink(__('削除'), array(
				'action' => 'delete',
				$user['User']['id']
		), array(
				'class' => 'btn btn-danger'
		), __('[%s] を削除してもよろしいですか?', $user['User']['name']));
		?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
</div>