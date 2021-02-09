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

				$('.course_id').each(function(index)
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
<div class="admin-courses-index">
	<div class="ib-page-title"><?= __('コース一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?= Router::url(['action' => 'add']) ?>'">+ 追加</button>
	</div>

	<div class="alert alert-warning"><?= __('ドラッグアンドドロップでコースの並び順が変更できます。'); ?></div>
	<table id='sortable-table'>
	<thead>
	<tr>
		<th><?= __('コース名'); ?></th>
		<th class="ib-col-datetime"><?= __('作成日時'); ?></th>
		<th class="ib-col-datetime"><?= __('更新日時'); ?></th>
		<th class="ib-col-action"><?= __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($courses as $course): ?>
	<tr>
		<td>
		<?php 
			echo $this->Html->link($course['Course']['title'], ['controller' => 'contents', 'action' => 'index', $course['Course']['id']]);
			echo $this->Form->hidden('id', ['id'=>'', 'class'=>'course_id', 'value'=>$course['Course']['id']]);
		?>
		</td>
		<td class="ib-col-date"><?= h(Utils::getYMDHN($course['Course']['created'])); ?>&nbsp;</td>
		<td class="ib-col-date"><?= h(Utils::getYMDHN($course['Course']['modified'])); ?>&nbsp;</td>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success" onclick="location.href='<?= Router::url(['action' => 'edit', $course['Course']['id']]) ?>'"><?= __('編集')?></button>
			<?php if($loginedUser['role'] == 'admin') {?>
			<?= $this->Form->postLink(__('削除'), ['action' => 'delete', $course['Course']['id']], ['class'=>'btn btn-danger'], 
				__('[%s] を削除してもよろしいですか?', $course['Course']['title']));?>
			<?php }?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
