<div class="usersGroups view">
<div class="ib-page-title"><?php echo __('Users Group'); ?></div>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($usersGroup['UsersGroup']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($usersGroup['User']['name'], array('controller' => 'users', 'action' => 'view', $usersGroup['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($usersGroup['Group']['title'], array('controller' => 'groups', 'action' => 'view', $usersGroup['Group']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($usersGroup['UsersGroup']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($usersGroup['UsersGroup']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($usersGroup['UsersGroup']['comment']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Users Group'), array('action' => 'edit', $usersGroup['UsersGroup']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Users Group'), array('action' => 'delete', $usersGroup['UsersGroup']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $usersGroup['UsersGroup']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Users Groups'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users Group'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
