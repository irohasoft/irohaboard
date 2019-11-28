<?php echo $this->element('admin_menu');?>
<head>
  <?php echo $this->Html->css('admin_manage');?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<div class = "admin-manage-header">
  <div class = "ib-page-title"><?php echo __('授業データ')?></div></br></br>
</div>

<div class="ib-horizontal">
  <?php echo $this->Form->create('User'); ?>
	<div class="ib-search-date-container form-inline">
  <?php
	  echo $this->Form->input('target_date',	array(
		  'label' => '日付：',
		  'options'=>$date_list,
		  'selected'=>'',
		  'empty' => '授業日を選択してください',
		  'required'=>false,
		  'style' => '',
		  'value' => $target_date,
      'class'=>'form-control'
    ));
    echo $this->Form->submit(__('ダウンロード'),	array('class' => 'btn btn-info', 'div' => false));
  ?>
  </div>
	<?php echo $this->Form->end(); ?>
</div>
