<?php $this->start('css-embedded'); ?>
<?php
$is_admin_record = $this->isAdminPage() && $this->isRecordPage();
?>
<style>
@media only screen and (max-width:800px)
{	.responsive-table tbody td:nth-of-type(2):before { width: 100px; display: inline-block; content: "<?= __('種別').' : '?>";}
	.responsive-table tbody td:nth-of-type(3):before { content: "<?= __('学習開始日').' : '?>"; }
	.responsive-table tbody td:nth-of-type(4):before { content: "<?= __('前回学習日').' : '?>"; }
	.responsive-table tbody td:nth-of-type(5):before { content: "<?= __('学習時間').' : '?>"; }
	.responsive-table tbody td:nth-of-type(6):before { content: "<?= __('学習回数').' : '?>"; }
	.responsive-table tbody td:nth-of-type(7):before { content: "<?= __('理解度').' : '?>"; }
	.responsive-table tbody td:nth-of-type(8):before { content: "<?= __('完了').' : '?>"; }
}

<?php if($is_admin_record) { // 管理者による学習履歴表示の場合、メニューを表示しない ?>
.ib-navi-item
{
	display					: none;
}

.ib-logo a
{
	pointer-events			: none;
}
<?php }?>
</style>
<?php $this->end(); ?>
<div class="contents-index">
	<div class="ib-breadcrumb">
	<?php
	// 管理者による学習履歴表示の場合、パンくずリストを表示しない
	if(!$is_admin_record)
	{
		$this->Html->addCrumb('<< '.__('コース一覧'), [
			'controller' => 'users_courses',
			'action' => 'index'
		]);
		echo $this->Html->getCrumbs(' / ');
	}
	?>
	</div>

	<div class="panel panel-info">
	<div class="panel-heading"><?= h($course['Course']['title']); ?></div>
	<div class="panel-body">
	<?php if($course['Course']['introduction'] != '') {?>
	<div class="well">
	<?php
		$introduction = $this->Text->autoLinkUrls($course['Course']['introduction'], [ 'target' => '_blank']);
		$introduction = nl2br($introduction);
		echo $introduction;
	?>
	</div>
	<?php }?>
	<table class="responsive-table">
		<thead>
			<tr>
				<th><?= __('コンテンツ名'); ?></th>
				<th class="ib-col-center"><?= __('種別'); ?></th>
				<th class="ib-col-date"><?= __('学習開始日'); ?></th>
				<th class="ib-col-date"><?= __('前回学習日'); ?></th>
				<th nowrap class="ib-col-center"><?= __('学習時間'); ?></th>
				<th nowrap class="ib-col-center"><?= __('学習回数'); ?></th>
				<th nowrap class="ib-col-center"><?= __('理解度'); ?></th>
				<th nowrap class="ib-col-center"><?= __('完了'); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php foreach ($contents as $content): ?>
	<?php
		$icon			= ''; // アイコン用クラス
		$title_link		= ''; // コンテンツタイトル（リンク付き）
		$kind			= Configure::read('content_kind.'.$content['Content']['kind']); // 学習種別
		$understanding	= ''; // 理解度・テスト結果
		
		// コンテンツの種別
		switch($content['Content']['kind'])
		{
			case 'test': // テスト
				$icon  = 'glyphicon glyphicon-check text-danger';
				$title_link = $this->Html->link(
					$content['Content']['title'], [
					'controller' => 'contents_questions',
					'action' => 'index',
					$content['Content']['id']
				]);
				$kind  = Configure::read('content_kind.'.$content['Content']['kind']);

				// テスト結果が存在する場合、テスト結果へのリンクを出力
				if ($content['Record']['record_id'] != null)
				{
					$result = Configure::read('record_result.'.$content[0]['is_passed']);
					
					$understanding = $this->Html->link(
						$result, [
						'controller' => 'contents_questions',
						'action' => 'record',
						$content['Content']['id'],
						$content['Record']['record_id']
					]);
				}
				break;
			case 'file': // 配布資料
				// 配布資料のURL
				$url = $content['Content']['url'];
				
				// 相対URLの場合、絶対URLに変更する
				if(mb_substr($url, 0, 1) == '/')
					$url = FULL_BASE_URL.$url;
				
				$icon  = 'glyphicon glyphicon-file text-success';
				$title_link = $this->Html->link(
					$content['Content']['title'], 
					$url,
					[
						'target'=>'_blank',
						'download' => $content['Content']['file_name']
					]
				);
				break;
			default : // その他（学習）
				$icon  = 'glyphicon glyphicon-play-circle text-info';
				$title_link = $this->Html->link(
					$content['Content']['title'], [
					'controller' => 'contents',
					'action' => 'view',
					$content['Content']['id']
				]);
				$kind  =  __('学習'); // 一律学習と表記
				$understanding = h(Configure::read('record_understanding.'.$content[0]['understanding']));
				break;
		}
		
		// 管理者による学習履歴表示の場合、学習画面へのリンクを出力しない
		if($is_admin_record)
			$title_link = h($content['Content']['title']);
		
		if($content['Content']['status'] == 0)
			$title_link .= ' <span class="status-closed">(非公開)</span>';
		
		//debug($content);
		?>
		<?php if($content['Content']['kind'] == 'label') { // ラベルの場合、タイトルのみ表示 ?>
		<tr>
			<td colspan="8" class="content-label"><?= h($content['Content']['title']); ?>&nbsp;</td>
		</tr>
		<?php }else{?>
		<tr>
			<td><span class="<?= $icon; ?>"></span>&nbsp;<?= $title_link; ?>&nbsp;</td>
			<td class="ib-col-center" nowrap><?= h($kind); ?>&nbsp;</td>
			<td class="ib-col-date"><?= Utils::getYMD($content['Record']['first_date']); ?>&nbsp;</td>
			<td class="ib-col-date"><?= Utils::getYMD($content['Record']['last_date']); ?>&nbsp;</td>
			<td class="ib-col-center"><?= str_replace('00:00:00', '', Utils::getHNSBySec($content['Record']['study_sec'])); ?>&nbsp;</td>
			<td class="ib-col-center"><?= h($content['Record']['study_count']); ?>&nbsp;</td>
			<td nowrap class="ib-col-center"><?= $understanding; ?></td>
			<td class="ib-col-center"><?= ($content['CompleteRecord']['is_complete'] == 1) ? '<span class="glyphicon glyphicon-ok text-muted"></span>' : ''; ?></td>
		</tr>
		<?php }?>
	<?php endforeach; ?>
	</tbody>
	</table>

	</div>
	</div>
</div>
