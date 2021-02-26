<?= $this->element('admin_menu');?>
<div class="admin-courses-edit">
<?= $this->Html->link(__('<< 戻る'), ['action' => 'index'])?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= $this->isEditPage() ? __('編集') :  __('新規コース'); ?>
		</div>
		<div class="panel-body">
		<?php
			echo $this->Form->create('Course', Configure::read('form_defaults'));
			echo $this->Form->input('id');
			echo $this->Form->input('title',	['label' => __('コース名')]);
			echo $this->Form->input('introduction',	['label' => __('コース紹介')]);
			echo $this->Form->input('comment',		['label' => __('備考')]);
			echo Configure::read('form_submit_before')
				.$this->Form->submit(__('保存'), Configure::read('form_submit_defaults'))
				.Configure::read('form_submit_after');
			echo $this->Form->end();
		?>
		</div>
	</div>
</div>