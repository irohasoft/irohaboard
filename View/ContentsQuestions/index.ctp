<div class="contents-questions-index">
	<div class="breadcrumb">
	<?php
	// 管理者による学習履歴表示モードの場合、コース一覧リンクを表示しない
	if($is_admin_record)
	{
		$course_url = array('controller' => 'contents', 'action' => 'record', $record['Course']['id'], $record['Record']['user_id']);
	}
	else
	{
		$course_url = array('controller' => 'contents', 'action' => 'index', $content['Course']['id']);
		$this->Html->addCrumb(__('コース一覧'), array('controller' => 'users_courses', 'action' => 'index'));
	}
	
	$this->Html->addCrumb($content['Course']['title'], $course_url);
	$this->Html->addCrumb(h($content['Content']['title'])); // addCrumb 内でエスケープされない為、別途エスケープ
	echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	
	<div id="lblStudySec" class="btn btn-info"></div>
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
	var TIMELIMIT_SEC	= parseInt('<?php echo $content['Content']['timelimit'] ?>') * 60;	// 制限時間（単位：秒）
	var IS_RECORD		= '<?php echo $is_record ?>';										// テスト結果表示フラグ
	</script>
	<?php echo $this->Html->script('contents_questions.js?20190401');?>
	<?php $this->end(); ?>
	
	<!-- テスト結果ヘッダ表示 -->
	<?php if($is_record){ ?>
		<?php
			$result_color  = ($record['Record']['is_passed']==1) ? 'text-primary' : 'text-danger';
			$result_label  = ($record['Record']['is_passed']==1) ? __('合格') : __('不合格');
		?>
		<table class="result-table">
			<caption><?php echo __('テスト結果'); ?></caption>
			<tr>
				<td><?php echo __('合否'); ?></td>
				<td><div class="<?php echo $result_color; ?>"><?php echo $result_label; ?></div></td>
			</tr>
			<tr>
				<td><?php echo __('得点'); ?></td>
				<td><?php echo $record['Record']['score'].' / '.$record['Record']['full_score']; ?></td>
			</tr>
			<tr>
				<td><?php echo __('合格基準得点'); ?></td>
				<td><?php echo $record['Record']['pass_score']; ?></td>
			</tr>
		</table>
	<?php }?>
	
	<?php
		$question_index = 1; // 設問番号
		
		// 問題IDをキーに問題の成績が参照できる配列を作成
		$question_records = array();
		if($is_record)
		{
			foreach ($record['RecordsQuestion'] as $rec)
			{
				$question_records[$rec['question_id']] = $rec;
			}
		}
	?>
	<?php echo $this->Form->create('ContentsQuestion'); ?>
		<?php foreach ($contentsQuestions as $contentsQuestion){ ?>
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
			$correct_list	= explode(',', $question['correct']);		// 正解リスト
			$answer_list	= explode(',', @$question_records[$question_id]['answer']); // 選択した解答リスト
			
			foreach($option_list as $option)
			{
				// テスト結果履歴モードの場合、ラジオボタンを無効化
				$is_disabled = $is_record ? 'disabled' : '';
				
				// 複数選択(順不同)問題の場合
				if(count($correct_list) > 1)
				{
					$is_checked = (in_array($option_index, $answer_list)) ? " checked" : "";
					
					// 選択肢チェックボックス
					$option_tag .= sprintf('<input type="checkbox" value="%s" name="data[answer_%s][]" %s %s> %s<br>',
						$option_index, $question_id, $is_checked, $is_disabled, h($option));
				}
				else
				{
					$is_checked = (@$answer_list[0]==$option_index) ? 'checked' : '';
					// 選択肢ラジオボタン
					$option_tag .= sprintf('<input type="radio" value="%s" name="data[answer_%s]" %s %s> %s<br>',
							$option_index, $question_id, $is_checked, $is_disabled, h($option));
				}
				
				
				$option_index++;
			}
			

			//------------------------------//
			//	正解、解説情報を出力		//
			//------------------------------//
			$explain_tag	= ''; // 解説用タグ
			$correct_tag	= ''; // 正解用タグ
			
			// テスト結果表示モードの場合
			if($is_record)
			{
				$result_img		= (@$question_records[$question_id]['is_correct']=='1') ? 'correct.png' : 'wrong.png';

				// 正解番号から正解ラベルへ変換
				$correct_label = ''; // 正解ラベル
				foreach($correct_list as $correct_no)
				{
					$correct_label .= ($correct_label=='') ? $option_list[$correct_no - 1] : ', '.$option_list[$correct_no - 1];
				}

				$correct_tag	= sprintf('<p class="correct-text bg-success">正解 : %s</p><p>%s</p>',
					$correct_label, $this->Html->image($result_img, array('width'=>'60','height'=>'60')));
				
				// 解説の設定
				if($question['explain']!='')
				{
					$explain_tag = sprintf('<div class="correct-text bg-danger">%s</div>',
						$question['explain']);
				}
			}
			?>
			<div class="panel panel-info">
				<div class="panel-heading">問<?php echo $question_index;?></div>
				<div class="panel-body">
					<!--問題タイトル-->
					<h4><?php echo h($title) ?></h4>
					<div class="question-text bg-warning">
						<!--問題文-->
						<?php echo $body ?>
					</div>
					
					<div class="radio-group">
						<!--選択肢-->
						<?php echo $option_tag; ?>
					</div>
					<!--正誤画像-->
					<?php echo $correct_tag ?>
					<!--解説文-->
					<?php echo $explain_tag ?>
					<?php echo $this->Form->hidden('correct_'.$question_id, array('value' => $question['correct'])); ?>
				</div>
			</div>
			<?php $question_index++;?>
		<?php } ?>
		
		<?php
			echo '<div class="form-inline"><!--start-->';
			
			// テスト実施の場合のみ、採点ボタンを表示
			if (!$is_record)
			{
				echo $this->Form->hidden('study_sec');
				echo '<input type="button" value="採点" class="btn btn-primary btn-lg btn-score" onclick="$(\'#confirmModal\').modal()">';
				echo '&nbsp;';
			}
			
			echo '<input type="button" value="戻る" class="btn btn-default btn-lg" onclick="location.href=\''.Router::url($course_url).'\'">';
			echo '</div><!--end-->';
			echo $this->Form->end();
		?>
	<br>
</div>

<!--採点確認ダイアログ-->
<div class="modal fade" id="confirmModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">採点確認</h4>
			</div>
			<div class="modal-body">
				<p>採点してよろしいですか？</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
				<button type="button" class="btn btn-primary btn-score" onclick="sendData();">採点</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
