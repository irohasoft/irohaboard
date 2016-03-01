	<p>
	<?php
	/*
	echo $this->Paginator->counter(array(
		'format' => __('{:page} / {:pages}ページ, 表示件数:{:current}, 合計件数:{:count}, 範囲:{:start} - {:end}')
	));
	*/
	echo $this->Paginator->counter(array(
		'format' => __('合計件数:{:count}　{:page} / {:pages}ページ')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('前へ'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('次へ') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
