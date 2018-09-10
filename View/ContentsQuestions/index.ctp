<div class="contentsQuestions form">
	<ol class="breadcrumb">
<?php
	if($is_admin)
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
	</ol>
	
	<div id="lblStudySec" class="btn btn-info"></div>
	<?php $this->start('css-embedded'); ?>
	<style type='text/css'>
		.radio-group
		{
			font-size		: 18px;
			padding			: 10px;
			line-height		: 180%;
		}
		
		input[type=radio]
		{
			padding			: 10px;
		}
		
		.form-inline
		{
		}
		
		#lblStudySec
		{
			position		: fixed;
			top				: 50px;
			right			: 20px;
			display			: none;
		}
		
		.question-text,
		.correct-text
		{
			padding			: 10px;
			border-radius	: 6px;
		}
		
		img{
			max-width		: 100%;
		}
		
		.result-table
		{
			margin			: 10px;
			width			: 250px;
		}
		
		<?php if($is_admin) {?>
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
		var studySec  = 0;
		var timeLimitSec = parseInt('<?php echo $content['Content']['timelimit'] ?>') * 60; // 制限時間（単位：秒）
		var is_record = '<?php echo $is_record ?>'; // テスト結果表示フラグ
		var timerID   = null;
		var lblStudySec = null;
		
		$(document).ready(function()
		{
			if(!is_record)
			{
				lblStudySec = $("#lblStudySec");
				lblStudySec.show();
				setStudySec();
				timerID = setInterval(setStudySec, 1000);
			}
		});
		
		function setStudySec()
		{
			if(timeLimitSec > 0)
			{
				if( studySec > timeLimitSec )
				{
					clearInterval(timerID);
					alert("制限時間を過ぎましたので自動採点を行います。");
					$("form").submit();
					return;
				}
				
				var restSec = timeLimitSec - studySec;
				var rest = moment("2000/01/01").add('seconds', restSec ).format('HH:mm:ss');
				
				lblStudySec.text("残り時間 : " + rest);
				
				if(restSec < 60)
				{
					if(lblStudySec.hasClass('btn-info'))
						lblStudySec.removeClass('btn-info').addClass('btn-danger');
				}
			}
			else
			{
				var passed = moment("2000/01/01").add('seconds', studySec ).format('HH:mm:ss');
				
				lblStudySec.text("経過: " + passed);
			}
			
			$("#ContentsQuestionStudySec").val(studySec);
			studySec++;
		}
	</script>
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
		<?php foreach ($contentsQuestions as $contentsQuestion): ?>
			<?php
			$title			= $contentsQuestion['ContentsQuestion']['title'];	// 問題のタイトル
			$body			= $contentsQuestion['ContentsQuestion']['body'];	// 問題文
			$question_id	= $contentsQuestion['ContentsQuestion']['id'];		// 問題ID
			
			// 問題画像（現在不使用）
			$image = '';
			if($contentsQuestion['ContentsQuestion']['image']!='')
				$image = sprintf('<div><img src="%s"/></div>', $contentsQuestion['ContentsQuestion']['image']);
			
			// 選択肢用の出力タグの生成
			$option_tag		= ''; // 選択肢用の出力タグ
			$option_index	= 1; // 選択肢番号
			$option_list	= explode('|', $contentsQuestion['ContentsQuestion']['options']); // 選択肢リスト
			foreach($option_list as $option)
			{
				$options[$option_index] = $option;
				$is_disabled = $is_record ? 'disabled' : '';
				$is_checked = (@$question_records[$question_id]['answer']==$option_index) ? 'checked' : '';
				
				$option_tag .= sprintf('<input type="radio" value="%s" name="data[answer_%s]" %s %s> %s<br>',
					$option_index, $question_id, $is_checked, $is_disabled, h($option));
				
				$option_index++;
			}
			
			// テスト結果表示モードの場合、正解、解説情報を出力
			$explain_tag = ''; // 解説用タグ
			$correct_tag = ''; // 正解用タグ
			if($is_record)
			{
				$result_img		= (@$question_records[$question_id]['is_correct']=='1') ? 'correct.png' : 'wrong.png';
				$correct		= $option_list[$contentsQuestion['ContentsQuestion']['correct']-1];
				$correct_tag	= sprintf('<p class="correct-text bg-success">正解 : %s</p><p>%s</p>',
					$correct, $this->Html->image($result_img, array('width'=>'60','height'=>'60')));
				
				// 解説の設定
				if($contentsQuestion['ContentsQuestion']['explain']!='')
				{
					$explain_tag = sprintf('<div class="correct-text bg-danger">%s</div>',
						$contentsQuestion['ContentsQuestion']['explain']);
				}
			}
			?>
			<div class="panel panel-info">
				<div class="panel-heading">問<?php echo $question_index;?></div>
				<div class="panel-body">
					<h4><?php echo h($title) ?></h4>
					<div class="question-text bg-warning">
						<?php echo $body ?>
						<?php echo $image; ?>
					</div>
					
					<div class="radio-group">
						<?php echo $option_tag; ?>
					</div>
					<?php echo $correct_tag ?>
					<?php echo $explain_tag ?>
					<?php echo $this->Form->hidden('correct_'.$question_id, array('value' => $contentsQuestion['ContentsQuestion']['correct'])); ?>
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
				echo '<input type="button" value="採点" class="btn btn-primary btn-lg btn-score" onclick="$(\'#confirmModal\').modal()">';
				echo '&nbsp;';
			}
			
			echo '<input type="button" value="戻る" class="btn btn-default btn-lg" onclick="location.href=\''.Router::url($course_url).'\'">';
			echo '</div><!--end-->';
			echo $this->Form->end();
		?>
	<br>
</div>

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
				<button type="button" class="btn btn-primary btn-score" onclick="$('form').submit();">採点</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
