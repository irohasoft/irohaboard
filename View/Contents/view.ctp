<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $content['Content']['title']; ?>
	</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('common');

		echo $this->Html->script('jquery-1.9.1.min.js');
		echo $this->Html->script('jquery-ui-1.9.2.min.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('common.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<?php echo $this -> fetch( 'css-embedded' ); ?>
	<?php echo $this -> fetch( 'script-embedded' ); ?>
	<style>
	html, body
	{
		margin				:0px;
		padding				:0px;
		height				:100%;
		background-color	:#EDEFF1;
	}

	#contentFrame
	{
		min-height	: 600px;
		border		: 1px;
	}
	table, td, tr
	{
		margin:0px;
		padding:0px;
	}
	
	.content-title
	{
		font-size	: 18px;
		font-weight	: bold;
	}
	
	.for-spn
	{
		display: none;
	}
	
	.select-message
	{
		padding-bottom	: 10px;
	}
	
	@media only screen and (max-width:800px)
	{
		button
		{
			margin			: 5px;
		}
		
		.select-message
		{
			padding:0px;
		}
		
		.for-pc
		{
			display: none;
		}
		.for-spn
		{
			display: block;
		}
	}

	</style>
	<script>
	var studySec = 0;
	
	$(document).ready(function()
	{
		setInterval("studySec++;", 1000);
	});

	function finish(val)
	{
		// プレビューの場合、学習履歴を保存しない
		if(location.href.indexOf('preview') > 0)
		{
			window.close();
			return;
		}
		
		// 中断の場合
		if(val==0)
		{
			location.href = '<?php echo Router::url(array('controller' => 'records', 'action' => 'add', $content['Content']['id'], 0))?>/' + studySec + '/0';
			return;
		}
		
		// 学習履歴を残さずに終了の場合
		if(val==-1)
		{
			location.href = '<?php echo Router::url(array('action' => 'index', $content['Course']['id']))?>';
			return;
		}
		
		// 学習履歴を残して終了の場合
		location.href = '<?php echo Router::url(array('controller' => 'records', 'action' => 'add', $content['Content']['id'], 1))?>/' + studySec + '/' + val;
		return;
	}

	function interrupt()
	{
	}

	function cancel()
	{
	}
</script>
</head>
<body>
<?php
	$td_style = '';
	
	switch($content['Content']['kind'])
	{
		case 'url':
			$body = '<iframe id="contentFrame" width="100%" height="100%" scrolling="yes" src="'.h($content['Content']['url']).'"></iframe>';
			break;
		case 'movie':
			$body = '<video src="'.h($content['Content']['url']).'" controls width="100%"></video>';
			$td_style = 'vertical-align: middle;';
			break;
		case 'text':
			$body = h($content['Content']['body']);
			$body = $this->Text->autoLinkUrls($body);
			$body = nl2br($body);
			$td_style = 'vertical-align: top;';
			break;
		case 'html':
			$body = $content['Content']['body'];
			$td_style = 'vertical-align: top;';
			break;
	}
?>
<table width="100%" height="99%">
	<tr height="30">
		<td>
			<div class='content-title'><?php echo h($content['Content']['title'])?></div>
		</td>
	</tr>
	<tr>
		<td style="<?php echo $td_style?>">
			<?php echo $body;?>
		</td>
	</tr>
	<tr height="60">
		<td align="center">
			<div>
				<div class="select-message">※ 理解度を選んで終了して下さい。</div>
				<span class='for-pc'>
				<button type="button" class="btn btn-success" onclick="finish(5);">◎よく理解できた</button>
				<button type="button" class="btn btn-success" onclick="finish(4);">〇まあまあ理解できた</button>
				<button type="button" class="btn btn-success" onclick="finish(3);">△あまりよく理解できなかった</button>
				<button type="button" class="btn btn-success" onclick="finish(2);">✕全く理解できなかった</button>
				</span>
				<span class='for-spn'>
				<button type="button" class="btn btn-success" onclick="finish(5);">◎</button>
				<button type="button" class="btn btn-success" onclick="finish(4);">〇</button>
				<button type="button" class="btn btn-success" onclick="finish(3);">△</button>
				<button type="button" class="btn btn-success" onclick="finish(2);">✕</button>
				</span>
				<button type="button" class="btn btn-danger" onclick="finish(0);">中断</button>
				<button type="button" class="btn btn-primary" onclick="finish(-1);">学習履歴を残さずに終了</button>
			</div>
		</td>
	</tr>
</table>
</body>
</html>