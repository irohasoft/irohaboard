<?php echo $this->element('admin_menu');?>
<div class="contents form">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index/'.$this->Session->read('Iroha.course_id')))?>
<?php echo $this->Form->create('Content'); ?>
	<fieldset>
		<legend><?php echo __('新規コンテンツ'); ?></legend>
	<?php
		echo $this->Form->input('title',	array('label' => 'コンテンツタイトル'));
		echo '<label>コンテンツの種類</label><br>';
		echo $this->Form->radio(
				'kind',
				Configure::read('content_kind'),
				array(
						'separator'=>"　",
						'disabled'=>false,
						'legend'=>false
				)
		);
		
		echo "<div class='kind kind-movie kind-url'>";
		echo $this->Form->input('url',		array('label' => 'URL'));
		echo "</div>";
		
		echo "<span class='kind kind-text kind-html'>";
		echo $this->Form->input('body',		array('label' => '内容'));
		echo "</span>";

		echo "<span class='kind kind-test'>";
		echo $this->Form->input('timelimit', array('label' => '制限時間 (0-100)単位:分'));
		echo $this->Form->input('pass_rate', array('label' => '合格とする得点率 (0-100)'));
		echo "</span>";
		//echo $this->Form->input('opened');
		echo $this->Form->input('comment', array('label' => '備考'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('保存')); ?>
</div>
<script>
	$('input[name="data[Content][kind]"]:radio').val(['text']);
	$(".kind").hide();
	$(".kind-text").show();
	
	$( 'input[name="data[Content][kind]"]:radio' ).change( function() {  
		$(".kind").hide();
		$(".kind-"+$( this ).val()).show();
	});
	 
</script>