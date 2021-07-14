<div class="ib-page-title"><?= $message; ?></div>
<p class="error">
	<strong><?= __d('cake', 'エラー'); ?>: </strong>
	<?= __d('cake', '内部エラーが発生しました。'); ?>
</p>
<?php
if (Configure::read('debug') > 0):
	echo $this->element('exception_stack_trace');
endif;
