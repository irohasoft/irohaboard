<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('attendance');?>
<div class="container-fluid">
  <div class="row">
     <div class="col"><?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?></div>
  </div>
	<div class="row">
	   <div class="col"><h2><?php echo h($name);?></h2></div>
	</div>
  <div class="row">
     <div class="col"><h2><?php echo h($date);?></h2></div>
  </div>
  <?php
    echo $this->Form->create();
    echo $this->Form->input('status', array(
      'label'    => '出欠席',
      'options'  => Configure::read('attendance_status_for_admin_edit'),
      'selected' => $attendance_status
    ));
    echo $this->Form->input('edited_login_time', array(
      'label'      => '出席時刻',
      'type'       => 'time',
      'timeFormat' => 24,
      'selected'   => $login_time
    ));
    echo $this->Form->input('reason', array(
      'label' => __('理由'),
      'type' => 'textarea',
      'value' => $attendance_reason,
      'div' => false,
      'class' => '',
      'style' => ''
    ));
    echo $this->Form->submit(__('更新'), array(
      'class' => 'btn btn-info',
      'div' => false
    ));
    echo $this->Form->end();
  ?>
</div>
