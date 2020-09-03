<?php echo $this->element('admin_menu');?>
<div class="admin-courses-edit full-view">
  <?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>

	<div class="card bg-light rounded shadow m-5">
		<div class="card-header">
			<?php echo ($this->action == 'admin_edit') ? __('カテゴリ編集') :  __('新規カテゴリ'); ?>
		</div>
		<div class="card-body">
			<?php echo $this->Form->create('Category', Configure::read('form_defaults_bs4')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => __('カテゴリ名')));
				echo $this->Form->input('introduction',	array('label' => __('カテゴリ紹介')));
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
