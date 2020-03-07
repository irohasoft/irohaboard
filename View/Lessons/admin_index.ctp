<?php echo $this->element('admin_menu')?>
<?php echo $this->Html->css('custom');?>
<div class = "admin-lessons-index full-view">
  <div class="ib-breadcrumb">
  <?php
    $this->Html->addCrumb('授業日一覧', array('controller' => 'dates', 'action' => 'index'));
    $this->Html->addCrumb(h($date));
    echo $this->Html->getCrumbs(' / ');
  ?>
  </div>
  <div class="ib-page-title"><?php echo h($date)." ".__('時限一覧'); ?></div>
  <div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add', $date_id)) ?>'">+ 追加</button>
	</div>
  <table>
	  <thead>
	    <tr>
        <th nowrap><?php echo $this->Paginator->sort('period', '時限'); ?></th>
        <th nowrap><?php echo __('授業コード'); ?></th>
        <th class="ib-col-date"><?php echo __('開始時刻'); ?></th>
        <th class="ib-col-date"><?php echo __('終了時刻'); ?></th>
        <th class="ib-col-action"><?php echo __('Actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($lessons as $lesson):
        $period = Configure::read('period')[$lesson['Lesson']['period']];
        $lesson_code = $lesson['Lesson']['code'] ? $lesson['Lesson']['code'] : 'なし';
      ?>
      <tr>
        <td nowrap><?php echo h($period); ?></td>
        <td nowrap><?php echo h($lesson_code); ?></td>
        <td nowrap class="ib-col-date"><?php echo h($lesson['Lesson']['start']); ?>&nbsp;</td>
        <td nowrap class="ib-col-date"><?php echo h($lesson['Lesson']['end']); ?>&nbsp;</td>
        <td nowrap class="ib-col-action">
          <button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $date_id, $lesson['Lesson']['id'])) ?>'">編集</button>
          <?php echo $this->Form->postLink(__('削除'),
            array('action' => 'delete', $date_id, $lesson['Lesson']['id']),
            array('class'=>'btn btn-danger'),
            __('[%s] を削除してもよろしいですか?', $date." ".$period)
          ); ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php echo $this->element('paging'); ?>
</div>
