<?php echo $this->element('admin_menu');?>
<?php $this->start('css-embedded'); ?>
	<style>
		#sortable-table tbody
		{
			cursor: move;
		}
	</style>
<?php $this->end(); ?>
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
						console.log(this);
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

<div class="contents index">
	<div class="ib-breadcrumb">
	<?php
		$this->Html->addCrumb('コース一覧', array('controller' => 'courses', 'action' => 'index'));
		$this->Html->addCrumb(h($course['Course']['title']));

		echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	<div class="ib-page-title"><?php echo __('コンテンツ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add', $course['Course']['id'])) ?>'">+ 追加</button>
	</div>
	<div class="alert alert-warning">ドラッグアンドドロップでコンテンツの並び順が変更できます。</div>
	<table id='sortable-table'>
	<thead>
	<tr>
		<th>コンテンツ名</th>
		<th>コンテンツ種別</th>
		<th class="ib-col-date">作成日時</th>
		<th class="ib-col-date">更新日時</th>
		<th class="ib-col-action"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($contents as $content): ?>
	<tr>
		<td>
			<?php
				if($content['Content']['kind'] == 'test')
				{
					echo $this->Html->link($content['Content']['title'], array('controller' => 'contents_questions', 'action' => 'index', $content['Content']['id']));
				}
				else
				{
					echo h($content['Content']['title']);
				}
				echo $this->Form->hidden('id', array('id'=>'', 'class'=>'content_id', 'value'=>$content['Content']['id']));
			?>
		</td>
		<td><?php echo h(Configure::read('content_kind.'.$content['Content']['kind'])); ?>&nbsp;</td>
		<td class="ib-col-date"><?php echo Utils::getYMDHN($content['Content']['created']); ?>&nbsp;</td>
		<td class="ib-col-date"><?php echo Utils::getYMDHN($content['Content']['modified']); ?>&nbsp;</td>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $course['Course']['id'], $content['Content']['id'])) ?>'">編集</button>
			<?php
			if($loginedUser['role']=='admin')
			{
				echo $this->Form->postLink(__('削除'),
					array('action' => 'delete', $content['Content']['id']),
					array('class'=>'btn btn-danger'),
					__('[%s] を削除してもよろしいですか?', $content['Content']['title'])
				);
			}?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
