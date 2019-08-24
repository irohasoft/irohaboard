<?php echo $this->element('admin_menu')?>
<div class = "admin-recentstates-index">
  <div class = "ib-page-title">
    <?php echo __('検索一覧');?>
  </div>
  <br><br>
  <button type = "button" class = "btn btn-primary"
    onclick = "location.href = '<?php echo Router::url(array('action' => 'find_by_group'))?>'">グループで検索</button>

  <br>
  <button type = "button" class = "btn btn-primary"
    onclick = "location.href = '<?php echo Router::url(array('action' => 'find_by_student'))?>'">個人で検索</button>
</div>
