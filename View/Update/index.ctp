<?php
$url = Router::url(array('controller' => 'users', 'action' => 'login'));
$url = str_replace('/users/', '/admin/users/', $url);
?>
<div class="update-index">
	<div class="panel panel-info" style="margin:20px;">
		<div class="panel-heading">
			iroha Board Updater
		</div>
		<div class="panel-body">
			<p style="margin:20px">データベースのアップデートが完了しました。</p>
		</div>
		<div class="panel-footer text-center">
			<button class="btn btn-primary" onclick="location.href='<?php echo $url;?>'">管理者ログイン画面へ</button>
		</div>
	</div>
</div>
