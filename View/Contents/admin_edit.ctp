<?php echo $this->element('admin_menu');?>
<?php $this->start('css-embedded'); ?>
<?php echo $this->Html->css('summernote.css');?>
<style type='text/css'>
	input[name="data[Content][url]"]
	{
		display:inline-block;
		margin-right:10px;
	}
	label span
	{
		font-weight: normal;
	}
</style>
<?php $this->end(); ?>
<?php $this->start('script-embedded'); ?>
<?php echo $this->Html->script('summernote.min.js');?>
<?php echo $this->Html->script('lang/summernote-ja-JP.js');?>
<script>
	//$('input[name="data[Content][kind]"]:radio').val(['text']);
	var _editor;
	
	$(document).ready(function()
	{
		$url = $('input[name="data[Content][url]"]');

		$url.after('<input id="btnUpload" type="button" value="アップロード">');

		$("#btnUpload").click(function(){
			var val = $('input[name="data[Content][kind]"]:checked').val();
			
			if(!val)
				return false;
			
			if(
				(val=='text')||
				(val=='test')
			)
				return false;
			
			if(val=='url')
				val = 'file';
			
			window.open('<?php echo Router::url(array('controller' => 'contents', 'action' => 'upload'))?>/'+val, '_upload', 'width=650,height=500,resizable=no');
			return false;
		});

		$('input[name="data[Content][kind]"]:radio').change( function() {
			render();
		});

		render();
	});
	
	function render()
	{
		var val = $('input[name="data[Content][kind]"]:checked').val();
		$(".kind").hide();
		$(".kind-"+val).show();
		
		if(val=='url')
		{
			$("input[name='data[Content][url]']").css('width', '100%');
			$("#btnUpload").hide();
		}
		else
		{
			$("input[name='data[Content][url]']").css('width', '85%');
			$("#btnUpload").show();
		}
		
		if(val=='html')
		{
			// リッチテキストエディタを起動
			CommonUtil.setRichTextEditor('#ContentBody', <?php echo (Configure::read('use_upload_image') ? 'true' : 'false')?>, '<?php echo $this->webroot ?>');
		}
		else
		{
			$("#ContentBody").summernote('destroy');
			
			// remove HTML tags
			if($($("#ContentBody").val()).text()=="")
				$("#ContentBody").val("");
		}
	}

	function preview()
	{
		var val = $('input[name="data[Content][kind]"]:checked').val();
		
		if(val=='label')
		{
			alert('ラベルはプレビューできません');
			return;
		}
		
		if(val=='file')
		{
			alert('配布資料はプレビューできません');
			return;
		}
		
		if(val=='test')
		{
			alert('テストはプレビューできません');
			return;
		}
		
		$.ajax({
			url: "<?php echo Router::url(array('action' => 'preview')) ?>",
			type: "POST",
			data: {
				content_title : $("#ContentTitle").val(),
				content_kind  : $('input[name="data[Content][kind]"]:checked').val(),
				content_url   : $("#ContentUrl").val(),
				content_body  : $("#ContentBody").val(),
			},
			dataType: "text",
			success : function(response){
				//通信成功時の処理
				//alert(response);
				var url = '<?php echo Router::url(array('controller' => 'contents', 'action' => 'preview'))?>'.replace('admin/', '');
				
				window.open(url, '_preview', 'width=1000,height=700,resizable=no');
			},
			error: function(){
				//通信失敗時の処理
				//alert('通信失敗');
			}
		});
	}

	function setURL(url)
	{
		$('input[name="data[Content][url]"]').val(url);
	}
</script>
<?php $this->end(); ?>

<div class="contents form">
	<?php
		$this->Html->addCrumb('コース一覧', array('controller' => 'courses', 'action' => 'index'));
		$this->Html->addCrumb(h($course['Course']['title']));

		echo $this->Html->getCrumbs(' / ');
	?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規コンテンツ'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Content', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => 'コンテンツタイトル'));
				echo $this->Form->input('kind',	array(
					'type' => 'radio',
					'before' => '<label class="col col-md-3 control-label">コンテンツの種類</label>',
					'separator'=>"<br>",
					'disabled'=>false,
					'legend' => false,
					'class' => false,
					'options' => Configure::read('content_kind_comment')
					)
				);

				echo "<div class='kind kind-movie kind-url kind-file'>";
				echo $this->Form->input('url',		array('label' => 'URL'));
				echo "</div>";

				echo "<div class='kind kind-text kind-html'>";
				echo $this->Form->input('body',		array('label' => '内容'));
				echo "</div>";

				echo "<span class='kind kind-test'>";
				echo $this->Form->input('timelimit', array('label' => '制限時間 (0-100分)'));
				echo $this->Form->input('pass_rate', array('label' => '合格とする得点率 (0-100%)'));
				echo "</span>";

				echo "<span class='kind kind-text kind-html kind-movie kind-url kind-file kind-test'>";
				echo $this->Form->input('comment', array('label' => '備考'));
				echo "</span>";
			?>
			<div class="form-group">
				<div class="col col-md-9 col-md-offset-3">
					<button class="btn btn-default" value="プレビュー" onclick="preview(); return false;" type="submit">プレビュー</button>
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
