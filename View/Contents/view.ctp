<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	
	<title><?php echo $content['Content']['title']; ?></title>
	<meta name="application-name" content="iroha Board">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('common.css');
		echo $this->Html->css('contents_view.css?20200421');
		echo $this->Html->css('custom.css');

		echo $this->Html->script('jquery-1.9.1.min.js');
		echo $this->Html->script('jquery-ui-1.9.2.min.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('common.js');
		echo $this->Html->script('contents_view.js?20200421');
		echo $this->Html->script('custom.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->fetch('css-embedded');
		echo $this->fetch('script-embedded');
	?>
	<script>
	var URL_RECORDS_ADD		= '<?php echo Router::url(array('controller' => 'records', 'action' => 'add', $content['Content']['id']))?>'; // 学習履歴保存用URL
	var URL_CONTNES_INDEX	= '<?php echo Router::url(array('action' => 'index', $content['Course']['id']))?>'; // コンテンツ一覧画面
	var BUTTON_PC_LIST		= <?php echo json_encode(Configure::read('record_understanding_pc')) ?>;
	var BUTTON_SPN_LIST		= <?php echo json_encode(Configure::read('record_understanding_spn')) ?>;
	</script>
</head>
<body>
<?php
	switch($content['Content']['kind'])
	{
		case 'url': // URLコンテンツ
			$body = '<iframe id="contentFrame" width="100%" height="100%" scrolling="yes" src="'.h($content['Content']['url']).'"></iframe>';
			break;
		case 'movie': // 動画コンテンツ
			$body = '<video src="'.h($content['Content']['url']).'" controls width="100%" oncontextmenu="return false;"></video>';
			break;
		case 'text': // テキスト型コンテンツ
			$body = h($content['Content']['body']);
			$body = $this->Text->autoLinkUrls($body);
			$body = nl2br($body);
			break;
		case 'html': // リッチテキストコンテンツ
			$body = $content['Content']['body'];
			break;
	}
?>
<div class="content-view">
	<div class="content-title"><?php echo h($content['Content']['title'])?></div>
	<div class="content-body content-body-<?php echo $content['Content']['kind']?>"><?php echo $body;?></div>
	<div class="content-foot">
		<div class="content-menu">
			<div class="select-message text-success">理解度を選択して終了して下さい。</div>
			<span class='understanding-pc'></span>
			<span class='understanding-spn'></span>
				<button type="button" class="btn btn-danger" onclick="finish(0);">中断</button>
			<button type="button" class="btn btn-default" onclick="finish(-1);">戻る</button>
		</div>
	</div>
			</div>
</body>
</html>