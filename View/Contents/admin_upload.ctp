<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		ファイルのアップロード
	</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('common');

		echo $this->Html->script('jquery-1.9.0.min.js');
		echo $this->Html->script('jquery-ui-1.9.2.min.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('common.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<?php echo $this -> fetch( 'css-embedded' ); ?>
	<?php echo $this -> fetch( 'script-embedded' ); ?>
</head>
<script>
	$(document).ready(function()
	{
		var mode = '<?php echo $mode?>';
		var file_url = '<?php echo $file_url?>';

		if(mode=='complete')
		{
			$('#btnUpload').hide();
			opener.setURL(file_url);
		}
	});
</script>
<body>
	<div class="panel panel-default">
		<div class="panel-heading">
			ファイルのアップロード
		</div>
		<div class="panel-body">
			アップロードするファイルを指定して、アップロードボタンをクリックしてください。<br><br>
			<h4>アップロード可能拡張子</h4>
			<?php echo join(', ', (array)Configure::read('upload_extensions'));?>

			<h4>アップロード可能ファイルサイズ</h4>
			最大 : <?php echo Configure::read('upload_maxsize');?>バイト

			<?php echo $this->Form->create('Content', array('type'=>'file', 'enctype' => 'multipart/form-data')); ?>
				<input type="file" name="data[Content][file]" multiple="multiple" id="ContentFile">
				<input type="submit" id="btnUpload"  class="btn btn-primary" value="アップロード">　
				<input type="button"  class="btn"  value=" 閉じる " onclick="window.close();">
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</body>
</html>
