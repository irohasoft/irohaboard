<div class="install-complete">
	<div class="panel panel-info">
		<div class="panel-heading">
			<?= APP_NAME; ?> Installer
		</div>
		<div class="panel-body">
			<p class="msg">
				インストールが完了しました<br>
				<br>
				以下のログインIDとパスワードでログイン可能です。<br>
				ログインID : root<br>
				パスワード : 入力されたパスワード<br>
			</p>
		</div>
		<div class="panel-footer text-center">
			<button class="btn btn-primary" onclick="location.href='<?= Router::url(['controller' => 'users', 'action' => 'login', 'admin' => true]) ?>'">管理者ログイン画面へ</button>
		</div>
	</div>
</div>
