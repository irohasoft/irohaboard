<?php
$url = Router::url(array('controller' => 'users', 'action' => 'login'));
$url = str_replace('/users/', '/admin/users/', $url);
?>
<div class="form">
	<div class="panel panel-info" style="margin:20px;">
		<div class="panel-heading">
			iroha Board Installer
		</div>
		<div class="panel-body">
			<p style="margin:20px">
				インストールが完了しました<br>
				<br>
				以下のIDとパスワードでログイン可能です。<br>
				ログインID : root<br>
				パスワード : irohaboard<br>
				※ログイン後すぐにパスワードを変更して下さい。
			</p>
		</div>
		<div class="panel-footer text-center">
			<button class="btn btn-primary" onclick="location.href='<?php echo $url;?>'">管理者ログイン画面へ</button>
		</div>
	</div>
</div>
