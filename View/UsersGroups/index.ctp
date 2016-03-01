<div class="usersGroups index">
	<div class="ib-page-title"><?php echo __('Users Groups'); ?></div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('group_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('comment'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($usersGroups as $usersGroup): ?>
	<tr>
		<td><?php echo h($usersGroup['UsersGroup']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($usersGroup['User']['name'], array('controller' => 'users', 'action' => 'view', $usersGroup['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($usersGroup['Group']['title'], array('controller' => 'groups', 'action' => 'view', $usersGroup['Group']['id'])); ?>
		</td>
		<td><?php echo h($usersGroup['UsersGroup']['created']); ?>&nbsp;</td>
		<td><?php echo h($usersGroup['UsersGroup']['modified']); ?>&nbsp;</td>
		<td><?php echo h($usersGroup['UsersGroup']['comment']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $usersGroup['UsersGroup']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $usersGroup['UsersGroup']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $usersGroup['UsersGroup']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $usersGroup['UsersGroup']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Users Group'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
