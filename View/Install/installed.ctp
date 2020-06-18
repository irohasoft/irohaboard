<?php
$url = Router::url(array('controller' => 'users', 'action' => 'login', 'admin' => true));
?>
<div class="install-installed">
	<div class="card">
		<div class="card-header">
			iroha Board Installer
		</div>
		<div class="card-body">
			<p class="msg">既にインストールされています。</p>
		</div>
		<div class="panel-footer text-center">
			<button class="btn btn-primary" onclick="location.href='<?php echo $url;?>'">管理者ログイン画面へ</button>
		</div>
	</div>
</div>
