<?= $this->element('admin_menu');?>
<?php $this->start('css-embedded'); ?>
<?= $this->Html->css('summernote.css');?>
<?php $this->end(); ?>
<?php $this->start('script-embedded'); ?>
<?= $this->Html->script('summernote.min.js');?>
<?= $this->Html->script('lang/summernote-ja-JP.js');?>
<script>
	$(document).ready(function()
	{
		$url = $('.form-control-upload');

		$url.after('<input id="btnUpload" type="button" value="ファイルを指定">');

		$("#btnUpload").click(function() {
			var val = $('input[name="data[Content][kind]"]:checked').val();
			
			if(!val)
				return false;
			
			if(
				(val == 'text')||
				(val == 'test')
			)
				return false;
			
			if(val == 'url')
				val = 'file';
			
			$('#uploadDialog').modal('show');

			//モーダル画面にiframeを追加する
			$("#uploadFrame").attr("src", "<?= Router::url(['controller' => 'contents', 'action' => 'upload'])?>/" + val);
			return false;
		});

		$('input[name="data[Content][kind]"]:radio').change( function() {
			render();
		});

		// 保存時、コード表示モードの場合、解除する（編集中の内容を反映するため）
		$("form").submit( function() {
			var val = $('input[name="data[Content][kind]"]:checked').val();
			
			if(val == 'html')
			{
				if ($('#ContentBody').summernote('codeview.isActivated'))
				{
					$('#ContentBody').summernote('codeview.deactivate')
				}
			}
		});

		render();
	});
	
	function render()
	{
		var content_kind = $('input[name="data[Content][kind]"]:checked').val();
		
		$(".kind").hide();
		$(".kind-" + content_kind).show();
		$("#btnPreview").hide();
		
		switch(content_kind)
		{
			case 'text': // テキスト
				$("#ContentBody").summernote('destroy');
				// テキストが存在しない場合、空文字にする。
				if($('<span>').html($("#ContentBody").val()).text() == '')
					$("#ContentBody").val("");
				$("#btnPreview").show();
				break;
			case 'html': // リッチテキスト
				// リッチテキストエディタを起動
				CommonUtil.setRichTextEditor('#ContentBody', <?= Configure::read('upload_image_maxsize') ?>, '<?= $this->webroot ?>');
				$("#btnPreview").show();
				break;
			case 'movie': // 動画
				$(".form-control-upload").css('width', '80%');
				$("#btnUpload").show();
				$("#btnPreview").show();
				break;
			case 'url':
				$(".form-control-upload").css('width', '100%');
				$("#btnUpload").hide();
				$("#btnPreview").show();
				break;
			case 'file':
				$(".form-control-upload").css('width', '80%');
				$("#btnUpload").show();
				break;
			case 'test':
				break;
		}
	}
	
	function preview()
	{
		var content_kind = $('input[name="data[Content][kind]"]:checked').val();
		
		$.ajax({
			url: "<?= Router::url(['action' => 'preview']) ?>",
			type: "POST",
			data: {
				content_title : $("#ContentTitle").val(),
				content_kind  : $('input[name="data[Content][kind]"]:checked').val(),
				content_url   : $("#ContentUrl").val(),
				content_body  : $("#ContentBody").val(),
			},
			dataType: "text",
			success : function(response){
				//通信成功時の処理
				//alert(response);
				var url = '<?= Router::url(['controller' => 'contents', 'action' => 'preview'])?>'.replace('admin/', '');
				
				window.open(url, '_preview', 'width=1000,height=700,resizable=no');
			},
			error: function(){
				//通信失敗時の処理
				//alert('通信失敗');
			}
		});
	}
	
	function setURL(url, file_name)
	{
		$('.form-control-upload').val(url);
		
		if(file_name)
			$('.form-control-filename').val(file_name);

		$('#uploadDialog').modal('hide');
	}
	
	function closeDialog(url, file_name)
	{
		$('#uploadDialog').modal('hide');
	}
</script>
<?php $this->end(); ?>

<div class="admin-contents-edit">
	<?php
		$this->Html->addCrumb(__('コース一覧'), ['controller' => 'courses', 'action' => 'index']);
		$this->Html->addCrumb($course['Course']['title'],  ['controller' => 'contents', 'action' => 'index', $course['Course']['id']]);

		echo $this->Html->getCrumbs(' / ');
	?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= $this->isEditPage() ? __('編集') :  __('新規コンテンツ'); ?>
		</div>
		<div class="panel-body">
		<?php
			echo $this->Form->create('Content', Configure::read('form_defaults'));
			echo $this->Form->input('id');
			echo $this->Form->input('title', ['label' => __('コンテンツ名')]);
			echo $this->Form->inputRadio('kind', ['label' => __('コンテンツ種別'), 'separator'=>"<br>", 'options' => Configure::read('content_kind_comment')]);

			// URL
			echo '<div class="kind kind-movie kind-url kind-file">';
			echo $this->Form->input('url', ['label' => __('URL'), 'class' => 'form-control form-control-upload']);
			echo '</div>';
			
			// 配布資料
			echo '<div class="kind kind-file">';
			echo $this->Form->input('file_name', ['label' => __('ファイル名'), 'class' => 'form-control-filename', 'readonly' => 'readonly']);
			echo '</div>';

			// リッチテキスト
			echo '<div class="kind kind-text kind-html">';
			echo $this->Form->input('body',		['label' => __('内容')]);
			echo '</div>';

			// テスト用設定 start
			echo '<span class="kind kind-test">';
			echo $this->Form->inputExp('timelimit', ['label' => __('制限時間 (1-100分)')], __('指定した場合、制限時間を過ぎると自動的に採点されます。'));
			echo $this->Form->inputExp('pass_rate', ['label' => __('合格とする得点率 (1-100%)')], __('指定した場合、合否の判定が行われ、指定しない場合は無条件に合格となります。'));
			
			// ランダム出題用
			echo $this->Form->inputExp('question_count', ['label' => __('出題数 (1-100問)')], __('指定した場合、登録した問題の中からランダムに出題され、指定しない場合は全問出題されます。'));
			
			// 問題が不正解時の表示
			echo $this->Form->inputRadio('wrong_mode', ['label' => __('不正解時の表示'), 'options' => Configure::read('wrong_mode'), 'default' => 2],
				__('テスト結果画面にて不正解の問題の表示方法を指定します。正解時は解説のみが表示されます。'));
			
			echo '</span>';
			// テスト用設定 end

			// ステータス
			echo $this->Form->inputRadio('status', ['label' => __('ステータス'), 'options' => Configure::read('content_status'), 'default' => 1],
				__('[非公開]と設定した場合、管理者権限でログインした場合のみ表示されます。'));

			// コンテンツ移動用
			if($this->isEditPage())
			{
				echo $this->Form->inputExp('course_id', ['label' => __('所属コース'), 'value' => $course['Course']['id']],
					__('変更することで他のコースにコンテンツを移動できます。'));
			}

			echo '<span class="kind kind-text kind-html kind-movie kind-url kind-file kind-test">';
			echo $this->Form->input('comment', ['label' => __('備考')]);
			echo '</span>';
			echo Configure::read('form_submit_before')
				.'<button id="btnPreview" class="btn btn-default" onclick="preview(); return false;">プレビュー</button> '
				.$this->Form->submit(__('保存'), Configure::read('form_submit_defaults'))
				.Configure::read('form_submit_after');
			echo $this->Form->end();
		?>
		</div>
	</div>
</div>

<!--ファイルアップロードダイアログ-->
<div class="modal fade" id="uploadDialog">
	<div class="modal-dialog">
		<div class="modal-content" style="width:660px;">
			<div class="modal-body">
				<iframe id="uploadFrame" width="100%" style="height: 440px;" scrolling="no" frameborder="no"></iframe>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
