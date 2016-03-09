<?php echo $this->element('menu');?>
<div class="usersCourses index">
	<?php if($info['Setting']['setting_value']!=""){?>
	<div class="well">
		<p><?php
			$info = $info['Setting']['setting_value'];
			$info = $this->Text->autoLinkUrls($info);
			$info = nl2br($info);
			echo $info?>
		</p>
	</div>
	<?}?>
	<div class="panel panel-info">
	<div class="panel-heading"><?php echo __('コース一覧'); ?></div>
	<div class="panel-body">
		<ul class="list-group">
		<?php foreach ($usersCourses as $usersCourse): ?>
		<?php //debug($usersCourse)?>
			<a href="<?php echo Router::url(array('controller' => 'contents', 'action' => 'index', $usersCourse['Course']['id']));?>" class="list-group-item">
				<?php if($usersCourse[0]['left_cnt']!=0){?>
				<button type="button" class="btn btn-danger btn-rest">残り <span class="badge"><?php echo h($usersCourse[0]['left_cnt']); ?></span></button>
				<?php }?>
				<h4 class="list-group-item-heading"><?php echo h($usersCourse['Course']['title']);?></h4>
				<p class="list-group-item-text">
					学習開始日: <?php echo h($usersCourse['Record']['first_date']); ?>
					最終学習日: <?php echo h($usersCourse['Record']['last_date']); ?>
				</p>
			</a>
		<?php endforeach; ?>
		<?php echo $no_records;?>
		</ul>
	</div>
	</div>
</div>
<style>
	.btn-rest
	{
		float: right;
	}
	a
	{
		display: block;
	}

</style>