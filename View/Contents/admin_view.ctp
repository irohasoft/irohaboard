<div class="contents view">
<div class="ib-page-title"><?php echo __('Content'); ?></div>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($content['Content']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($content['Group']['title'], array('controller' => 'groups', 'action' => 'view', $content['Group']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course'); ?></dt>
		<dd>
			<?php echo $this->Html->link($content['Course']['title'], array('controller' => 'courses', 'action' => 'view', $content['Course']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($content['User']['name'], array('controller' => 'users', 'action' => 'view', $content['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($content['Content']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($content['Content']['url']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kind'); ?></dt>
		<dd>
			<?php echo h($content['Content']['kind']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Body'); ?></dt>
		<dd>
			<?php echo h($content['Content']['body']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Timelimit'); ?></dt>
		<dd>
			<?php echo h($content['Content']['timelimit']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Passing Score'); ?></dt>
		<dd>
			<?php echo h($content['Content']['passing_score']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Opened'); ?></dt>
		<dd>
			<?php echo h($content['Content']['opened']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($content['Content']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($content['Content']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deleted'); ?></dt>
		<dd>
			<?php echo h($content['Content']['deleted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sort No'); ?></dt>
		<dd>
			<?php echo h($content['Content']['sort_no']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($content['Content']['comment']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Content'), array('action' => 'edit', $content['Content']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Content'), array('action' => 'delete', $content['Content']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $content['Content']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Contents'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Content'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Records'), array('controller' => 'records', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Record'), array('controller' => 'records', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Records'); ?></h3>
	<?php if (!empty($content['Record'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Content Id'); ?></th>
		<th><?php echo __('Score'); ?></th>
		<th><?php echo __('Borderline'); ?></th>
		<th><?php echo __('Is Passed'); ?></th>
		<th><?php echo __('Is Complete'); ?></th>
		<th><?php echo __('Progress'); ?></th>
		<th><?php echo __('Understanding'); ?></th>
		<th><?php echo __('Study Sec'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($content['Record'] as $record): ?>
		<tr>
			<td><?php echo $record['id']; ?></td>
			<td><?php echo $record['group_id']; ?></td>
			<td><?php echo $record['course_id']; ?></td>
			<td><?php echo $record['user_id']; ?></td>
			<td><?php echo $record['content_id']; ?></td>
			<td><?php echo $record['score']; ?></td>
			<td><?php echo $record['borderline']; ?></td>
			<td><?php echo $record['is_passed']; ?></td>
			<td><?php echo $record['is_complete']; ?></td>
			<td><?php echo $record['progress']; ?></td>
			<td><?php echo $record['understanding']; ?></td>
			<td><?php echo $record['study_sec']; ?></td>
			<td><?php echo $record['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'records', 'action' => 'view', $record['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'records', 'action' => 'edit', $record['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'records', 'action' => 'delete', $record['id']), array('confirm' => __('Are you sure you want to delete # %s?', $record['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Record'), array('controller' => 'records', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
