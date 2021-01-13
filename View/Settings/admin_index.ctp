<?= $this->element('admin_menu');?>
<?php $this->start('script-embedded'); ?>
<script>
	$(document).ready(function()
	{
		$('option').each(function(){
			console.log($(this).val());
			$(this).css('color',		'white');
			$(this).css('background',	$(this).val());
			$(this).css('font-weight',	'bold');
		});
	});
</script>
<?php $this->end(); ?>
<div class="admin-settings-index">
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= __('システム設定'); ?>
		</div>
		<div class="panel-body">
			<?= $this->Form->create('Setting', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('title',		array('label' => __('システム名'),		'value'=>$settings['title']));
				echo $this->Form->input('copyright',	array('label' => __('コピーライト'),	'value'=>$settings['copyright']));
				echo $this->Form->input('color',		array('label' => __('テーマカラー'),	'options'=>$colors, 'selected'=>$settings['color']));
				echo $this->Form->input('information',	array('label' => __('全体のお知らせ'),	'value'=>$settings['information'], 'type' => 'textarea'));
			?>
			<div class="form-group">
				<div class="col col-sm-9 col-sm-offset-3">
					<?= $this->Form->end(array('label' => __('保存'), 'class' => 'btn btn-primary')); ?>
				</div>
			</div>
			<?= $this->Form->end(); ?>
		</div>
	</div>
</div>
