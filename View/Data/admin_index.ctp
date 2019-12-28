<?php echo $this->element('admin_menu')?>
<?php echo $this->Html->css('custom');?>
<div class = "admin-data-index">
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'records', 'action' => 'index'))?>'">学習履歴</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'soaprecords', 'action' => 'index'))?>'">SOAP</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'enquete', 'action' => 'index'))?>'">アンケート</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'attendances', 'action' => 'index'))?>'">出欠席</button>
  <button type = "button" class = "btn btn-primary select-btn"
    onclick = "location.href = '<?php echo Router::url(array('controller' => 'adminmanages', 'action' => 'download'))?>'">授業データ</button>
</div>
