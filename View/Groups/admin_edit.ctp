<?= $this->element('admin_menu');?>
<?= $this->Html->css( 'select2.min.css');?>
<?= $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(['inline' => false]); ?>
	$(function (e) {
		$('#CourseCourse').select2({placeholder: "<?= __('受講するコースを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
	});
<?php $this->Html->scriptEnd(); ?>
<div class="admin-groups-edit">
<?= $this->Html->link(__('<< 戻る'), ['action' => 'index'])?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= $this->isEditPage() ? __('編集') :  __('新規グループ'); ?>
		</div>
		<div class="panel-body">
		<?php
			echo $this->Form->create('Group', Configure::read('form_defaults'));
			echo $this->Form->input('id');
			echo $this->Form->input('title',	['label' => __('グループ名')]);
			echo $this->Form->input('Course',	['label' => __('受講コース'),		'size' => 20]);
			echo $this->Form->input('comment',	['label' => __('備考')]);
			echo Configure::read('form_submit_before')
				.$this->Form->submit(__('保存'), Configure::read('form_submit_defaults'))
				.Configure::read('form_submit_after');
			echo $this->Form->end();
		?>
		</div>
	</div>
</div>