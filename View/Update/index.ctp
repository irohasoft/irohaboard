<?php
$url = Router::url(array('controller' => 'users', 'action' => 'login'));
$url = str_replace('/users/', '/admin/users/', $url);
?>
<div class="update-index">
	<div class="card" style="margin:20px;">
		<div class="card-header">
			Ripple Updater
		</div>
		<div class="card-body">
			<p style="margin:20px">データベースのアップデートが完了しました。</p>
		</div>
		<div class="card-footer text-center">
			<button class="btn btn-primary" onclick="location.href='<?php echo $url;?>'">管理者ログイン画面へ</button>
		</div>
	</div>
</div>
