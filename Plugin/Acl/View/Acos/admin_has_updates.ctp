<?php
echo $this->element('design/header', array('no_acl_links' => true));
?>

<div class="error">
	
	<?php
	echo '<p class="warning">' . __d('acl', 'Some controllers have been modified, resulting in actions that are not referenced as ACO in the database or ACO records that are obsolete') . ' :</p>';
	
	if(count($missing_aco_nodes) > 0)
	{
	    echo '<h3>' . __d('acl', 'Missing ACOs') . '</h3>';
	
    	echo '<p>';
    	echo $this->Html->nestedList($missing_aco_nodes);
    	echo '</p>';
	}
	
	if(count($nodes_to_prune) > 0)
	{
	    echo '<h3>' . __d('acl', 'Obsolete ACOs') . '</h3>';
	    
	    echo '<p>';
    	echo $this->Html->nestedList($nodes_to_prune);
    	echo '</p>';
	}
	
	echo '<p>';
	echo __d('acl', 'You can update the ACOs by clicking on the following link') . ' : ';
	echo $this->Html->link(__d('acl', 'Synchronize ACOs'), '/admin/acl/acos/synchronize/run');
	echo '</p>';
	
	echo '<p>';
	echo __d('acl', 'Please be aware that this message will appear only once. But you can always rebuild the ACOs by going to the ACO tab.');
	echo '</p>';
	?>
	
</div>

<?php
echo $this->element('design/footer');
?>