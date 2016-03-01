<?php 
echo $this->element('design/header', array('no_acl_links' => true));
?>

<div class="error">
	
	<?php 
	if(isset($model_is_not_requester))
	{
		echo '<p class="warning">';
		echo sprintf(__d('acl', 'The <em>%s</em> model is not configured to act as an <em>ACL requester</em>'), Configure :: read('acl.aro.user.model')) . '</p>';
		echo '</p>';
	}
	
	if(isset($role_is_not_requester))
	{
		echo '<p class="warning">';
		echo sprintf(__d('acl', 'The <em>%s</em> model is not configured to act as an <em>ACL requester</em>'), Configure :: read('acl.aro.role.model')) . '</p>';
		echo '</p>';
	}
	
	echo '<p>&nbsp;</p>';
	
	echo '<p>';
	echo sprintf(__d('acl', 'In a classical ACL configuration, the models that represent the users and the roles must act as <em>ACL requesters</em> (see %s).'), 
					$this->Html->link(__d('acl', 'Acts As a Requester'), 'http://book.cakephp.org/view/1547/Acts-As-a-Requester'));	
	echo '</p>';
	
	echo '<p>';
	echo __d('acl', "If you wish, you can disable this alert by setting the ACL plugin parameter 'acl.check_act_as_requester' to <em>false</em>.");
	echo '</p>';
	
	?>

</div>

<?php
echo $this->element('design/footer');
?>