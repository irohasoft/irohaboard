<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('summernote.css');?>
<?php $this->start('script-embedded'); ?>
<?php echo $this->Html->script('summernote.min.js');?>
<?php echo $this->Html->script('lang/summernote-ja-JP.js');?>
<script>
	$(document).ready(function()
	{
		init();
	});

	function add_option()
	{
		txt	= document.all("option");
		opt	= document.all("data[ContentsQuestion][option_list][]").options;
		
		if(txt.value=="")
		{
			alert("選択肢を入力してください");
			return false;
		}
		
		if(txt.value.length > 100)
		{
			alert("選択肢は100文字以内で入力してください");
			return false;
		}
		
		if(opt.length==10)
		{
			alert("選択肢の数が最大値を超えています");
			return false;
		}
		
		opt[opt.length] = new Option( txt.value, txt.value )
		txt.value = "";
		update_options();
		update_correct();

		return false;
	}

	function del_option()
	{
		var opt = document.all("data[ContentsQuestion][option_list][]").options;
		
		if( opt.selectedIndex > -1 )
		{
			opt[opt.selectedIndex] = null;
			update_options();
			update_correct();
		}
	}

	function update_options()
	{
		var opt = document.all("data[ContentsQuestion][option_list][]").options;
		var txt = document.all("ContentsQuestionOptions");
		
		txt.value = "";
		
		for(var i=0; i<opt.length; i++)
		{
			if(txt.value=="")
			{
				txt.value = opt[i].value;
			}
			else
			{
				txt.value += "|" + opt[i].value;
			}
		}
		
	}

	function update_correct()
	{
		var opt = document.all("data[ContentsQuestion][option_list][]").options;
		
		if( opt.selectedIndex < 0 )
		{
			document.all("ContentsQuestionCorrect").value = "";
		}
		else
		{
			var corrects = new Array();
			
			for(var i=0; i<opt.length; i++)
			{
				if(opt[i].selected)
					corrects.push(i+1);
			}
			
			document.all("ContentsQuestionCorrect").value = corrects.join(',');
		}
	}

	function init()
	{
		// リッチテキストエディタを起動
		CommonUtil.setRichTextEditor('#ContentsQuestionBody', <?php echo (Configure::read('use_upload_image') ? 'true' : 'false')?>, '<?php echo $this->webroot ?>');
		CommonUtil.setRichTextEditor('#ContentsQuestionExplain', <?php echo (Configure::read('use_upload_image') ? 'true' : 'false')?>, '<?php echo $this->webroot ?>');
		
		if($("#ContentsQuestionOptions").val()=="")
			return;
		
		var options = $("#ContentsQuestionOptions").val().split('|');
		var corrects = $("#ContentsQuestionCorrect").val().split(',');
		
		for(var i=0; i<options.length; i++)
		{
			var no = (i+1).toString();
			var isSelected = (corrects.indexOf(no) >= 0);
			
			$option = $('<option>')
				.val(options[i])
				.text(options[i])
				.prop('selected', isSelected);
			
			$("#ContentsQuestionOptionList").append($option);
		}
	}
</script>
<?php $this->end(); ?>
<div class="admin-contents-questions-edit">
	<div class="ib-breadcrumb">
	<?php 
		$this->Html->addCrumb('コース一覧',  array('controller' => 'courses', 'action' => 'index'));
		$this->Html->addCrumb($content['Course']['title'],  array('controller' => 'contents', 'action' => 'index', $content['Course']['id']));
		$this->Html->addCrumb($content['Content']['title'], array('controller' => 'contents_questions', 'action' => 'index', $content['Content']['id']));
		
		echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規問題'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('ContentsQuestion', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => __('タイトル')));
				echo $this->Form->input('body',		array('label' => __('問題文')));
			?>
			<div class="form-group required">
				<label for="ContentsQuestionOptions" class="col col-sm-3 control-label">選択肢／正解</label>
				<div class="col col-sm-9 required">
				「＋」で選択肢の追加、「−」で選択された選択肢を削除します。（※最大10個まで）<br>
				また選択された選択肢が正解となります。Ctrlキーを押下したまま選択することで、複数の正解の設定も可能です。<br>
				<input type="text" size="20" name="option" style="width: 80%;display:inline-block;">
				<button class="btn" onclick="add_option();return false;">＋</button>
				<button class="btn" onclick="del_option();return false;">−</button><br>
			<?php
				echo $this->Form->input('option_list',	array('label' => __('選択肢／正解'), 
					'type' => 'select',
					'label' => false,
					'multiple' => true,
					'size' => 5,
					'onchange' => 'update_correct()'
				));
				echo $this->Form->hidden('options',		array('label' => __('選択肢')));
			?>
				</div>
			</div>
			<?php
				echo "<div class='' style='display:none;'>";
				echo $this->Form->input('correct',	array('label' => __('正解')));
				echo "</div>";
				echo $this->Form->input('score',	array('label' => __('得点')));
				echo $this->Form->input('explain',	array('label' => __('解説')));
				echo $this->Form->input('comment',	array('label' => __('備考')));
			?>
			<div class="form-group">
				<div class="col col-sm-9 col-sm-offset-3">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>