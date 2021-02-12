<!DOCTYPE html>
<html>
<head>
<?= $this->Html->charset(); ?>
<title><?= $pageTitle; ?></title>

<?php if (!Configure::read('debug')): ?>
<meta http-equiv="Refresh" content="<?= $pause; ?>;url=<?= $url; ?>"/>
<?php endif ?>
<style><!--
P { text-align:center; font:bold 1.1em sans-serif }
A { color:#444; text-decoration:none }
A:HOVER { text-decoration: underline; color:#44E }
--></style>
</head>
<body>
<p><a href="<?= $url; ?>"><?= $message; ?></a></p>
</body>
</html>
