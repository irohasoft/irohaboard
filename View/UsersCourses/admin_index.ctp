<div class="usersCourses index">
	<div class="ib-page-title"><?php echo __('Users Courses'); ?></div>
	<table>
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('course_id'); ?></th>
			<th><?php echo $this->Paginator->sort('started'); ?></th>
			<th><?php echo $this->Paginator->sort('ended'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('comment'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($usersCourses as $usersCourse): ?>
	<tr>
		<td><?php echo h($usersCourse['UsersCourse']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($usersCourse['User']['name'], array('controller' => 'users', 'action' => 'view', $usersCourse['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($usersCourse['Course']['title'], array('controller' => 'courses', 'action' => 'view', $usersCourse['Course']['id'])); ?>
		</td>
		<td><?php echo h($usersCourse['UsersCourse']['started']); ?>&nbsp;</td>
		<td><?php echo h($usersCourse['UsersCourse']['ended']); ?>&nbsp;</td>
		<td><?php echo h($usersCourse['UsersCourse']['created']); ?>&nbsp;</td>
		<td><?php echo h($usersCourse['UsersCourse']['modified']); ?>&nbsp;</td>
		<td><?php echo h($usersCourse['UsersCourse']['comment']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $usersCourse['UsersCourse']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $usersCourse['UsersCourse']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $usersCourse['UsersCourse']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $usersCourse['UsersCourse']['id']))); ?>
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
		<?php echo $this->element('admin_menu');?>
	</ul>
</div>
