<?php $this->start('css-embedded'); ?>
<!--[if !IE]><!-->
<style>
/* スマートフォン対応 */
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
	.responsive-table tbody td:nth-of-type(2):before { width: 100px; display: inline-block; content: "<?php echo __('種別').' : '?>";}
	.responsive-table tbody td:nth-of-type(3):before { content: "<?php echo __('学習開始日').' : '?>"; }
	.responsive-table tbody td:nth-of-type(4):before { content: "<?php echo __('前回学習日').' : '?>"; }
	.responsive-table tbody td:nth-of-type(5):before { content: "<?php echo __('学習時間').' : '?>"; }
	.responsive-table tbody td:nth-of-type(6):before { content: "<?php echo __('学習回数').' : '?>"; }
	.responsive-table tbody td:nth-of-type(7):before { content: "<?php echo __('理解度').' : '?>"; }
	
	.ib-col-center,
	.ib-col-date
	{
		text-align: left;
		width:100%;
	}
}

.content-label
{
	font-size: 22px;
	padding-bottom: 0px;
}

<?php if($this->action=='admin_record') {?>
.ib-navi-item
{
	display: none;
}

.ib-logo a
{
	pointer-events: none;
}
<?php }?>
</style>
<!--<![endif]-->
<?php $this->end(); ?>
<div class="contents index">
	<div class="ib-breadcrumb">
	<?php
	// 管理者が閲覧する場合、パンくずリストを表示しない
	if($this->action!='admin_record')
	{
		$this->Html->addCrumb('<< '.__('コース一覧'), array(
			'controller' => 'users_courses',
			'action' => 'index'
		));
		echo $this->Html->getCrumbs(' / ');
	}
	?>
	</div>

	<div class="panel panel-info">
	<div class="panel-heading"><?php echo h($course['Course']['title']); ?></div>
	<div class="panel-body">
	<?php if($course['Course']['introduction']!='') {?>
	<div class="well">
		<?php
		$introduction = $this->Text->autoLinkUrls($course['Course']['introduction'], array( 'target' => '_blank'));
		$introduction = nl2br($introduction);
		echo $introduction;
	?>
	</div>
	<?php }?>
	<table class="responsive-table">
		<thead>
			<tr>
				<th><?php echo __('コンテンツ名'); ?></th>
				<th class="ib-col-center"><?php echo __('種別'); ?></th>
				<th class="ib-col-date"><?php echo __('学習開始日'); ?></th>
				<th class="ib-col-date"><?php echo __('前回学習日'); ?></th>
				<th nowrap class="ib-col-center"><?php echo __('学習時間'); ?></th>
				<th nowrap class="ib-col-center"><?php echo __('学習回数'); ?></th>
				<th nowrap class="ib-col-center"><?php echo __('理解度'); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php foreach ($contents as $content): ?>
		<?php
		$icon			= ''; // アイコン用クラス
		$title_link		= ''; // コンテンツタイトル（リンク付き）
		$kind			= Configure::read('content_kind.'.$content['Content']['kind']); // 学習種別
		$understanding	= ''; // 理解度・テスト結果
		
		//debug($content);
		// コンテンツの種別
		switch($content['Content']['kind'])
		{
			case 'test': // テスト
				$icon  = 'glyphicon glyphicon-check text-danger';
				$title_link = $this->Html->link(
					$content['Content']['title'], array(
					'controller' => 'contents_questions',
					'action' => 'index',
					$content['Content']['id']
				));
				$kind  = Configure::read('content_kind.'.$content['Content']['kind']);

				// テスト結果が存在する場合、テスト結果へのリンクを出力
				if ($content['Record']['record_id'] != null)
				{
					$result = Configure::read('record_result.'.$content[0]['is_passed']);
					
					$understanding = $this->Html->link(
						$result, array(
						'controller' => 'contents_questions',
						'action' => 'record',
						$content['Content']['id'],
						$content['Record']['record_id']
					));
				}
				break;
			case 'file': // 配布資料
				// 配布資料のURL
				$url = $content['Content']['url'];
				
				// 相対URLの場合、絶対URLに変更する
				if(mb_substr($url, 0, 1)=='/')
					$url = FULL_BASE_URL.$url;
				
				$icon  = 'glyphicon glyphicon-file text-success';
				$title_link = $this->Html->link(
					$content['Content']['title'], 
					$url,
					array('target'=>'_blank'
				));
				break;
			default : // その他（学習）
				$icon  = 'glyphicon glyphicon-play-circle text-info';
				$title_link = $this->Html->link(
					$content['Content']['title'], array(
					'controller' => 'contents',
					'action' => 'view',
					$content['Content']['id']
				));
				$kind  =  __('学習'); // 一律学習と表記
				$understanding = h(Configure::read('record_understanding.'.$content[0]['understanding']));
				break;
		}
		
		// 学習履歴表示の場合、学習画面へのリンクを出力しない
		if($this->action=='admin_record')
			$title_link = h($content['Content']['title']);
		?>
		<?php if($content['Content']['kind']=='label') { // ラベルの場合、タイトルのみ表示 ?>
		<tr>
			<td colspan="7" class="content-label"><?php echo h($content['Content']['title']); ?>&nbsp;</td>
		</tr>
		<?php }else{?>
		<tr>
			<td><span class="<?php echo $icon; ?>"></span>&nbsp;<?php echo $title_link; ?>&nbsp;</td>
			<td class="ib-col-center" nowrap><?php echo h($kind); ?>&nbsp;</td>
			<td class="ib-col-date"><?php echo Utils::getYMD($content['Record']['first_date']); ?>&nbsp;</td>
			<td class="ib-col-date"><?php echo Utils::getYMD($content['Record']['last_date']); ?>&nbsp;</td>
			<td class="ib-col-center"><?php echo h(Utils::getHNSBySec($content['Record']['study_sec'])); ?>&nbsp;</td>
			<td class="ib-col-center"><?php echo h($content['Record']['study_count']); ?>&nbsp;</td>
			<td nowrap class="ib-col-center"><?php echo $understanding; ?></td>
		</tr>
		<?php }?>
	<?php endforeach; ?>
	</tbody>
	</table>

	</div>
	</div>
</div>
