<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
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

	</style>
	<script>
	function finish(val)
	{
		location.href = '<?php echo Router::url(array('controller' => 'records', 'action' => 'add', $content['Content']['id'], 1))?>/' + val;
	}

	function interrupt()
	{
		location.href = '<?php echo Router::url(array('controller' => 'records', 'action' => 'add', $content['Content']['id'], 0))?>/0';
	}

	function cancel()
	{
		location.href = '<?php echo Router::url(array('action' => 'index', $this->Session->read('Iroha.course_id')))?>';
	}
</script>
</head>
<body>
<?php
	//debug($content);
	$td_style = '';

	switch($content['Content']['kind'])
	{
		case 'url':
			$output = '<iframe id="contentFrame" width="100%" height="100%" scrolling="yes" src="'.h($content['Content']['url']).'"></iframe>';
			break;
		case 'movie':
			$output = '<video src="'.h($content['Content']['url']).'" controls width="100%"></video>';
			$td_style = 'vertical-align: middle;';
			break;
		case 'text':
			$output = h($content['Content']['body']);
			$td_style = 'vertical-align: top;';
		case 'html':
			$output = $content['Content']['body'];
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
			<?php echo $output;?>
		</td>
	</tr>
	<tr height="60">
		<td align="center">
			<div>
				※ 理解度を選んで終了して下さい。<br>
				<button type="button" class="btn btn-success" onclick="finish(5)">◎よく理解できた</button>
				<button type="button" class="btn btn-success" onclick="finish(4)">〇まあまあ理解できた</button>
				<button type="button" class="btn btn-success" onclick="finish(3)">△あまりよく理解できなかった</button>
				<button type="button" class="btn btn-success" onclick="finish(2)">✕全く理解できなかった</button>
				<button type="button" class="btn btn-danger" onclick="interrupt()">中断</button>
				<button type="button" class="btn btn-primary" onclick="cancel()">学習履歴を残さずに終了</button>
			</div>
		</td>
	</tr>
</table>
</body>
</html>