<?php echo $this->element('admin_menu')?>
<?php echo $this->Html->css('custom');?>
<div class = "admin-management-index">
  <div class = "custom-page-title"><?php echo __('各種管理')?></div>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'users', 'action' => 'index'))?>'">ユーザ</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'groups', 'action' => 'index'))?>'">グループ</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'courses', 'action' => 'index'))?>'">コンテンツ</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'managements', 'action' => 'other_index'))?>'">その他管理</button>
</div>
