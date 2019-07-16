<?php
$url = Router::url(array('controller' => 'users', 'action' => 'login', 'admin' => true));
?>
<div class="install-installed">
	<div class="panel panel-info">
		<div class="panel-heading">
			iroha Board Installer
		</div>
		<div class="panel-body">
			<p class="msg">既にインストールされています。</p>
		</div>
		<div class="panel-footer text-center">
			<button class="btn btn-primary" onclick="location.href='<?php echo $url;?>'">管理者ログイン画面へ</button>
		</div>
	</div>
</div>
