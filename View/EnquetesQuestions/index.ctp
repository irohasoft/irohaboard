<?php $this->start('css-embedded'); ?>
<style type='text/css'>
	<?php if($is_admin_record) { // 管理者による学習履歴表示モードの場合、ロゴのリンクを無効化 ?>
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
<?php $this->end(); ?>

<?php $this->start('script-embedded'); ?>
<script>
	var IS_RECORD		= '<?= $is_record ?>';										// テスト結果表示フラグ

</script>
<?= $this->Html->script('contents_enquetes.js?20250201');?>
<?php $this->end(); ?>
<div class="contents-questions-index">
	<div class="breadcrumb">
	<?php
	// 管理者による学習履歴表示モードの場合、コース一覧リンクを表示しない
	if($is_admin_record)
	{
		$course_url = ['controller' => 'contents', 'action' => 'record', $record['Course']['id'], $record['Record']['user_id']];
	}
	else
	{
		$course_url = ['controller' => 'contents', 'action' => 'index', $content['Course']['id']];
		$this->Html->addCrumb(
			'<span class="glyphicon glyphicon-book" aria-hidden="true"></span> コース一覧',
			['controller' => 'users_courses','action' => 'index'],
			['escape' => false]
		);
	}
	
	$this->Html->addCrumb($content['Course']['title'], $course_url);
	$this->Html->addCrumb(h($content['Content']['title'])); // addCrumb 内でエスケープされない為、別途エスケープ
	echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	<div id="lblStudySec" class="btn btn-info"></div>
	
	<!-- 問題一覧 -->
	<?php
		$question_index = 1; // 設問番号
		
		// 問題IDをキーに問題の成績が参照できる配列を作成
		$question_records = [];
		
		if($is_record)
		{
			foreach ($record['RecordsQuestion'] as $rec)
			{
				$question_records[$rec['question_id']] = $rec;
			}
		}
		
		echo $this->Form->create('ContentsQuestion');
	?>
		<?php foreach ($contentsQuestions as $contentsQuestion): ?>
			<?php
			$question		= $contentsQuestion['ContentsQuestion'];	// 問題情報
			$title			= $question['title'];						// 問題のタイトル
			$body			= $question['body'];						// 問題文
			$question_id	= $question['id'];							// 問題ID
			
			//------------------------------//
			//	選択肢用の出力タグの生成	//
			//------------------------------//
			$option_tag		= '';										// 選択肢用の出力タグ
			$option_index	= 1;										// 選択肢番号
			$option_list	= explode('|', $question['options']);		// 選択肢リスト
			$answer_list	= [];										// 選択した解答リスト
			
			// 解答済みの場合、解答リストを作成
			if(isset($question_records[$question_id]))
				$answer_list = explode(',', $question_records[$question_id]['answer']);
			
			$question_type	= $contentsQuestion['ContentsQuestion']['question_type']; // 問題形式
			
			switch($question_type)
			{
				case 'text':
					$option_tag  = $is_record ? '<div class="well">'.nl2br(h($question_records[$question_id]['answer'])).'</div>' : sprintf('<textarea name="answer_%s" class="form-control" rows="6" maxlength="1000"></textarea><br>', $question_id);
					break;
				case 'single':
					foreach($option_list as $option)
					{
						$is_checked = '';
				
						// テスト結果履歴モードの場合、ラジオボタンを無効化
						$is_disabled = $is_record ? 'disabled' : '';
						
						// 解答リストがある場合
						if(count($answer_list) > 0)
							$is_checked = ($answer_list[0] == $option_index) ? 'checked' : '';

						// 選択肢ラジオボタン
						$option_tag .= sprintf('<input type="radio" value="%s" name="data[answer_%s]" %s %s> %s<br>',
								$option_index, $question_id, $is_checked, $is_disabled, h($option));
						
						$option_index++;
					}
			}

			?>
			<div class="panel panel-info question question-<?= $question_index;?>">
				<div class="panel-heading"><?= __('問').$question_index;?></div>
				<div class="panel-body">
					<!--問題タイトル-->
					<h4><?= h($title) ?></h4>
					<div class="question-text bg-warning">
						<!--問題文-->
						<?= $body ?>
					</div>
					
					<div class="radio-group">
						<!--選択肢-->
						<?= $option_tag; ?>
					</div>
				</div>
			</div>
			<?php $question_index++;?>
		<?php endforeach; ?>
		
		<?php
			echo '<div class="form-inline"><!--start-->';
			
			// テスト実施の場合のみ、採点ボタンを表示
			if (!$is_record)
			{
				echo $this->Form->hidden('study_sec');
				echo '<input type="button" value="'.__('送信').'" class="btn btn-primary btn-lg btn-score" onclick="showConfirm()">';
				echo '&nbsp;';
			}
			
			echo '<input type="button" value="'.__('戻る').'" class="btn btn-default btn-lg" onclick="location.href=\''.Router::url($course_url).'\'">';
			echo '</div><!--end-->';
			echo $this->Form->end();
		?>
	<br>
</div>
	
<!--送信確認ダイアログ-->
<div class="modal fade" id="confirmModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title"><?= __('送信確認');?></h4>
			</div>
			<div class="modal-body">
				<p class="answer-incomplete text-danger"><b>※未回答の項目があります。</b></p>
				<p><?= __('送信してよろしいですか？');?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= __('キャンセル');?></button>
				<button type="button" class="btn btn-primary btn-score" onclick="sendData();"><?= __('送信');?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
