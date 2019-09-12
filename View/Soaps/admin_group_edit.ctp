<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('soap');?>
<div class = "admin-group_edit-index">
  <div class = "ib-page-title"><?php echo __('担当受講生一覧')?></div>
  <br><br>
  <?php //$this->log($members);?>
  <?php foreach($members as $member):?>
  <div class = "member-input">
    <?php
      $user_id = $member['User']['id'];
    ?>
    <div class = "user_name">
      <td><?php echo h($user_list[$user_id]);?>&nbsp;</td>
    </div>
    <div class = "soap">
    <?php
		  echo $this->Form->create("$user_id");
			echo $this->Form->hidden('user_id',array('value' => $member['User']['id']));
			echo $this->Form->hidden('group_id',array('value' => $member['User']['group_id']));
      ?>
      <div class = "soap_teacher">
      <?php
      echo $this->Form->input('group_id',array(
						'label' => __('担当講師：'),
						'div' => false,
						'class' => 'soap_teacher',
						'options' => $group_list,
						'empty' => '',
            'value' => $member['User']['group_id'],
						'style' => ''
					 ));
      ?>
      </div>
      <?php
      echo "<div class = 'soap_current'>";
      echo $this->Form->input('current_status',array(
						'label' => __('現状：'),
						'div' => false,
						'class' => 'soap_select',
						'options' => $course_list,
						'empty' => '',
						'style' => ''
					));
      echo "</div>";
      echo "<div class = 'under-element'></div>";
      echo "<div class = 'soap-time'>";
      echo $this->Form->input('today_date',array(
						'type' => 'date',
						'dateFormat' => 'YMD',
						'monthNames' => false,
						'timeFormat' => '24',
            'div' => false,
						'minYear' => date('Y') - 1,
						'maxYear' => date('Y'),
						'separator' => ' / ',
						'label' => '記入時間：',
						'class' => 'soap_select',
						'style' => '',
						'value' => $today_date
					));
      echo "</div>";
      ?>
      <?php

      echo "<div class = 'soap-input'>";
  		echo $this->Form->input('S',array(
  			'label' => __('S:'),
  			'div' => false,
  			'class' => ''
  		));
      echo "</div>";

      echo "<div class = 'soap-input'>";
      echo $this->Form->input('O',array(
  			'label' => __('O:'),
  			'div' => false,
  			'class' => '',
  			'style' => ''
  		));
      echo "</div>";

      echo "<div class = 'soap-input'>";
      echo $this->Form->input('A',array(
  			'label' => __('A:'),
  			'div' => false,
  			'class' => '',
  			'style' => ''
  		));
      echo "</div>";

      echo "<div class = 'soap-input'>";
      echo $this->Form->input('P',array(
  			'label' => __('P:'),
  			'div' => false,
  			'class' => '',
  			'style' => ''
  		));
      echo "</div>";

      echo "<div class = 'soap-input'>";
      echo $this->Form->input('comment',array(
  			'label' => __('自由記述:'),
  			'div' => false,
  			'class' => 'soap',
  			'style' => ''
  		));
      echo "</div>";
  	?>
    </div>
  </div>
  <div class = "under_element"><?php echo __('--------------');?></div>
  <?php endforeach;?>
  <input type = "submit" class = "btn btn-info btn-add" value = "登録">
  <?php echo $this->Form->end(); ?>
</div>
