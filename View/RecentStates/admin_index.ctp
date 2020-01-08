<?php echo $this->element('admin_menu')?>
<?php echo $this->Html->css('custom');?>
<div class="admin-recentstates-index">
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('action' => 'find_by_group'))?>'">グループ</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('action' => 'find_by_student'))?>'">個人</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('action' => 'admin_all_view'))?>'">全受講生</button>
</div>
