<?php //echo $this->element('menu');?>
<?php $this->start('css-embedded'); ?>
<style>
.btn-rest
{
	float: right;
}

@media only screen and (max-width:800px)
{
	a
	{
		display: block;
	}
	
	.list-group-item-text span
	{
		display: block;
	}
}
</style>
<?php $this->end(); ?>
<div class="usersCourses index">
	<div class="panel panel-success">
		<div class="panel-heading"><?php echo __('お知らせ'); ?></div>
		<div class="panel-body">
			<?php if($info['Setting']['setting_value']!=""){?>
			<div class="well">
				<?php
				$info = $info['Setting']['setting_value'];
				$info = $this->Text->autoLinkUrls($info);
				$info = nl2br($info);
				echo $info;
				?>
			</div>
			<?php }?>
			<table cellpadding="0" cellspacing="0">
			<tbody>
			<?php foreach ($infos as $info): ?>
			<tr>
				<td width="100" valign="top"><?php echo h(Utils::getYMD($info['Info']['created'])); ?>&nbsp;</td>
				<td><?php echo $this->Html->link($info['Info']['title'], array('controller' => 'infos', 'action' => 'view', $info['Info']['id'])); ?>&nbsp;</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		</div>
	</div>
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
					<span>学習開始日: <?php echo h($usersCourse['Record']['first_date']); ?></span>
					<span>最終学習日: <?php echo h($usersCourse['Record']['last_date']); ?></span>
				</p>
			</a>
		<?php endforeach; ?>
		<?php echo $no_records;?>
		</ul>
	</div>
	</div>
</div>
