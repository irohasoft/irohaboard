<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css( 'select2.min.css');?>
<?php echo $this->Html->script( 'select2.min.js');?>

<div class="infos form">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規グループ'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Info', Configure::read('form_defaults')); ?>
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

			?>
			<div class="form-group">
				<div class="col col-md-9 col-md-offset-3">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
		</div>
	</div>
</div>
