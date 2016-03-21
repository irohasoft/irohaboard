	<div class="text-center">
	<?php
	/*
	echo $this->Paginator->counter(array(
		'format' => __('{:page} / {:pages}ページ, 表示件数:{:current}, 合計件数:{:count}, 範囲:{:start} - {:end}')
	));
	*/
	echo $this->Paginator->counter(array(
		'format' => __('合計 : {:count}件　{:page} / {:pages}ページ')
	));
	?>
	</div>
	<div class="text-center">
		<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
	</div>

