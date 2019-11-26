<?php echo $this->element('admin_menu');?>
<head>
  <?php echo $this->Html->css('admin_manage');?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<div class = "admin-manage-header">
  <div class = "ib-page-title"><?php echo __('授業データ')?></div></br></br>
</div>

<div class="ib-horizontal">
  <?php
    echo $this->Form->create('User');
	  echo '<div class="ib-search-buttons" style = "float : right;">';
	  echo $this->Form->submit(__('ダウンロード'),	array('class' => 'btn btn-info', 'div' => false));
	  //echo $this->Form->hidden('cmd');
	  //echo '<button type="button" class="btn btn-default" onclick="downloadCSV()">'.__('CSV出力').'</button>';
	  echo '</div>';
	  echo '<div class="ib-row" >';
	  echo '<div class="ib-search-date-container form-inline">';
	  echo $this->Form->input('target_date',	array(
		  'label' => '日付：',
		  'options'=>$date_list,
		  'selected'=>'',
		  'empty' => '授業日を選択してください',
		  'required'=>false,
		  'style' => '',
		  'value' => $target_date,
      'class'=>'form-control'));
    echo'</div>';
	  echo '</div>';
	  echo $this->Form->end();
?>
</div>
