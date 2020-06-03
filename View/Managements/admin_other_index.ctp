<?php echo $this->element('admin_menu')?>
<?php echo $this->Html->css('custom');?>
<div class = "admin-management-index">
  <div class = "custom-page-title"><?php echo __('その他管理')?></div>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'infos', 'action' => 'index'))?>'">お知らせ</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'settings', 'action' => 'index'))?>'">システム設定</button>
</div>
