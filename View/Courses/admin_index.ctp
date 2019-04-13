<?php echo $this->element('admin_menu');?>
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
					url: "<?php echo Router::url(array('action' => 'order')) ?>",
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
	<div class="ib-page-title"><?php echo __('コース一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>

	<div class="alert alert-warning">ドラッグアンドドロップでコースの並び順が変更できます。</div>
	<table id='sortable-table'>
	<thead>
	<tr>
		<th><?php echo __('コース名'); ?></th>
		<th class="ib-col-datetime"><?php echo __('作成日時'); ?></th>
		<th class="ib-col-datetime"><?php echo __('更新日時'); ?></th>
		<th class="ib-col-action"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($courses as $course): ?>
	<tr>
		<td>
			<?php 
				echo $this->Html->link($course['Course']['title'], array('controller' => 'contents', 'action' => 'index', $course['Course']['id']));
				echo $this->Form->hidden('id', array('id'=>'', 'class'=>'course_id', 'value'=>$course['Course']['id']));
			?>
		</td>
		<td class="ib-col-date"><?php echo h(Utils::getYMDHN($course['Course']['created'])); ?>&nbsp;</td>
		<td class="ib-col-date"><?php echo h(Utils::getYMDHN($course['Course']['modified'])); ?>&nbsp;</td>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $course['Course']['id'])) ?>'">編集</button>
			<?php
			if($loginedUser['role']=='admin')
			{
				echo $this->Form->postLink(__('削除'),
					array('action' => 'delete', $course['Course']['id']),
					array('class'=>'btn btn-danger'),
					__('[%s] を削除してもよろしいですか?', $course['Course']['title'])
				);
			}?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
