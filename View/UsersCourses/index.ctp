<?php echo $this->element('menu');?>
<?php echo $this->Html->css('user_course');?>
<div class="users-courses-index full-view">

	<div class = "attendance-block mb-4">
	  <div class = "attendance-info">
	    <div class = "attendance-date-block">
			<div class = "attendance-date">
	       <?php foreach($user_info as $row):?>
	         <div class = "date">
	           <?php
	             $class_date = (new DateTime($row['Date']['date']))->format('m月d日');
							 if(strtotime($row['Date']['date']) >= strtotime(date('Y-m-d'))){
								 $attendance_id = $row['Attendance']['id'];
								 echo $this->Html->link($class_date, array('controller' => 'attendances', 'action' => 'edit', $attendance_id));
							 }else{
	             	 echo h($class_date);
						 	 }
	           ?>
	         </div>
					<?php endforeach;?>
			</div>
			<div class = "attendance-status">
				<?php foreach($user_info as $row):?>
					<div class = "status">
	          <?php
							if(strtotime($row['Date']['date']) >= strtotime(date('Y-m-d'))){
								switch($row['Attendance']['status']){
    							case 0:
        						echo __("欠席");
        						break;
									case 1:
										echo __("出席済");
										break;
    							case 3:
        						echo __("遅刻");
        						break;
    							case 4:
        						echo __("早退");
        						break;
									case 5:
										echo __("時限変更");
										break;
									default:
										echo __("出席予定");
										break;
								}
							}else{
	            	if($row['Attendance']['status'] != 1){
	              	echo h('欠席');
	            	}else{
	              	echo h('出席');
	            	}
							}
	          ?>
	        </div>
				<?php endforeach;?>
			</div>
			</div>
		</div>
	</div>

	<?php if(count($infos) > 0){?>
		<div class="card bg-light mb-4">
			<div class="card-body">
				<table cellpadding="0" cellspacing="0">
					<tbody>
						<?php foreach ($infos as $an_info): ?>
							<tr>
								<td><?php echo $this->Html->link($an_info['Info']['title'], array('controller' => 'infos', 'action' => 'view', $an_info['Info']['id'])); ?></td>
								<td width="150" valign="top"><?php echo (new DateTime($an_info['Info']['created']))->format('Y年m月d日'); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<div class="text-right"><?php echo $this->Html->link(__('一覧を表示'), array('controller' => 'infos', 'action' => 'index')); ?></div>
			</div>
		</div>
	<?php }?>

	<div class="card bg-light mb-4">
		<div class="card-header"><?php echo __('全体のお知らせ'); ?></div>
		<div class="card-body">
			<?php if($info!=""){?>
				<div class="mb-4">
					<?php
						$info = $this->Text->autoLinkUrls($info, array( 'target' => '_blank'));
						$info = nl2br($info);
						echo $info;
					?>
				</div>
			<?php } else {
				echo $no_info;
			}?>
		</div>
	</div>

	<?php if($next_goal){?>
	<div class="card border-light">
  	<div class="card-header">次回の授業に来る時までに達成するゴール</div>
  	<div class="card-body">
    	<?php echo h($next_goal); ?>
  	</div>
	</div>
	<?php }?>

	<div class="card border-light">
		<div class="card-header"><?php echo __('コース一覧'); ?></div>
		<div class="card-body">
			<ul class="list-group">
				<?php foreach ($courses as $course): ?>
				<?php //debug($course)?>
				<a href="<?php echo Router::url(array('controller' => 'contents', 'action' => 'index', $course['Course']['id']));?>" class="list-group-item">
					<?php if($course[0]['left_cnt']!=0){?>
						<button type="button" class="btn btn-danger btn-rest"><?php echo __('残り')?> <span class="badge"><?php echo h($course[0]['left_cnt']); ?></span></button>
					<?php }?>
					<h4 class="list-group-item-heading"><?php echo h($course['Course']['title']);?></h4>
					<p class="list-group-item-text">
						<span><?php echo __('学習開始日').': '.Utils::getYMD($course['Record']['first_date']); ?></span>
						<span><?php echo __('最終学習日').': '.Utils::getYMD($course['Record']['last_date']); ?></span>
					</p>
				</a>
				<?php endforeach; ?>
				<?php echo $no_record;?>
			</ul>
		</div>
	</div>

</div>
