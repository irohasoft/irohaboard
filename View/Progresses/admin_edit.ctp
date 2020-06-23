<?php echo $this->element('admin_menu');?>
<div class="admin-progress-edit full-view">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
	<div class="card bg-light">
		<div class="card-header">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規成果発表'); ?>
		</div>
		<div class="card-body">
			<?php echo $this->Form->create('Progress', Configure::read('form_defaults_bs4')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => __('タイトル')));
				/*
				echo $this->Form->input('opened',	array(
					'type' => 'datetime',
					'dateFormat' => 'YMD',
					'monthNames' => false,
					'timeFormat' => '24',
					'separator' => ' - ',
					'label'=> '公開日時',
					'style' => 'width:initial; display: inline;'
				));
				*/
				// echo $this->Form->input('status',	array(
				// 	'type' => 'radio',
				// 	'before' => '<label class="col col-sm-3 control-label">ステータス</label>',
				// 	'after' => '<span class="status-exp">　非公開と設定した場合、管理者権限でログインした場合のみ表示されます。</span>',
				// 	'separator' => '　',
				// 	'legend' => false,
				// 	'class' => false,
				// 	'default' => 1,
				// 	'options' => Configure::read('content_status')
				// 	)
				// );
				echo $this->Form->input('introduction',	array(
          'label' => __('成果発表紹介'),
          'type' => 'textarea'
        ));
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
