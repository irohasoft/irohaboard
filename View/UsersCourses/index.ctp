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
<div class="courses index">
	<div class="panel panel-success">
		<div class="panel-heading"><?php echo __('お知らせ'); ?></div>
		<div class="panel-body">
			<?php if($info!=""){?>
			<div class="well">
				<?php
				$info = $this->Text->autoLinkUrls($info, array( 'target' => '_blank'));
				$info = nl2br($info);
				echo $info;
				?>
			</div>
			<?php }?>
			
			<?php if(count($infos) > 0){?>
			<table cellpadding="0" cellspacing="0">
			<tbody>
			<?php foreach ($infos as $info): ?>
			<tr>
				<td width="100" valign="top"><?php echo h(Utils::getYMD($info['Info']['created'])); ?></td>
				<td><?php echo $this->Html->link($info['Info']['title'], array('controller' => 'infos', 'action' => 'view', $info['Info']['id'])); ?></td>
			</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
			<div class="text-right"><a href="<?php echo Router::url(array('controller' => 'infos', 'action' => 'index'));?>">一覧を表示</a></div>
			<?php }?>
			
			<?php echo $no_info;?>
		</div>
	</div>
	<div class="panel panel-info">
	<div class="panel-heading"><?php echo __('コース一覧'); ?></div>
	<div class="panel-body">
		<ul class="list-group">
		<?php foreach ($courses as $course): ?>
		<?php //debug($course)?>
			<a href="<?php echo Router::url(array('controller' => 'contents', 'action' => 'index', $course['Course']['id']));?>" class="list-group-item">
				<?php if($course[0]['left_cnt']!=0){?>
				<button type="button" class="btn btn-danger btn-rest">残り <span class="badge"><?php echo h($course[0]['left_cnt']); ?></span></button>
				<?php }?>
				<h4 class="list-group-item-heading"><?php echo h($course['Course']['title']);?></h4>
				<p class="list-group-item-text">
					<span>学習開始日: <?php echo h($course['Record']['first_date']); ?></span>
					<span>最終学習日: <?php echo h($course['Record']['last_date']); ?></span>
				</p>
			</a>
		<?php endforeach; ?>
		<?php echo $no_record;?>
		</ul>
	</div>
	</div>
</div>
