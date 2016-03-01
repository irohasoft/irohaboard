<?php
echo $this->element('design/header');
?>

<?php
echo $this->element('Acos/links');
?>

<?php
if($run)
{
    if(count($logs) > 0)
    {
        echo '<p>';
        echo __d('acl', 'The following actions ACOs have been pruned');
        echo '<p>';
        echo $this->Html->nestedList($logs);
    }
    else
    {
        echo '<p>';
        echo __d('acl', 'There was no actions ACOs to prune');
        echo '</p>';
    }
}
else
{
    echo '<p>';
    echo __d('acl', 'This page allows you to prune obsolete ACOs.');
    echo '</p>';
    
    echo '<p>&nbsp;</p>';
    
    if(count($nodes_to_prune) > 0)
    {
        echo '<h3>' . __d('acl', 'Obsolete ACO nodes') . '</h3>';
	    
	    echo '<p>';
    	echo $this->Html->nestedList($nodes_to_prune);
    	echo '</p>';
    
    	echo '<p>&nbsp;</p>';
    	
        echo '<p>';
        echo __d('acl', 'Clicking the link will not change or remove permissions for actions ACOs that are not obsolete.');
        echo '</p>';
        
        echo '<p>';
        echo $this->Html->link($this->Html->image('/acl/img/design/clean.png') . ' ' . __d('acl', 'Prune'), '/admin/acl/acos/prune_acos/run', array('escape' => false));
        echo '</p>';
    }
    else
    {
        echo '<p style="font-style:italic;">';
        echo $this->Html->image('/acl/img/design/tick.png') . ' ' . __d('acl', 'There is no ACO node to delete');
        echo '</p>';
    }
}

echo $this->element('design/footer');
?>