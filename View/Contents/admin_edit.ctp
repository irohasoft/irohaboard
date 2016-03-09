<?php echo $this->element('admin_menu');?>
<div class="contents form">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index/'.$this->Session->read('Iroha.course_id')))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規グループ'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Content', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => 'コンテンツタイトル'));
				echo $this->Form->input('kind',	array(
					'type' => 'radio',
					'before' => '<label class="col col-md-3 control-label">コンテンツの種類</label>',
					'separator'=>"　",
					'disabled'=>false,
					'legend' => false,
					'class' => false,
					'options' => Configure::read('content_kind')
					)
				);

				echo "<div class='kind kind-movie kind-url kind-file'>";
				echo $this->Form->input('url',		array('label' => 'URL'));
				echo $this->Form->input('アップロード', array('label'=>'', 'type'=>'button', 'value'=>'アップロード', 'onclick' => 'openUploader(); return false;' ));
				echo "</div>";

				echo "<div class='kind kind-text kind-html'>";
				echo $this->Form->input('body',		array('label' => '内容'));
				echo "</div>";

				echo "<span class='kind kind-test'>";
				echo $this->Form->input('timelimit', array('label' => '制限時間 (0-100)単位:分'));
				echo $this->Form->input('pass_rate', array('label' => '合格とする得点率 (0-100)'));
				echo "</span>";

				echo "<span class='kind kind-text kind-html kind-movie kind-url kind-file kind-test'>";
				echo $this->Form->input('comment', array('label' => '備考'));
				echo "</span>";
			?>
			<div class="form-group">
				<div class="col col-md-9 col-md-offset-3">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	input[name="data[Content][url]"]
	{
		display:inline-block;
		width:85%;
	}
</style>
<script>
	//$('input[name="data[Content][kind]"]:radio').val(['text']);
	$(document).ready(function()
	{
		$url = $('input[name="data[Content][url]"]');

		$url.after('<input id="btnPreview" type="button" value="プレビュー">');

		$("#btnPreview").click(function(){

			if($url=="")
			{
				alert("URLが入力されていません");
				return;
			}

			window.open($url.val());
		});

		render();
	});

	$('input[name="data[Content][kind]"]:radio').change( function() {
		render();
	});

	function render()
	{
		var val = $('input[name="data[Content][kind]"]:checked').val();
		$(".kind").hide();
		$(".kind-"+val).show();
	}

	function openUploader()
	{
		var val = $('input[name="data[Content][kind]"]:checked').val();
		
		if(val=='url')
			val = 'file';
		
		window.open('<?php echo Router::url(array('controller' => 'contents', 'action' => 'upload'))?>/'+val, '_upload', 'width=600,height=500,resizable=no');
		return false;
	}

	function setURL(url)
	{
		$('input[name="data[Content][url]"]').val(url);
	}
</script>