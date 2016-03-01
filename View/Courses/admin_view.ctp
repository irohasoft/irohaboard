<div class="courses view">
<div class="ib-page-title"><?php echo __('Course'); ?></div>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($course['Course']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($course['Group']['title'], array('controller' => 'groups', 'action' => 'view', $course['Group']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($course['Course']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Opened'); ?></dt>
		<dd>
			<?php echo h($course['Course']['opened']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($course['Course']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($course['Course']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deleted'); ?></dt>
		<dd>
			<?php echo h($course['Course']['deleted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sort No'); ?></dt>
		<dd>
			<?php echo h($course['Course']['sort_no']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($course['Course']['comment']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Course'), array('action' => 'edit', $course['Course']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Course'), array('action' => 'delete', $course['Course']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $course['Course']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contents'), array('controller' => 'contents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Content'), array('controller' => 'contents', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contents Questions'), array('controller' => 'contents_questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contents Question'), array('controller' => 'contents_questions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Records'), array('controller' => 'records', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Record'), array('controller' => 'records', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Contents'); ?></h3>
	<?php if (!empty($course['Content'])): ?>
	<table cellpadding = "0" cellspacing = "0">
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
	<?php foreach ($course['Content'] as $content): ?>
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
	<h3><?php echo __('Related Contents Questions'); ?></h3>
	<?php if (!empty($course['ContentsQuestion'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Course Id'); ?></th>
		<th><?php echo __('Question Type'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Body'); ?></th>
		<th><?php echo __('Image'); ?></th>
		<th><?php echo __('Options'); ?></th>
		<th><?php echo __('Correct'); ?></th>
		<th><?php echo __('Point'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Sort No'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($course['ContentsQuestion'] as $contentsQuestion): ?>
		<tr>
			<td><?php echo $contentsQuestion['id']; ?></td>
			<td><?php echo $contentsQuestion['group_id']; ?></td>
			<td><?php echo $contentsQuestion['course_id']; ?></td>
			<td><?php echo $contentsQuestion['question_type']; ?></td>
			<td><?php echo $contentsQuestion['title']; ?></td>
			<td><?php echo $contentsQuestion['body']; ?></td>
			<td><?php echo $contentsQuestion['image']; ?></td>
			<td><?php echo $contentsQuestion['options']; ?></td>
			<td><?php echo $contentsQuestion['correct']; ?></td>
			<td><?php echo $contentsQuestion['point']; ?></td>
			<td><?php echo $contentsQuestion['comment']; ?></td>
			<td><?php echo $contentsQuestion['created']; ?></td>
			<td><?php echo $contentsQuestion['modified']; ?></td>
			<td><?php echo $contentsQuestion['sort_no']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'contents_questions', 'action' => 'view', $contentsQuestion['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'contents_questions', 'action' => 'edit', $contentsQuestion['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'contents_questions', 'action' => 'delete', $contentsQuestion['id']), array('confirm' => __('Are you sure you want to delete # %s?', $contentsQuestion['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Contents Question'), array('controller' => 'contents_questions', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Records'); ?></h3>
	<?php if (!empty($course['Record'])): ?>
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
	<?php foreach ($course['Record'] as $record): ?>
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
	<h3><?php echo __('Related Users'); ?></h3>
	<?php if (!empty($course['User'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Username'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Role'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th><?php echo __('Last Logined'); ?></th>
		<th><?php echo __('Started'); ?></th>
		<th><?php echo __('Ended'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Deleted'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($course['User'] as $user): ?>
		<tr>
			<td><?php echo $user['id']; ?></td>
			<td><?php echo $user['group_id']; ?></td>
			<td><?php echo $user['username']; ?></td>
			<td><?php echo $user['password']; ?></td>
			<td><?php echo $user['name']; ?></td>
			<td><?php echo $user['role']; ?></td>
			<td><?php echo $user['email']; ?></td>
			<td><?php echo $user['comment']; ?></td>
			<td><?php echo $user['last_logined']; ?></td>
			<td><?php echo $user['started']; ?></td>
			<td><?php echo $user['ended']; ?></td>
			<td><?php echo $user['created']; ?></td>
			<td><?php echo $user['modified']; ?></td>
			<td><?php echo $user['deleted']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users', 'action' => 'delete', $user['id']), array('confirm' => __('Are you sure you want to delete # %s?', $user['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
