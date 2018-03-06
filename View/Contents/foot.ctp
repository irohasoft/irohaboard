<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>foot</title>
	<style type="text/css">
	</style>
	<?php echo $this->Html->css( 'jquery-ui.css');?>
	<?php echo $this->Html->css( 'bootstrap.min.css');?>
	<?php echo $this->Html->script( 'jquery-1.9.1.min.js');?>
	<?php echo $this->Html->script( 'bootstrap.min.js');?>
	<script>
	function finish(val)
	{
		parent.location.href = '<?php echo Router::url(array('controller' => 'records', 'action' => 'add', $content['Content']['id'], 1))?>/' + val;
	}

	function interrupt()
	{
		parent.location.href = '<?php echo Router::url(array('controller' => 'records', 'action' => 'add', $content['Content']['id'], 0))?>/0';
	}
	
	function cancel()
	{
		parent.location.href = '<?php echo Router::url(array('action' => 'index', $content['Course']['id']))?>';
	}
	</script>
</head>
<body>
	※ 理解度を選んで終了して下さい。
	<button type="button" class="btn btn-success" onclick="finish(5)">◎よく理解できた</button>
	<button type="button" class="btn btn-success" onclick="finish(4)">〇まあまあ理解できた</button>
	<button type="button" class="btn btn-success" onclick="finish(3)">△あまりよく理解できなかった</button>
	<button type="button" class="btn btn-success" onclick="finish(2)">✕全く理解できなかった</button>
	<button type="button" class="btn btn-danger" onclick="interrupt()">中断</button>
	<button type="button" class="btn btn-info" onclick="cancel()">学習履歴を残さずに終了</button>
</body>
</html>
