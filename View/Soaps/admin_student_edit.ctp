<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('soap');?>
<div class = "admin-student_edit-index">
  <div class = "ib-page-title"><?php echo __('個別記入')?></div>
  <br><br>
  <?php //$this->log($members);?>
  <div class = "member-input">
    <div class = "info">
      <div class = "user_name">
        <td><?php echo h($user_list[$user_id]);?>&nbsp;</td>
      </div>
      <div class = "student-photo">
        <?php
          $img_src = $this->Image->makeInlineImage(Configure::read('student_img').$pic_path);
        ?>
        <img src="<?php echo $img_src; ?>" height="150" alt="<?php echo $pic_path ?>"/>
      </div>
    </div>
    <div class = "soap">
    <?php
			echo $this->Form->create("$user_id");
			echo $this->Form->hidden('id', array('value' => $soap_inputted[$user_id]['id']));
			echo $this->Form->hidden('user_id',array('value' => $user_id));
      ?>
      <div class = "soap_teacher">
      <?php
      echo $this->Form->input('group_id',array(
						'label' => __('担当講師：'),
						'div' => false,
						'class' => 'soap_teacher',
						'options' => $group_list,
						'empty' => '',
            'value' => $group_id,
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
						'value' => $soap_inputted[$user_id]['current_status'],
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
						'label' => '記入日：',
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
				'value' => $soap_inputted[$user_id]['S'],
  			'div' => false,
  			'class' => ''
  		));
      echo "</div>";

      echo "<div class = 'soap-input'>";
      echo $this->Form->input('O',array(
				'label' => __('O:'),
				'value' => $soap_inputted[$user_id]['O'],
  			'div' => false,
  			'class' => '',
  			'style' => ''
  		));
      echo "</div>";

      echo "<div class = 'soap-input'>";
      echo $this->Form->input('A',array(
				'label' => __('A:'),
				'value' => $soap_inputted[$user_id]['A'],
  			'div' => false,
  			'class' => '',
  			'style' => ''
  		));
      echo "</div>";

      echo "<div class = 'soap-input'>";
      echo $this->Form->input('P',array(
				'label' => __('P:'),
				'value' => $soap_inputted[$user_id]['P'],
  			'div' => false,
  			'class' => '',
  			'style' => ''
  		));
      echo "</div>";

      echo "<div class = 'soap-input'>";
      echo $this->Form->input('comment',array(
				'label' => __('自由記述:'),
				'value' => $soap_inputted[$user_id]['comment'],
  			'div' => false,
  			'class' => 'soap',
  			'style' => ''
  		));
      echo "</div>";
  	?>
    </div>
		<div class = "enquete">
			<div class = "enquete_headline"><?php echo __('今日の感想:');?></div>
			<?php //$this->log($enquete_inputted[$user_id]['today_impressions']);?>
			<?php echo $enquete_inputted[$user_id]['today_impressions'];?>
		</div>
  </div>
  <div class = "under_element"></div>
  <input type = "submit" class = "btn btn-info btn-add pull-right" value = "登録">
  <?php echo $this->Form->end(); ?>
</div>
