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
	var TIMELIMIT_SEC	= parseInt('<?= $content['Content']['timelimit'] ?>') * 60;	// 制限時間（単位：秒）
	var IS_RECORD		= '<?= $is_record ?>';										// テスト結果表示フラグ
</script>
<?= $this->Html->script('contents_questions.js?20190401');?>
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
		$this->Html->addCrumb(__('コース一覧'), ['controller' => 'users_courses', 'action' => 'index']);
	}
	
	$this->Html->addCrumb($content['Course']['title'], $course_url);
	$this->Html->addCrumb(h($content['Content']['title'])); // addCrumb 内でエスケープされない為、別途エスケープ
	echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	<div id="lblStudySec" class="btn btn-info"></div>
	<!-- テスト結果ヘッダ表示 -->
	<?php if($is_record){ ?>
		<?php
			$result_color  = ($record['Record']['is_passed'] == 1) ? 'text-primary' : 'text-danger';
			$result_label  = ($record['Record']['is_passed'] == 1) ? __('合格') : __('不合格');
		?>
		<table class="result-table">
			<caption><?= __('テスト結果'); ?></caption>
			<tr>
				<td><?= __('合否'); ?></td>
				<td><div class="<?= $result_color; ?>"><?= $result_label; ?></div></td>
			</tr>
			<tr>
				<td><?= __('得点'); ?></td>
				<td><?= $record['Record']['score'].' / '.$record['Record']['full_score']; ?></td>
			</tr>
			<tr>
				<td><?= __('合格基準得点'); ?></td>
				<td><?= ($record['Record']['pass_score']) ? $record['Record']['pass_score'] : __('設定されていません'); ?></td>
			</tr>
		</table>
	<?php }?>
	
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
			$correct_list	= explode(',', $question['correct']);		// 正解リスト
			$answer_list	= [];										// 選択した解答リスト
			
			// 解答済みの場合、解答リストを作成
			if(isset($question_records[$question_id]))
				$answer_list = explode(',', $question_records[$question_id]['answer']);
			
			foreach($option_list as $option)
			{
				$is_checked = '';
				
				// テスト結果履歴モードの場合、ラジオボタンを無効化
				$is_disabled = $is_record ? 'disabled' : '';
				
				// 複数選択(順不同)問題の場合
				if(count($correct_list) > 1)
				{
					$is_checked = (in_array($option_index, $answer_list)) ? ' checked' : '';
					
					// 選択肢チェックボックス
					$option_tag .= sprintf('<input type="checkbox" value="%s" name="data[answer_%s][]" %s %s> %s<br>',
						$option_index, $question_id, $is_checked, $is_disabled, h($option));
				}
				else
				{
					// 解答リストがある場合
					if(count($answer_list) > 0)
						$is_checked = ($answer_list[0] == $option_index) ? 'checked' : '';
					
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
			$result_tag		= ''; // 正誤用タグ
			$is_correct		= false; // 正誤
			
			// テスト結果表示モードの場合
			if($is_record)
			{
				// 正誤判定
				if(isset($question_records[$question_id]['is_correct']))
					$is_correct = ($question_records[$question_id]['is_correct'] == '1');
				
				// 不正解時の表示モード
				$wrong_mode	= $content['Content']['wrong_mode'];
				
				// 正解番号から正解ラベルへ変換
				$correct_label = ''; // 正解ラベル
				
				foreach($correct_list as $correct_no)
				{
					$correct_label .= ($correct_label == '') ? $option_list[$correct_no - 1] : ', '.$option_list[$correct_no - 1];
				}
				
				// 正解時は、解説のみを表示
				if($is_correct)
				{
					$result_tag  = sprintf('<p>%s<span class="result-currect">%s</span></p>', $this->Html->image('correct.png', ['width'=>'60','height'=>'60']), __('正解'));
					$explain_tag = getExplain($question['explain']);
				}
				else
				{
					$result_tag  = sprintf('<p>%s<span class="result-wrong">%s</span></p>', $this->Html->image('wrong.png', ['width'=>'60','height'=>'60']), __('不正解'));
					
					// 不正解時の表示
					switch($wrong_mode)
					{
						case 0: // 正解と解説を表示しない
							break;
						case 1: // 正解と解説を表示する
							$correct_tag = sprintf('<p class="correct-text bg-success">%s : %s</p>',__('正解'), $correct_label);
							$explain_tag = getExplain($question['explain']);
							break;
						case 2: // 解説のみ表示する
							$explain_tag = getExplain($question['explain']);
							break;
					}
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
					<!--正誤画像-->
					<?= $result_tag ?>
					<!--正解-->
					<?= $correct_tag ?>
					<!--解説文-->
					<?= $explain_tag ?>
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
				echo '<input type="button" value="'.__('採点').'" class="btn btn-primary btn-lg btn-score" onclick="$(\'#confirmModal\').modal()">';
				echo '&nbsp;';
			}
			
			echo '<input type="button" value="'.__('戻る').'" class="btn btn-default btn-lg" onclick="location.href=\''.Router::url($course_url).'\'">';
			echo '</div><!--end-->';
			echo $this->Form->end();
		?>
	<br>
</div>
<?php 
function getExplain($explain)
{
	$tag = '';
	
	$check = str_replace(['<p>','</p>','<br>'], '', $explain);
	
	// pタグ、brタグのみの場合、解説を表示しない
	if($check != '')
	{
		$tag = sprintf('<div class="correct-text bg-danger">%s : %s</div>', __('解説'), $explain);
	}
	
	return $tag;
}
?>
<!--採点確認ダイアログ-->
<div class="modal fade" id="confirmModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title"><?= __('採点確認');?></h4>
			</div>
			<div class="modal-body">
				<p><?= __('採点してよろしいですか？');?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= __('キャンセル');?></button>
				<button type="button" class="btn btn-primary btn-score" onclick="sendData();"><?= __('採点');?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
