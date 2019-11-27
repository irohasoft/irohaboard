<?php echo $this->element('admin_menu')?>
<?php echo $this->Html->css('custom');?>
<div class = "admin-lessons-index">
  <div class="ib-page-title"><?php echo __('授業日程'); ?></div>
  <div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>

  <table>
	  <thead>
	    <tr>
        <th nowrap><?php echo $this->Paginator->sort('date', '授業日'); ?></th>
        <th nowrap><?php echo __('時限'); ?></th>
        <th class="ib-col-date"><?php echo __('開始時刻'); ?></th>
        <th class="ib-col-date"><?php echo __('終了時刻'); ?></th>
        <th class="ib-col-action"><?php echo __('Actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($lessons as $lesson):
        $period = Configure::read('period')[$lesson['Lesson']['period']];
      ?>
      <tr>
        <td nowrap><?php echo h($lesson['Lesson']['date']); ?></td>
        <td><?php echo h($period); ?></td>
        <td class="ib-col-date"><?php echo h($lesson['Lesson']['start']); ?>&nbsp;</td>
        <td class="ib-col-date"><?php echo h($lesson['Lesson']['end']); ?>&nbsp;</td>
        <td class="ib-col-action">
          <button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $lesson['Lesson']['id'])) ?>'">編集</button>
          <?php echo $this->Form->postLink(__('削除'),
            array('action' => 'delete', $lesson['Lesson']['id']),
            array('class'=>'btn btn-danger'),
            __('[%s] を削除してもよろしいですか?', $lesson['Lesson']['date']." ".$period)
          ); ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php echo $this->element('paging'); ?>
</div>
