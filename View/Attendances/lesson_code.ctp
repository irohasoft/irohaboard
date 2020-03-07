<?php echo $this->element('menu');?>
<?php echo $this->Html->css('attendance');?>
<div class="attendance-lesson-code full-view">
  <div class="row">
     <div class="col"><?php echo $this->Html->link(__('<< 戻る'), array('controller' => 'users_courses', 'action' => 'index'))?></div>
  </div>
	<div class="row">
	   <div class="col"><h2><?php echo __("授業コード記入");?></h2></div>
	</div>
  <?php
    echo $this->Form->create();
    echo $this->Form->input('lesson_code', array(
      'label' => __(' '),
      'type' => 'number',
      'div' => false,
      'class' => '',
      'style' => ''
    ));
    echo $this->Form->submit(__('送信'), array(
      'class' => 'btn btn-info',
      'div' => false
    ));
    echo $this->Form->end();
  ?>
</div>
