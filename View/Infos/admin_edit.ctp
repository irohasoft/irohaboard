<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('select2.min.css');?>
<?php echo $this->Html->css('summernote.css');?>
<?php echo $this->Html->script('select2.min.js');?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
	$(function (e) {
		$('#GroupGroup').select2({placeholder:   "選択しない場合、全てのユーザが対象となります。", closeOnSelect: <?php echo (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
	});
<?php $this->Html->scriptEnd(); ?>
<?php $this->start('script-embedded'); ?>
<?php echo $this->Html->script('summernote.min.js');?>
<?php echo $this->Html->script('lang/summernote-ja-JP.js');?>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
<script>
	$(document).ready(function()
	{
		init();
	});

	function init()
	{
		// リッチテキストエディタを起動
		CommonUtil.setRichTextEditor('#InfoBody', <?php echo (Configure::read('use_upload_image') ? 'true' : 'false')?>, '<?php echo $this->webroot ?>');
	}
</script>
<?php $this->end(); ?>

<div class="admin-infos-edit full-view">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
	<div class="card bg-light">
		<div class="card-header">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規お知らせ'); ?>
		</div>
		<div class="card-body">
			<?php echo $this->Form->create('Info', Configure::read('form_defaults_bs4')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => __('タイトル')));
				echo $this->Form->input('body',		array('label' => __('本文')));
				/*
				echo $this->Form->input('opened', array(
					'type' => 'date',
					'dateFormat' => 'YMD',
					'monthNames' => false,
					'timeFormat' => '24',
					'separator' => ' / ',
					'label'=> '公開開始日 : ',
					'style' => 'width:initial; display: inline;'
				));

				echo $this->Form->input('closed', array(
					'type' => 'date',
					'dateFormat' => 'YMD',
					'monthNames' => false,
					'timeFormat' => '24',
					'separator' => ' / ',
					'label'=> '公開終了日 : ',
					'style' => 'width:initial; display: inline;'
				));
				*/
				echo $this->Form->input('Group',	array('label' => '対象グループ',	'size' => 20));

			?>
			<div class="form-group">
				<div class="col col-sm-12 col-sm-offset-3">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
