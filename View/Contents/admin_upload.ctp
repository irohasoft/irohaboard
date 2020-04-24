<?php $this->start('css-embedded'); ?>
<style type='text/css'>
	.header
	,.irohasoft
	,.ib-theme-color
	{
		display		: none;
	}
	
	#container
	{
		min-width	: initial;
	}
	
	#content
	{
		min-height	: initial;
	}
	
	.drag-over
	{
		opacity		: 0.5;
	}
</style>
<?php $this->end(); ?>
<?php $this->start('script-embedded'); ?>
<script>
	$(document).ready(function()
	{
		var mode		= '<?php echo $mode?>';
		var file_url	= '<?php echo $file_url?>';
		var file_name	= '<?php echo $file_name?>';
		
		if(mode=='complete')
		{
			$('#btnUpload').hide();
			parent.setURL(file_url, file_name);
		}
		
		$('.drop-container').on("dragenter", function(e){
			e.stopPropagation();
			e.preventDefault();
		});
		
		$('.drop-container').on("dragover", function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			$('.drop-container').addClass('drag-over');
		});
		
		$('.drop-container').on("dragleave", function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			$('.drop-container').removeClass('drag-over');
		});
		
		$('.drop-container').on("drop", function(event)
		{
			event.stopPropagation();
			event.preventDefault();
			$('.drop-container').removeClass('drag-over');
			var files = event.originalEvent.dataTransfer.files;
			$("#ContentFile")[0].files = files;
			
			if($("#ContentFile")[0].files.length==0)
			{
				alert('このブラウザはファイルのドロップをサポートしておりません。');
				return;
			}
			
			$('form').submit();
		});
		
	});
</script>
<?php $this->end(); ?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="form-group">
			<h4>アップロード可能なファイル形式</h4>
			<?php echo $upload_extensions;?>
		</div>

		<div class="form-group">
			<h4>アップロード可能なファイルサイズ</h4>
			最大 : <?php echo $this->Number->toReadableSize($upload_maxsize) ;?>バイト
		</div>

		<div class="form-group">
			<?php echo $this->Form->create('Content', array('type'=>'file', 'enctype' => 'multipart/form-data')); ?>
				<div class="drop-container alert alert-warning">
					<p>ここにファイルをドロップするか、ファイルを選択後、アップロードボタンをクリックしてください。</p>
					<p>ファイルが複数ある場合には、ZIP形式で圧縮してアップロードを行ってください。</p>
					<input type="file" name="data[Content][file]" multiple="multiple" id="ContentFile" class="form-control">
				</div>
				<input type="submit" id="btnUpload"  class="btn btn-primary" value="アップロード">　
				<input type="button"  class="btn"  value=" 閉じる " onclick="parent.closeDialog();">
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
