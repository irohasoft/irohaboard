<div class="usersCourses view">
<div class="ib-page-title"><?php echo __('Users Course'); ?></div>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($usersCourse['UsersCourse']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($usersCourse['User']['name'], array('controller' => 'users', 'action' => 'view', $usersCourse['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course'); ?></dt>
		<dd>
			<?php echo $this->Html->link($usersCourse['Course']['title'], array('controller' => 'courses', 'action' => 'view', $usersCourse['Course']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Started'); ?></dt>
		<dd>
			<?php echo h($usersCourse['UsersCourse']['started']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ended'); ?></dt>
		<dd>
			<?php echo h($usersCourse['UsersCourse']['ended']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($usersCourse['UsersCourse']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($usersCourse['UsersCourse']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($usersCourse['UsersCourse']['comment']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Users Course'), array('action' => 'edit', $usersCourse['UsersCourse']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Users Course'), array('action' => 'delete', $usersCourse['UsersCourse']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $usersCourse['UsersCourse']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Users Courses'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users Course'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
	</ul>
</div>
