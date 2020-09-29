<div class="text-center">
<?php
echo $this->Paginator->counter(array(
	'format' => __('合計').' : {:count}'.__('件').'　{:page} / {:pages}'.__('ページ')
));
?>
</div>
<div class="text-center">
	<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
</div>

