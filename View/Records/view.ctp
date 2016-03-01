<div class="records view">
<div class="ib-page-title"><?php echo __('Record'); ?></div>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($record['Record']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($record['Group']['title'], array('controller' => 'groups', 'action' => 'view', $record['Group']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course'); ?></dt>
		<dd>
			<?php echo $this->Html->link($record['Course']['title'], array('controller' => 'courses', 'action' => 'view', $record['Course']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($record['User']['name'], array('controller' => 'users', 'action' => 'view', $record['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
			<?php echo $this->Html->link($record['Content']['title'], array('controller' => 'contents', 'action' => 'view', $record['Content']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Score'); ?></dt>
		<dd>
			<?php echo h($record['Record']['score']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Borderline'); ?></dt>
		<dd>
			<?php echo h($record['Record']['borderline']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Passed'); ?></dt>
		<dd>
			<?php echo h($record['Record']['is_passed']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Complete'); ?></dt>
		<dd>
			<?php echo h($record['Record']['is_complete']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Progress'); ?></dt>
		<dd>
			<?php echo h($record['Record']['progress']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Understanding'); ?></dt>
		<dd>
			<?php echo h($record['Record']['understanding']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Study Sec'); ?></dt>
		<dd>
			<?php echo h($record['Record']['study_sec']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($record['Record']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Record'), array('action' => 'edit', $record['Record']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $record['Record']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $record['Record']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Records'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Record'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contents'), array('controller' => 'contents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Content'), array('controller' => 'contents', 'action' => 'add')); ?> </li>
	</ul>
</div>
