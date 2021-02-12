<?= $this->element('admin_menu');?>
<div class="admin-users-import">
<?= $this->Html->link(__('<< 戻る'), ['action' => 'index'])?>
	<div class="panel panel-default">
		<div class="panel-heading">
			インポート
		</div>
		<div class="panel-body">
			<li>ユーザ情報が格納されたCSVファイルを選択し、インポートを行って下さい。</li>
			<li>CSVファイルの文字コードは「Shift-JIS」を使用してください。(UTF-8は使用できません。)</li>
			<li>1行目はヘッダー行として扱われます。</li>
			<li>パスワードの指定は任意です。指定されていない場合は、既存のパスワードが保持されます。</li>
			<li>インポート処理がタイムアウトする場合は、CSVファイルを分割してインポートしてください。</li>
			<br>
			CSVの形式 ( * : 必須項目)
			<table class="ib-table-csv">
			<tr>
				<th>ログインID*</th>
				<th>パスワード</th>
				<th>氏名*</th>
				<th>権限 (受講者 / 管理者)*</th>
				<th>メールアドレス</th>
				<th>備考</th>
				<th>所属グループ・・・<?= Configure::read('import_group_count');?>列</th>
				<th>受講コース・・・<?= Configure::read('import_course_count');?>列</th>
			</tr>
			</table>
			<?php
				echo $this->Form->create('User',['type'=>'file']);
				echo $this->Form->input('csvfile',['label'=>'','type'=>'file']);
				echo $this->Form->submit('インポート', Configure::read('form_submit_defaults'));
				echo $this->Form->end();
			?>
			<div style="color:red;">
			<?= $err_msg; ?>
			</div>
		</div>
	</div>
</div>
