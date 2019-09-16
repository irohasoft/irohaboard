<?php echo $this->element('admin_menu')?>
<?php echo $this->Html->css('custom');?>
<div class = "admin-recentstates-index">
  <div class = "custom-page-title"><?php echo __('近況状況')?></div>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('action' => 'find_by_group'))?>'">グループで検索</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('action' => 'find_by_student'))?>'">個人で検索</button>
</div>
