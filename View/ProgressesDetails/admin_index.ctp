<?php echo $this->element('admin_menu');?>
<div class="admin-progress-detail-index full-view">
	<div class="ib-breadcrumb">
	<?php
		$this->Html->addCrumb('成果発表一覧', array('controller' => 'progresses', 'action' => 'index'));
		$this->Html->addCrumb(h($progress_info['Progress']['title']));

		echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	<div class="ib-page-title"><?php echo __('発表作品一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add', $progress_info['Progress']['id'])) ?>'">+ 追加</button>
	</div>
	<!-- <div class="alert alert-warning">ドラッグアンドドロップでコンテンツの並び順が変更できます。</div> -->
	<table>
	<thead>
	<tr>
		<th>タイトル</th>
		<th nowrap>発表者名</th>
		<th class="ib-col-date">作成日時</th>
		<th class="ib-col-action"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($progress_details as $detail): ?>
	<tr>
		<td><?php echo $detail['title']; ?></td>
    <?php $user_id = $detail['user_id'];?>
		<td><?php echo h($user_list[$user_id]); ?>&nbsp;</td>
		<td class="ib-col-date"><?php echo Utils::getYMDHN($detail['created']); ?>&nbsp;</td>
		<td class="ib-col-action">
			<?php
			echo $this->Form->hidden('id', array('id'=>'', 'class'=>'content_id', 'value'=>$detail['id']));

			if($loginedUser['role']=='admin')
			{
				echo $this->Form->postLink(__('削除'),
					array('action' => 'delete', $progress_info['Progress']['id'], $detail['id']),
					array('class'=>'btn btn-danger'),
					__('[%s] を削除してもよろしいですか?', $detail['title'])
				);
			}?>
			<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $detail['progress_id'], $detail['id'])) ?>'">編集</button>


		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>