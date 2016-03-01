<div id="pluginAclflashMessage" class="error">
ACL:
<?php
if(is_array($message))
{
    echo $this->Html->nestedList($message);
}
else
{
    echo $message;
}
?>
</div>