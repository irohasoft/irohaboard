<?php //echo ($this->action=='admin_record') ? '' : $this->element('menu');?>
<?php $this->start('css-embedded'); ?>
<!--[if !IE]><!-->
<style>
@media only screen and (max-width:800px)
{
	.responsive-table
	{
		display: block;
	}

	.responsive-table thead
	{
		display: none;
	}

	.responsive-table tbody
	{
		display: block;
	}

	.responsive-table tbody tr
	{
		display: block;
		margin-bottom: 1.5em;
	}

	.responsive-table tbody th,
	.responsive-table tbody td
	{
		display: list-item;
		list-style: none;
		border: none;
	}

	.responsive-table tbody th
	{
		margin-bottom: 5px;
		list-style-type: none;
		color: #fff;
		background: #000;
	}

	.responsive-table tbody td
	{
		margin-left: 10px;
		padding: 0;
	}

	.responsive-table a
	{
		font-size: 18px;
		font-weight: bold;
	}

	.responsive-table tbody td:before { width: 100px; display: inline-block;}
	.responsive-table tbody td:nth-of-type(2):before { width: 100px; display: inline-block; content: "種別 : ";}
	.responsive-table tbody td:nth-of-type(3):before { content: "学習開始日 : "; }
	.responsive-table tbody td:nth-of-type(4):before { content: "前回学習日 : "; }
	.responsive-table tbody td:nth-of-type(5):before { content: "学習時間 : "; }
	.responsive-table tbody td:nth-of-type(6):before { content: "学習回数 : "; }
	.responsive-table tbody td:nth-of-type(7):before { content: "理解度 : "; }
	
	.ib-col-center,
	.ib-col-date
	{
		text-align: left;
		width:100%;
	}

}
.content-label
{
	/*
	background: #999;
	color: #fff;
	*/
	font-size: 22px;
	padding-bottom: 0px;
}

<?php if($this->action=='admin_record'){?>
.ib-navi-item
{
	display: none;
}
<?php }?>

</style>
<!--<![endif]-->
<?php $this->end(); ?>
<div class="contents index">
	<div class="ib-breadcrumb">
	<?php
	
	if($this->action!='admin_record')
	{
		$this->Html->addCrumb('<< TOP', array(
				'controller' => 'users_courses',
				'action' => 'index'
		));
	}

	echo $this->Html->getCrumbs(' / ');
	//debug($contents);

	?>
	</div>

	<div class="panel panel-info">
	<div class="panel-heading"><?php echo $course_name; ?></div>
	<div class="panel-body">
	<table class="responsive-table">
		<thead>
			<tr>
				<th><?php echo __('コンテンツ名'); ?></th>
				<th class="ib-col-center"><?php echo __('種別'); ?></th>
				<th class="ib-col-date"><?php echo __('学習開始日'); ?></th>
				<th class="ib-col-date"><?php echo __('前回学習日'); ?></th>
				<th class="ib-col-center"><?php echo __('学習時間'); ?></th>
				<th class="ib-col-center"><?php echo __('学習回数'); ?></th>
				<th class="ib-col-center"><?php echo __('理解度'); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php foreach ($contents as $content): ?>
	<tr>
		<?php
		if($content['Content']['kind']=='label') // ラベル
		{
			echo '<td colspan="7" class="content-label">'.h($content['Content']['title']).'</td>';
		}
		else
		{
			if($this->action=='admin_record')
			{// 学習履歴表示モードの場合
				echo 
					'<td>'.h($content['Content']['title']).'</td>'.
					'<td>'.h(Configure::read('content_kind.'.$content['Content']['kind'])).'</td>';
			}
			else
			{
				if ($content['Content']['kind'] == 'test') // テスト
				{
					echo '<td>' .
							$this->Html->link($content['Content']['title'],
									array(
											'controller' => 'contents_questions',
											'action' => 'index',
											$content['Content']['id']
									)) . "</td>";
					echo '<td class="ib-col-center">テスト</td>';
				}
				else if($content['Content']['kind'] == 'file') // 配布資料
				{
					// 配布資料のURL
					$url = $content['Content']['url'];
					
					// 相対URLの場合、絶対URLに変更する
					if(mb_substr($url, 0, 1)=='/')
						$url = FULL_BASE_URL.$url;
					
					echo '<td>' .
							$this->Html->link($content['Content']['title'], $url, array('target'=>'_blank')). "</td>";
					echo '<td class="ib-col-center" nowrap>配布資料</td>';
				}
				else
				{
					echo '<td>' .
							$this->Html->link($content['Content']['title'],
									array(
											'controller' => 'contents',
											'action' => 'view',
											$content['Content']['id']
									)) . "</td>";

					echo '<td class="ib-col-center">学習</td>';
				}
			}

			//debug($content);
			?>
			<td class="ib-col-center"><?php echo h($content['Record']['first_date']); ?>&nbsp;</td>
			<td class="ib-col-date"><?php echo h($content['Record']['last_date']); ?>&nbsp;</td>
			<td class="ib-col-center"><?php echo h(Utils::getHNSBySec($content['Record']['study_sec'])); ?>&nbsp;</td>
			<td class="ib-col-center"><?php echo h($content['Record']['study_count']); ?>&nbsp;</td>
			<td class="ib-col-center">
			<?php
			if ($content['Content']['kind'] == 'test')
			{
				if ($content['Record']['record_id'] == null)
				{
					echo '';
				}
				else
				{
					$result = ($content[0]['is_passed'] == 1) ? __('合格') : __('不合格');
					
					if($this->action=='admin_record')
					{// 学習履歴表示モードの場合
						echo $result;
					}
					else
					{// 通常の学習の場合
						echo $this->Html->link($result,
								array(
										'controller' => 'contents_questions',
										'action' => 'record',
										$content['Content']['id'],
										$content['Record']['record_id']
								));
					}
				}
			}
			else
			{
				echo h(Configure::read('record_understanding.'.$content[0]['understanding']));
			}
			echo '</td>';
		}
		?>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>

	</div>
	</div>
</div>
