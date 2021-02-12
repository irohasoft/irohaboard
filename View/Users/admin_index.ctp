<?= $this->element('admin_menu');?>
<div class="admin-users-index">
	<div class="ib-page-title"><?= __('ユーザ一覧'); ?></div>
	<div class="buttons_container">
		<?php if($loginedUser['role'] == 'admin') { ?>
		<button type="button" class="btn btn-primary btn-export" onclick="location.href='<?= Router::url(['action' => 'export']) ?>'">エクスポート</button>
		<button type="button" class="btn btn-primary btn-import" onclick="location.href='<?= Router::url(['action' => 'import']) ?>'">インポート</button>
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?= Router::url(['action' => 'add']) ?>'">+ 追加</button>
		<?php }?>
	</div>
	<div class="ib-horizontal">
	<?php
		echo $this->Form->create('User');
		echo $this->Form->searchField('group_id', [
			'label'    => __('グループ'),
			'options'  => $groups, 
			'selected' => $group_id, 
			'empty'    => '全て', 
			'onchange' => 'submit(this.form);'
		]);
		echo $this->Form->searchField('username',		['label' => __('ログインID')]);
		echo $this->Form->searchField('name',			['label' => __('氏名')]);
		echo $this->Form->submit(__('検索'),	['class' => 'btn btn-info btn-add']);
		echo $this->Form->end();
	?>
	</div>
	<table>
	<thead>
	<tr>
		<th nowrap><?= $this->Paginator->sort('username', __('ログインID')); ?></th>
		<th nowrap class="col-width"><?= $this->Paginator->sort('name', __('氏名')); ?></th>
		<th nowrap><?= $this->Paginator->sort('role', '権限'); ?></th>
		<th nowrap><?= __('所属グループ'); ?></th>
		<th nowrap class="ib-col-datetime"><?= __('受講コース'); ?></th>
		<th class="ib-col-datetime"><?= $this->Paginator->sort('last_logined', __('最終ログイン日時')); ?></th>
		<th class="ib-col-datetime"><?= $this->Paginator->sort('created', __('作成日時')); ?></th>
		<?php if($loginedUser['role'] == 'admin') {?>
		<th class="ib-col-action"><?= __('Actions'); ?></th>
		<?php }?>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($users as $user): ?>
	<tr>
		<td><?= h($user['User']['username']); ?>&nbsp;</td>
		<td><?= h($user['User']['name']); ?></td>
		<td nowrap><?= h(Configure::read('user_role.'.$user['User']['role'])); ?>&nbsp;</td>
		<td><div class="reader" title="<?= h($user[0]['group_title']); ?>"><p><?= h($user[0]['group_title']); ?>&nbsp;</p></td>
		<td><div class="reader" title="<?= h($user[0]['course_title']); ?>"><p><?= h($user[0]['course_title']); ?>&nbsp;</p></div></td>
		<td class="ib-col-datetime"><?= h(Utils::getYMDHN($user['User']['last_logined'])); ?>&nbsp;</td>
		<td class="ib-col-datetime"><?= h(Utils::getYMDHN($user['User']['created'])); ?>&nbsp;</td>
		<?php if($loginedUser['role'] == 'admin') {?>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success" onclick="location.href='<?= Router::url(['action' => 'edit', $user['User']['id']]) ?>'"><?= __('編集')?></button>
			<?= $this->Form->postLink(__('削除'), ['action' => 'delete', $user['User']['id']], ['class' => 'btn btn-danger'],__('[%s] を削除してもよろしいですか?', $user['User']['name']));?>
		</td>
		<?php }?>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?= $this->element('paging');?>
</div>