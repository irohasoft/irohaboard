<?php echo $this->element('menu');?>
<?php echo $this->Html->css('attendance');?>
<div class="container-fluid">
  <div class="row">
     <div class="col"><?php echo $this->Html->link(__('<< 戻る'), array('controller' => 'users_courses', 'action' => 'index'))?></div>
  </div>
	<div class="row">
	   <div class="col"><h2><?php echo __("出欠連絡");?></h2></div>
	</div>
  <div class="row">
     <div class="col"><h2><?php echo h($attendance_date);?></h2></div>
  </div>
  <?php
    echo $this->Form->create();
    echo $this->Form->input('status', array(
      'label'    => __('出欠席'),
      'options'  => Configure::read('attendance_status_for_edit'),
      'selected' => $attendance_status
    ));
    echo $this->Form->input('reason', array(
      'label' => __('理由'),
      'type' => 'textarea',
      'value' => $attendance_reason,
      'div' => false,
      'class' => '',
      'style' => ''
    ));
    echo $this->Form->submit(__('保存'), array(
      'class' => 'btn btn-info',
      'div' => false
    ));
    echo $this->Form->end();
  ?>
</div>
