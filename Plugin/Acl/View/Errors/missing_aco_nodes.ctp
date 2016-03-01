<?php
echo '<div class="error_page_message">';

echo '	<span class="error">' . $message . '</span>';
echo '	<p>&nbsp;</p>';
echo '	<p>&nbsp;</p>';
echo '	<p>' . $this->Html->link(__d('acl', 'go to homepage'), '/') . '</p>';

echo '</div>';
?>