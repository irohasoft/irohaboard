<?php echo $this->element('admin_menu');?>
<style type="text/css">
.radio-group{
	position: relative;
}
.list-dot{
	margin-top : .3rem !important;
	margin-left: -1.25rem !important;
}

.list-label{
	margin-right : 2rem !important;
}

</style>

<div class="admin-users-index full-view">
	<div class="ib-page-title"><?php echo __('ユーザ一覧'); ?></div>
	<div class="buttons_container">
		<?php if($loginedUser['role']=='admin'){ ?>
		<button type="button" class="btn btn-primary btn-export" onclick="location.href='<?php echo Router::url(array('action' => 'export')) ?>'">エクスポート</button>
		<button type="button" class="btn btn-primary btn-import" onclick="location.href='<?php echo Router::url(array('action' => 'import')) ?>'">インポート</button>
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
		<?php }?>
	</div>
	<div class="ib-horizontal">
		<?php
			echo $this->Form->create('User');
			echo $this->Form->input('group_id',		array(
				'label' => 'グループ : ',
				'options'=>$groups,
				'selected'=>$group_id,
				'empty' => '全て',
				'required'=>false,
				'class' => 'form-control',
				'onchange' => 'submit(this.form);'
			));
			echo $this->Form->input('username',		array('label' => 'ログインID : ', 'required' => false));
			echo $this->Form->input('name',			array('label' => '氏名 or ふりがな: '  , 'required' => false));
		?>
		<input type="submit" class="btn btn-info btn-add" value="検索">
	</div>
	<br>
	<div class="ib-horizontal form-check radio-group">
		<?php
			echo $this->Form->input('role', array(
				'legend' => false,
				'type' => 'radio',
				'options' => $roles,
				'value' => $role,
				'onchange' => 'submit(this.form);'
			));
		?>
	</div>
	<?php echo $this->Form->end(); ?>
	<br>

	<table>
		<thead>
		<tr>
		    <th nowrap style="width: 40px;"><?php echo __('No'); ?></th>
            <th class="text-center"><?php echo __('写真'); ?></th>
			<th nowrap><?php echo $this->Paginator->sort('username', 'ログインID'); ?></th>
			<th nowrap class="col-width"><?php echo $this->Paginator->sort('name', '氏名'); ?></th>
			<th nowrap ><?php echo $this->Paginator->sort('name_furigana', 'ふりがな'); ?></th>
			<th nowrap><?php echo $this->Paginator->sort('role', '権限'); ?></th>
			<th nowrap><?php echo __('担当'); ?></th>

			<th class="ib-col-datetime"><?php echo $this->Paginator->sort('last_logined', '最終ログイン日時'); ?></th>
			<!-- <th class="ib-col-datetime"><?php echo $this->Paginator->sort('created', '作成日時'); ?></th> -->
			<?php if($loginedUser['role']=='admin') {?>
			<th class="ib-col-action"><?php echo __('Actions'); ?></th>
			<?php }?>
		</tr>
		</thead>
		<tbody id="user-list">
  	<?php $cnt = 1;?>
		<?php foreach ($user_list as $user): ?>
		<tr>
			<td nowrap><?php echo h($cnt++);?></td>
  	  <td align="center">
  	  	<?php
					$img_src = $this->Html->url(array(
  	  			"controller" => "users",
  	  			"action" => "show_picture",
  	  			$user['User']['id']
					), false);
					echo $this->Html->link(
						'<img src="'.$img_src.'" height="60" alt="'.h($user['User']['name']).'"/>',
						array(
							'controller' => 'users',
							'action' => 'admin_edit',$user['User']['id']
						),
						array('escape' => false)
					);
				?>
  	  </td>
			<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
			<td><?php echo h($user['User']['name']); ?></td>
			<td><?php echo h($user['User']['name_furigana']); ?></td>
			<td nowrap><?php echo h(Configure::read('user_role.'.$user['User']['role'])); ?>&nbsp;</td>
			<?php $group_id = $user['User']['group_id']; ?>
			<td>
				<div class="reader" title="<?php echo h($groups[$group_id]); ?>">
					<p><?php echo h($groups[$group_id]); ?></p>
	  			</div>
	  		</td>
			<td class="ib-col-datetime"><?php echo h(Utils::getYMDHN($user['User']['last_logined'])); ?>&nbsp;</td>
			<!-- <td class="ib-col-datetime"><?php echo h(Utils::getYMDHN($user['User']['created'])); ?>&nbsp;</td> -->
			<?php if($loginedUser['role']=='admin') {?>
			<td class="ib-col-action">
				<button type="button" class="btn btn-success"
					onclick="window.open('<?php echo Router::url(array('action' => 'edit', $user['User']['id']))?>', '_blank','width=900,height=600,resizable=no')">編集</button>
			</td>
			<?php }?>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('paging');?>
</div>
