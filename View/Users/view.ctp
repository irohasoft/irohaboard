<div class="users view">
	<div class="ib-page-title"><?php echo __('User'); ?></div>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($user['Group']['title'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($user['User']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Role'); ?></dt>
		<dd>
			<?php echo h($user['User']['role']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($user['User']['comment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Logined'); ?></dt>
		<dd>
			<?php echo h($user['User']['last_logined']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Started'); ?></dt>
		<dd>
			<?php echo h($user['User']['started']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ended'); ?></dt>
		<dd>
			<?php echo h($user['User']['ended']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($user['User']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deleted'); ?></dt>
		<dd>
			<?php echo h($user['User']['deleted']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $user['User']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contents'), array('controller' => 'contents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Content'), array('controller' => 'contents', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Records'), array('controller' => 'records', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Record'), array('controller' => 'records', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Contents'); ?></h3>
	<?php if (!empty($user['Content'])): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Group Id'); ?></th>
			<th><?php echo __('Course Id'); ?></th>
			<th><?php echo __('User Id'); ?></th>
			<th><?php echo __('Title'); ?></th>
			<th><?php echo __('Url'); ?></th>
			<th><?php echo __('Kind'); ?></th>
			<th><?php echo __('Body'); ?></th>
			<th><?php echo __('Timelimit'); ?></th>
			<th><?php echo __('Passing Score'); ?></th>
			<th><?php echo __('Opened'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Modified'); ?></th>
			<th><?php echo __('Deleted'); ?></th>
			<th><?php echo __('Sort No'); ?></th>
			<th><?php echo __('Comment'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
	<?php foreach ($user['Content'] as $content): ?>
		<tr>
			<td><?php echo $content['id']; ?></td>
			<td><?php echo $content['group_id']; ?></td>
			<td><?php echo $content['course_id']; ?></td>
			<td><?php echo $content['user_id']; ?></td>
			<td><?php echo $content['title']; ?></td>
			<td><?php echo $content['url']; ?></td>
			<td><?php echo $content['kind']; ?></td>
			<td><?php echo $content['body']; ?></td>
			<td><?php echo $content['timelimit']; ?></td>
			<td><?php echo $content['passing_score']; ?></td>
			<td><?php echo $content['opened']; ?></td>
			<td><?php echo $content['created']; ?></td>
			<td><?php echo $content['modified']; ?></td>
			<td><?php echo $content['deleted']; ?></td>
			<td><?php echo $content['sort_no']; ?></td>
			<td><?php echo $content['comment']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'contents', 'action' => 'view', $content['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'contents', 'action' => 'edit', $content['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'contents', 'action' => 'delete', $content['id']), array('confirm' => __('Are you sure you want to delete # %s?', $content['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Content'), array('controller' => 'contents', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Records'); ?></h3>
	<?php if (!empty($user['Record'])): ?>
	<table cellpadding="0" cellspacing="0">
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
	<?php foreach ($user['Record'] as $record): ?>
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
<div class="related">
	<h3><?php echo __('Related Courses'); ?></h3>
	<?php if (!empty($user['Course'])): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Group Id'); ?></th>
			<th><?php echo __('Title'); ?></th>
			<th><?php echo __('Opened'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Modified'); ?></th>
			<th><?php echo __('Deleted'); ?></th>
			<th><?php echo __('Sort No'); ?></th>
			<th><?php echo __('Comment'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
	<?php foreach ($user['Course'] as $course): ?>
		<tr>
			<td><?php echo $course['id']; ?></td>
			<td><?php echo $course['group_id']; ?></td>
			<td><?php echo $course['title']; ?></td>
			<td><?php echo $course['opened']; ?></td>
			<td><?php echo $course['created']; ?></td>
			<td><?php echo $course['modified']; ?></td>
			<td><?php echo $course['deleted']; ?></td>
			<td><?php echo $course['sort_no']; ?></td>
			<td><?php echo $course['comment']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'courses', 'action' => 'view', $course['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'courses', 'action' => 'edit', $course['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'courses', 'action' => 'delete', $course['id']), array('confirm' => __('Are you sure you want to delete # %s?', $course['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Groups'); ?></h3>
	<?php if (!empty($user['Group'])): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __('Id'); ?></th>
			<th><?php echo __('Title'); ?></th>
			<th><?php echo __('Comment'); ?></th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Modified'); ?></th>
			<th><?php echo __('Deleted'); ?></th>
			<th><?php echo __('Status'); ?></th>
			<th><?php echo __('Logo'); ?></th>
			<th><?php echo __('Copyright'); ?></th>
			<th><?php echo __('Module'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
	<?php foreach ($user['Group'] as $group): ?>
		<tr>
			<td><?php echo $group['id']; ?></td>
			<td><?php echo $group['title']; ?></td>
			<td><?php echo $group['comment']; ?></td>
			<td><?php echo $group['created']; ?></td>
			<td><?php echo $group['modified']; ?></td>
			<td><?php echo $group['deleted']; ?></td>
			<td><?php echo $group['status']; ?></td>
			<td><?php echo $group['logo']; ?></td>
			<td><?php echo $group['copyright']; ?></td>
			<td><?php echo $group['module']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'groups', 'action' => 'view', $group['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'groups', 'action' => 'edit', $group['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'groups', 'action' => 'delete', $group['id']), array('confirm' => __('Are you sure you want to delete # %s?', $group['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
