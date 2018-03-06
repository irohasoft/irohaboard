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
		$this->Html->addCrumb('コース一覧', array('controller' => 'users_courses', 'action' => 'index'));
	}
	
	$this->Html->addCrumb($content['Course']['title'], $course_url);
	$this->Html->addCrumb(h($content['Content']['title']));
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
		var timeLimit = parseInt('<?php echo $content['Content']['timelimit'] ?>');
		var mode      = '<?php echo $mode ?>';
		var timerID   = null;
		
		$(document).ready(function()
		{
			if(mode=='test')
			{
				setStudySec();
				timerID = setInterval("setStudySec();", 1000);
			}
		});
		
		function setStudySec()
		{
			$("#lblStudySec").show();
			
			if(timeLimit > 0)
			{
				if( studySec > (timeLimit*60) )
				{
					clearInterval(timerID);
					alert("制限時間を過ぎましたので自動採点を行います。");
					$("form").submit();
				}
				
				var rest_sec = ( (timeLimit * 60) - studySec );
				var rest = moment("2000/01/01").add('seconds', rest_sec ).format('HH:mm:ss');
				
				$("#lblStudySec").text("残り時間 : " + rest);
				
				if(rest_sec < 60)
				{
					$("#lblStudySec").removeClass('btn-info');
					$("#lblStudySec").addClass('btn-danger');
				}
			}
			else
			{
				var passed = moment("2000/01/01").add('seconds', studySec ).format('HH:mm:ss');
				
				$("#lblStudySec").text("経過: " + passed);
			}
			
			$("#ContentsQuestionStudySec").val(studySec);
			studySec++;
		}
	</script>
	<?php $this->end(); ?>

	<?php
		$index = 1;
	?>
	<?php if($is_record){ ?>
		<table class="result-table">
			<caption><?php echo __('テスト結果'); ?></caption>
			<tr>
				<td><?php echo __('合否'); ?></td>
				<td><?php 
					if ($record['Record']['is_passed']==1)
					{
						echo '<div class="text-primary">'.__('合格').'</div>';
					}
					else
					{
						echo '<div class="text-danger">'.__('不合格').'</div>';
					}
					?>
				</td>
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
	
	<?php echo $this->Form->create('ContentsQuestion'); ?>
		<?php foreach ($contentsQuestions as $contentsQuestion): ?>
			<div class="panel panel-info">
				<div class="panel-heading">問<?php echo $index;?></div>
				<div class="panel-body">
					<h4><?php echo h($contentsQuestion['ContentsQuestion']['title']); ?></h4>
					
					<?php
						$image = $contentsQuestion['ContentsQuestion']['image'];
						$image = ($image=='') ? '' : '<div><img src="'.$image.'"/></div>';
					
					?>
					
					<div class="question-text bg-warning">
						<?php echo $contentsQuestion['ContentsQuestion']['body']; ?>
						<?php echo $image; ?>
					</div>
					
					<div class="radio-group">
						<?php
						$list = explode('|', $contentsQuestion['ContentsQuestion']['options']);
						
						$val = 1;
						
						$id = $contentsQuestion['ContentsQuestion']['id'];
						
						foreach($list as $option) {
							$options[$val] = $option;
							$is_disabled = ($is_record) ? " disabled" : "";
							$is_checked = (@$record['RecordsQuestion'][$index-1]['answer']==$val) ? " checked" : "";
							
							echo '<input type="radio" value="'.$val.'" name="data[answer_'.$id.']" '.
								$is_checked.$is_disabled.'> '.h($option).'<br>';
							$val++;
						}
						?>
					</div>
					<?php
					if ($is_record)
					{
						$result_img = ($record['RecordsQuestion'][$index-1]['is_correct']=='1') ? 'correct.png' : 'wrong.png';
						$correct = $list[$contentsQuestion['ContentsQuestion']['correct']-1];
						echo '<p class="correct-text bg-success">正解 : '.$correct.'</p>';
						echo '<p>'.$this->Html->image($result_img, array('width'=>'60','height'=>'60')).'</p>';
						
						if($contentsQuestion['ContentsQuestion']['explain']!='')
							echo '<div class="correct-text bg-danger">'.$contentsQuestion['ContentsQuestion']['explain'].'</div>';
						
						echo $this->Form->hidden(
							'correct_'.$id,
							array('value' => $contentsQuestion['ContentsQuestion']['correct'])
						);
					}
					$index++;
					?>
				</div>
			</div>
		<?php endforeach; ?>

		<?php
			echo '<div class="form-inline"><!--start-->';
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
