<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('soap');?>
<div class = "admin-soap-findByGroup">
  <div class = "ib-page-title"><?php echo __('担当グループ一覧');?></div>
  <br><br>
  <div class = "group-block">

  <?php foreach ((array)$groupData as $row):?>
  <div class = "group-btn-block">
  <button type = "button" class="group-btn"
    onclick = "location.href = '<?php echo Router::url(array('action' => 'group_edit',$row['Group']['id']))?>'"><?php echo $row['Group']['title']?></button>
  </div>
  <?php endforeach;?>
  </div>

</div>