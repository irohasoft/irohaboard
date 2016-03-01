<div class="contentsQuestions view">
<div class="ib-page-title"><?php echo __('Contents Question'); ?></div>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($contentsQuestion['Group']['title'], array('controller' => 'groups', 'action' => 'view', $contentsQuestion['Group']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content Id'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['content_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Question Type'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['question_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Body'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['body']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['image']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Options'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['options']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Correct'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['correct']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Point'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['point']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['comment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sort No'); ?></dt>
		<dd>
			<?php echo h($contentsQuestion['ContentsQuestion']['sort_no']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Contents Question'), array('action' => 'edit', $contentsQuestion['ContentsQuestion']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Contents Question'), array('action' => 'delete', $contentsQuestion['ContentsQuestion']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $contentsQuestion['ContentsQuestion']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Contents Questions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contents Question'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
	</ul>
</div>
