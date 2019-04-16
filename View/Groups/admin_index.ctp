<?php echo $this->element('admin_menu');?>
<div class="admin-groups-index">
	<div class="ib-page-title"><?php echo __('グループ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>
	
	<table>
	<thead>
	<tr>
		<th><?php echo $this->Paginator->sort('title', 'グループ名'); ?></th>
		<th nowrap class="col-course"><?php echo __('受講コース'); ?></th>
		<th class="ib-col-date"><?php echo $this->Paginator->sort('created', '作成日時'); ?></th>
		<th class="ib-col-date"><?php echo $this->Paginator->sort('modified', '更新日時'); ?></th>
		<th class="ib-col-action"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($groups as $group): ?>
	<tr>
		<td><?php echo h($group['Group']['title']); ?></td>
		<td><div class="reader" title="<?php echo h($group['GroupCourse']['course_title']); ?>"><p><?php echo h($group['GroupCourse']['course_title']); ?>&nbsp;</p></div></td>
		<td class="ib-col-date"><?php echo h(Utils::getYMDHN($group['Group']['created'])); ?>&nbsp;</td>
		<td class="ib-col-date"><?php echo h(Utils::getYMDHN($group['Group']['modified'])); ?>&nbsp;</td>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $group['Group']['id'])) ?>'">編集</button>
			<?php echo $this->Form->postLink(__('削除'), 
					array('action' => 'delete', $group['Group']['id']), 
					array('class'=>'btn btn-danger'), 
					__('[%s] を削除してもよろしいですか?', $group['Group']['title'])
			); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?php echo $this->element('paging');?>
</div>
