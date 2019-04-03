<div class="text-center">
<?php
echo $this->Paginator->counter(array(
	'format' => __('合計 : {:count}件　{:page} / {:pages}ページ')
));
?>
</div>
<div class="text-center">
	<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
</div>

