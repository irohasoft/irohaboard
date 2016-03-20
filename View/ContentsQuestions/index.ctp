<div class="contentsQuestions form">
	<ol class="breadcrumb">
<?php
	$course_url = array('controller' => 'contents', 'action' => 'index', $this->Session->read('Iroha.course_id'));
	
	$this->Html->addCrumb('コース一覧', array('controller' => 'users_courses', 'action' => 'index'));
	$this->Html->addCrumb($course_name, $course_url);
	$this->Html->addCrumb($contentsQuestions[0]['Content']['title']);
	
	echo $this->Html->getCrumbs(' / ');
?>
	</ol>
<div class="ib-page-title"><?php echo $contentsQuestions[0]['Content']['title']; ?></div>
<?php $this->start('css-embedded'); ?>
<style type='text/css'>
	.radio-group
	{
		font-size:18px;
		padding:10px;
		line-height: 180%;
	}
	
	input[type=radio]
	{
		padding:10px;
	}
	
	.form-inline
	{
	}
</style>
<?php $this->end(); ?>
<?php $this->start('script-embedded'); ?>
<script>
	var studySec = 0;
	
	$(document).ready(function()
	{
		setInterval("setStudySec();", 1000);
	});
	
	function setStudySec()
	{
		studySec++;
		$("#ContentsQuestionStudySec").val(studySec);
	}
</script>
<?php $this->end(); ?>

<?php
	$index = 1;
?>
<?php echo $this->Form->create('ContentsQuestion'); ?>
<?php foreach ($contentsQuestions as $contentsQuestion): ?>
<div class="panel panel-info">
	<div class="panel-heading">問<?php echo $index;?></div>
	<div class="panel-body">
		<h4><?php echo h($contentsQuestion['ContentsQuestion']['title']); ?></h4>
		
		<?php
			$image = $contentsQuestion['ContentsQuestion']['image'];
			$image = ($image=="") ? "" : '<div><img src="'.$image.'"/></div>';
		
		?>
		
		<p class="bg-warning">
			<?php echo nl2br(h($contentsQuestion['ContentsQuestion']['body'])); ?>
			<?php echo $image; ?>
		</p>
		
		<div class="radio-group">
			<?php
				$list = explode('|', $contentsQuestion['ContentsQuestion']['options']);
				
				$val = 1;
				
				$id = $contentsQuestion['ContentsQuestion']['id'];
				
				foreach($list as $option) {
					$options[$val] = $option;
					$is_disabled = ($this->action == 'record') ? " disabled" : "";
					$is_checked = (@$record['RecordsQuestion'][$index-1]['answer']==$val) ? " checked" : "";
					
					echo '<input type="radio" value="'.$val.'" name="data[answer_'.$id.']" '.
						$is_checked.$is_disabled.'> '.$option.'<br>';
					$val++;
				}
				
				//debug($contentsQuestion);
				
				if ($this->action == 'record')
				{
					$result_img = ($record['RecordsQuestion'][$index-1]['is_correct']=='1') ? 'correct.png' : 'wrong.png';
					$correct = $list[$contentsQuestion['ContentsQuestion']['correct']-1];
					echo '正解 : '.$correct;
				}
						
				echo $this->Form->hidden(
					'correct_'.$id,
					array('value' => $contentsQuestion['ContentsQuestion']['correct'])
				);

				$index++;
			?>
		</div>
		<?php if ($this->action == 'record') {?>
		<p>
			<?php echo $this->Html->image($result_img, array('width'=>'60','height'=>'60')); ?>
		</p>
		<p class="bg-danger">
			<?php echo h($contentsQuestion['ContentsQuestion']['explain']); ?>
		</p>
		<?php }?>
	</div>
</div>
<?php endforeach; ?>

<?php
	if ($this->action != 'record')
	{
		$comment = __('採点してよろしいですか？', true);
		echo '<div class="form-inline">';
		echo $this->Form->hidden('study_sec');
		echo $this->Form->submit(__('採点', true), array(
			'name'=>'complete',
			'class' => 'btn btn-primary btn-lg',
			'div' => false,
			'onClick'=>"return confirm('$comment')")
		);
		echo '&nbsp;';
		$url = Router::url($course_url);
		echo '<input type="button" value="戻る" class="btn btn-default btn-lg" onclick="location.href=\''.$url.'\'">';
		echo $this->Form->end();
		echo '</div>';
	}
?>
<br>
</div>

