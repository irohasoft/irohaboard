<div class="ib-page-title"><?= $message; ?></div>
<p class="error">
	<br>
	<strong><?= __d('cake', 'エラー'); ?>: </strong>
	<?php
	if($message == 'The request has been black-holed')
	{
		printf(__d('cake', 'トークンの有効期限が切れています。前の画面に戻り、画面を一度リフレッシュしてから再度お試しください。'));
	}
	else
	{
		printf(__d('cake', '指定されたアドレス %s へのリクエストは無効です。'), "<strong>'{$url}'</strong>");
	}
	?>
</p>
<?php
if (Configure::read('debug') > 0):
	echo $this->element('exception_stack_trace');
endif;
