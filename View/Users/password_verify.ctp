<div class="password-verify">
  <?php if (isset($success)): ?>
    <h2>パスワードリセット完了</h2>
    <div class="message">パスワードがリセットされました．</div>
    <p>
      ログイン用パスワードを作成し，メールで送信しました．<br/>
      ログイン後，パスワードを変更してください．
    </p>
  <?php else: ?>
    <h2>パスワードのリセットに失敗しました．</h2>
    <div class="warning">不正なトークンです．トークンの有効期限が切れているか，URLが正しくありません．</div>
    <p>
      URLが正しいか確認してください．<br/>
      URLが正しいにも関わらずアクセスできない場合は，管理者に連絡してください．
    </p>
  <?php endif; ?>
</div>
