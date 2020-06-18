<?php echo $this->Html->css('summernote.css');?>
<style type='text/css'>
	.header
	{
		display	: none;
	}
</style>
<?php $this->end(); ?>

<script>
	$(document).ready(function()
	{
		var mode = '<?php echo $mode?>';
		var file_url = '<?php echo $file_url?>';
		var file_name = '<?php echo $file_name?>';

		if(mode=='complete')
		{
			$('#btnUpload').hide();
			opener.setURL(file_url, file_name);
			window.close();
		}
	});
</script>
<div class="card panel-default">
	<div class="card-header">
		ファイルのアップロード
	</div>
	<div class="card-body">
		<div class="alert alert-info">
			アップロードするファイルを指定して、アップロードボタンをクリックしてください。<br>
			ファイルが複数ある場合には、ZIP形式で圧縮してアップロードを行ってください。
		</div>
		<div class="alert alert-warning">
			Google DriveからダウンロードしたZIPファイルは、アップロードの際に文字化けすることがあります。<br>
			その場合は、一度展開してから圧縮し直したものをアップロードしてください。<br>
			エラーを避けるために，スライドからファイルを生成する時，ファイル名を必ず英語にしてください．例： CPU_basic<br>
			アップロードするファイル名も英語にしてください．例：CPU_basic.zip
		</div>
		<div class="form-group">
			<h4>アップロード可能拡張子</h4>
			<?php echo $upload_extensions;?>
		</div>

		<div class="form-group">
			<h4>アップロード可能ファイルサイズ</h4>
			最大 : <?php echo $this->Number->toReadableSize($upload_maxsize) ;?>バイト
		</div>

		<div class="form-group">
			<?php echo $this->Form->create('Content', array('type'=>'file', 'enctype' => 'multipart/form-data')); ?>
				<input type="file" name="data[Content][file]" multiple="multiple" id="ContentFile" class="form-control">
				<br>
				<input type="submit" id="btnUpload"  class="btn btn-primary" value="アップロード">　
				<input type="button"  class="btn"  value=" 閉じる " onclick="window.close();">
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
