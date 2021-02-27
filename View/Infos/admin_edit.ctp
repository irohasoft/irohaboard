<?= $this->element('admin_menu');?>
<?= $this->Html->css( 'select2.min.css');?>
<?= $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(['inline' => false]); ?>
	$(function (e) {
		$('#GroupGroup').select2({placeholder:   "<?= __('選択しない場合、全てのユーザが対象となります。')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
	});
<?php $this->Html->scriptEnd(); ?>

<div class="admin-infos-edit">
<?= $this->Html->link(__('<< 戻る'), ['action' => 'index'])?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= $this->isEditPage() ? __('編集') :  __('新規お知らせ'); ?>
		</div>
		<div class="panel-body">
		<?php
			echo $this->Form->create('Info', Configure::read('form_defaults'));
			echo $this->Form->input('id');
			echo $this->Form->input('title',	['label' => __('タイトル')]);
			echo $this->Form->input('body',		['label' => __('本文')]);
			echo $this->Form->input('Group',	['label' => __('対象グループ'),	'size' => 20]);
			echo Configure::read('form_submit_before')
				.$this->Form->submit(__('保存'), Configure::read('form_submit_defaults'))
				.Configure::read('form_submit_after');
			echo $this->Form->end();
		?>
		</div>
	</div>
</div>
