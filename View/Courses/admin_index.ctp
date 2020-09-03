<?php echo $this->element('admin_menu');?>
<?php $this->start('script-embedded'); ?>
<script>
	var js_arr = <?php echo $json_list;?>;
	js_arr['0'] = "未分類コース";
	var arr_len = Object.keys(js_arr).length;
	var keys = Object.keys(js_arr);
	$(function(){
		for(var i = 0; i < arr_len; i++){
			var id = keys[i];
			$(`#sortable-table-${id} tbody`).sortable(
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
					id = this.id;
					var id_list = new Array();

					$(`.${id}_id`).each(function(index)
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
				receive: function(event, ui) {
    			var group = event.target.id;
    			console.log('receiving group', group);

    			// do something with the group
				},
				cursor: "move",
				opacity: 0.5
			});
		}
		
	});
</script>
<?php $this->end(); ?>
<div class="admin-courses-index full-view rounded shadow-lg m-4 p-3">
	<div class="ib-page-title"><?php echo __('コース一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-outline-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>

	<div class="alert alert-warning">ドラッグアンドドロップでコースの並び順が変更できます。</div>
	
	<div class="accordion">
	<?php foreach($in_category_courses as $category_info):?>
    <div class="card mb-3 shadow rounded-lg">
      <div class="card-header" id='heading-<?php echo $category_info['Category']['id']?>'>
        <h5 class="mb-0">
          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-<?php echo $category_info['Category']['id']?>" aria-expanded="true" aria-controls="collapse-<?php echo $category_info['Category']['id']?>">
            <?php echo $category_info['Category']['title']?>
          </button>
        </h5>
      </div>
  
      <div id="collapse-<?php echo $category_info['Category']['id']?>" class="collapse" aria-labelledby="heading-<?php echo $category_info['Category']['id']?>'">
        <div class="card-body">
          <table id="sortable-table-<?php echo $category_info['Category']['id']?>">
						<thead>
						<tr>
							<th nowrap><?php echo __('コース名'); ?></th>
							<th nowrap class="ib-col-datetime"><?php echo __('作成日時'); ?></th>
							<th nowrap class="ib-col-datetime"><?php echo __('更新日時'); ?></th>
							<th class="ib-col-action"><?php echo __('Actions'); ?></th>
						</tr>
						</thead>
						<tbody id="<?php echo $category_info['Category']['id']?>">
						<?php foreach ($category_info['Course'] as $course): ?>
						<tr>
							<td nowrap>
								<?php
									echo $this->Html->link($course['title'], array('controller' => 'contents', 'action' => 'index', $course['id']));
									echo $this->Form->hidden('id', array('id'=>'', 'class'=>$category_info['Category']['id'].'_id', 'value'=>$course['id']));
								?>
							</td>
							<td nowrap class="ib-col-datetime"><?php echo h(Utils::getYMDHN($course['created'])); ?>&nbsp;</td>
							<td nowrap class="ib-col-datetime"><?php echo h(Utils::getYMDHN($course['modified'])); ?>&nbsp;</td>
							<td class="ib-col-action">
								<?php
								if($loginedUser['role']=='admin')
								{
									echo $this->Form->postLink(__('削除'),
										array('action' => 'delete', $course['id']),
										array('class'=>'btn btn-danger'),
										__('[%s] を削除してもよろしいですか?', $course['title'])
									);
								}?>
								<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $course['id'])) ?>'">編集</button>
							
							</td>
						</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
        </div>
      </div>
      
    </div>
	<?php endforeach;?>

    <div class="card mb-3 shadow rounded-lg">
      <div class="card-header" id='heading-0'>
        <h5 class="mb-0">
          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-0" aria-expanded="true" aria-controls="collapse-0">
            未分類コース
          </button>
        </h5>
      </div>
  
      <div id="collapse-0" class="collapse" aria-labelledby="heading-0">
        <div class="card-body">
          <table id="sortable-table-0">
						<thead>
						<tr>
							<th nowrap><?php echo __('コース名'); ?></th>
							<th nowrap class="ib-col-datetime"><?php echo __('作成日時'); ?></th>
							<th nowrap class="ib-col-datetime"><?php echo __('更新日時'); ?></th>
							<th class="ib-col-action"><?php echo __('Actions'); ?></th>
						</tr>
						</thead>
						<tbody id="0">
						
						<?php foreach ($out_category_courses as $course): ?>
						<?php $this->log($course['Course']);?>
						<tr>
							<td nowrap>
								<?php
									echo $this->Html->link($course['Course']['title'], array('controller' => 'contents', 'action' => 'index', $course['Course']['id']));
									echo $this->Form->hidden('id', array('id'=>'', 'class'=>'0_id', 'value'=>$course['Course']['id']));
								?>
							</td>
							<td nowrap class="ib-col-datetime"><?php echo h(Utils::getYMDHN($course['Course']['created'])); ?>&nbsp;</td>
							<td nowrap class="ib-col-datetime"><?php echo h(Utils::getYMDHN($course['Course']['modified'])); ?>&nbsp;</td>
							<td class="ib-col-action">
								<?php
								if($loginedUser['role']=='admin')
								{
									echo $this->Form->postLink(__('削除'),
										array('action' => 'delete', $course['Course']['id']),
										array('class'=>'btn btn-danger'),
										__('[%s] を削除してもよろしいですか?', $course['Course']['title'])
									);
								}?>
								<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $course['Course']['id'])) ?>'">編集</button>
							
							</td>
						</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
        </div>
      </div>
      
    </div>
  </div>

</div>
