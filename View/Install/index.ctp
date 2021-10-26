<div class="install-index">
	<div class="panel panel-info">
		<div class="panel-heading">
			<?= APP_NAME; ?> Installer
		</div>
		<div class="panel-body">
			<p><?= APP_NAME; ?> のインストール及び管理者アカウントの作成を行います。</p>
			<li>作成する管理者アカウントのログインIDとパスワードを入力し、「インストール」ボタンをクリックしてください。</li>
			<li>管理者ログインIDは4文字以上32文字以内で、英数字のみを使用してください。</li>
			<li>パスワードは4文字以上32文字以内で、英数字のみを使用してください。</li>
		</div>
		<div class="panel-body">
			<form method="post" class="form-horizontal">
				<div class="form-group">
					<label for="Password" class="col col-sm-3 control-label">管理者ログインID</label>
					<div class="col col-sm-9">
						<input name="data[User][username]" class="form-control" value="<?= h($username)?>">
					</div>
				</div>
				<div class="form-group">
					<label for="Password" class="col col-sm-3 control-label">パスワード</label>
					<div class="col col-sm-9">
						<input name="data[User][password]" class="form-control" autocomplete="new-password" type="password" id="UserPassword" aria-autocomplete="list">
					</div>
				</div>
				<div class="form-group">
					<label for="Password" class="col col-sm-3 control-label">パスワード(確認用)</label>
					<div class="col col-sm-9">
						<input name="data[User][password2]" class="form-control" autocomplete="new-password" type="password" id="UserPassword2" aria-autocomplete="list">
					</div>
				</div>
				<div class="form-group">
					<label for="UserRegistNo" class="col col-sm-3 control-label"></label>
					<div class="col col-sm-9">
						<input class="btn btn-primary" type="submit" value="インストール">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
