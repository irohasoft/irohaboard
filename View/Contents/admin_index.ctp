<?= $this->element('admin_menu');?>
<?php $this->start('script-embedded'); ?>
<script>
	$(function(){
		$('#sortable-table tbody').sortable(
		{
			helper: function(event, ui)
			{
				var children = ui.children();
				var clone = ui.clone();

				clone.children().each(function(index)
				{
					$(this).width(children.eq(index).width());
				});
				return clone;
			},
			update: function(event, ui)
			{
				var id_list = new Array();

				$('.content_id').each(function(index)
				{
					id_list[id_list.length] = $(this).val();
				});

				$.ajax({
					url: "<?= Router::url(['action' => 'order']) ?>",
					type: "POST",
					data: { id_list : id_list },
					dataType: "text",
					success : function(response){
						//通信成功時の処理
						//alert(response);
					},
					error: function(){
						//通信失敗時の処理
						//alert('通信失敗');
					}
				});
			},
			cursor: "move",
			opacity: 0.5
		});
	});
</script>
<?php $this->end(); ?>

<div class="admin-contents-index">
	<div class="ib-breadcrumb">
	<?php
		$this->Html->addCrumb(__('コース一覧'), ['controller' => 'courses', 'action' => 'index']);
		$this->Html->addCrumb(h($course['Course']['title']));

		echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	<div class="ib-page-title"><?= __('コンテンツ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?= Router::url(['action' => 'add', $course['Course']['id']]) ?>'">+ 追加</button>
	</div>
	<div class="alert alert-warning"><?= __('ドラッグアンドドロップでコンテンツの並び順が変更できます。'); ?></div>
	<table id='sortable-table'>
	<thead>
	<tr>
		<th><?= __('コンテンツ名'); ?></th>
		<th nowrap><?= __('コンテンツ種別'); ?></th>
		<th class="text-center"><?= __('ステータス'); ?></th>
		<th class="ib-col-date"><?= __('作成日時'); ?></th>
		<th class="ib-col-date"><?= __('更新日時'); ?></th>
		<th class="ib-col-action"><?= __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($contents as $content): ?>
	<?php
		switch($content['Content']['kind'])
		{
			case 'test':
				$title = $this->Html->link($content['Content']['title'], ['controller' => 'contents_questions', 'action' => 'index', $content['Content']['id']]);
				break;
			default :
				$title = h($content['Content']['title']);
				break;
		}
	?>
	<tr>
		<td><?= $title; ?></td>
		<td><?= h(Configure::read('content_kind.'.$content['Content']['kind'])); ?>&nbsp;</td>
		<td class="text-center"><?= h(Configure::read('content_status.'.$content['Content']['status'])); ?>&nbsp;</td>
		<td class="ib-col-date"><?= Utils::getYMDHN($content['Content']['created']); ?>&nbsp;</td>
		<td class="ib-col-date"><?= Utils::getYMDHN($content['Content']['modified']); ?>&nbsp;</td>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success" onclick="location.href='<?= Router::url(['action' => 'edit', $course['Course']['id'], $content['Content']['id']]) ?>'"><?= __('編集')?></button>
			<button type="button" class="btn btn-info" onclick="location.href='<?= Router::url(['action' => 'copy', $course['Course']['id'], $content['Content']['id']]) ?>'"><?= __('複製')?></button>
			<?php if($loginedUser['role'] == 'admin') {?>
			<?= $this->Form->postLink(__('削除'), ['action' => 'delete', $content['Content']['id']], ['class'=>'btn btn-danger'], 
				__('[%s] を削除してもよろしいですか?', $content['Content']['title']));?>
			<?php }?>
			<?= $this->Form->hidden('id', ['id'=>'', 'class'=>'content_id', 'value'=>$content['Content']['id']]);?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
