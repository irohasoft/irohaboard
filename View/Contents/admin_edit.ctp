<?php echo $this->element('admin_menu');?>
<?php $this->start('css-embedded'); ?>
<?php echo $this->Html->css('summernote.css');?>
<?php $this->end(); ?>
<?php $this->start('script-embedded'); ?>
<?php echo $this->Html->script('summernote.min.js');?>
<?php echo $this->Html->script('lang/summernote-ja-JP.js');?>
<script>
	$(document).ready(function()
	{
		$url = $('.form-control-upload');

		$url.after('<input id="btnUpload" type="button" value="ファイルを指定">');

		$("#btnUpload").click(function(){
			var val = $('input[name="data[Content][kind]"]:checked').val();
			
			if(!val)
				return false;
			
			if(
				(val=='text')||
				(val=='test')
			)
				return false;
			
			if(val=='url')
				val = 'file';
			
			//window.open('<?php echo Router::url(array('controller' => 'contents', 'action' => 'upload'))?>/'+val, '_upload', 'width=650,height=500,resizable=no');
			$('#uploadDialog').modal('show');

			//モーダル画面にiframeを追加する
			$("#uploadFrame").attr("src", "<?php echo Router::url(array('controller' => 'contents', 'action' => 'upload'))?>/" + val);
			return false;
		});

		$('input[name="data[Content][kind]"]:radio').change( function() {
			render();
		});

		// 保存時、コード表示モードの場合、解除する（編集中の内容を反映するため）
		$('#ContentAdminEditForm').submit( function() {
			var val = $('input[name="data[Content][kind]"]:checked').val();
			
			if(val=='html')
			{
				if ($('#ContentBody').summernote('codeview.isActivated')) {
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
		$(".kind-"+content_kind).show();
		$("#btnPreview").hide();
		
		switch(content_kind)
		{
			case 'text': // テキスト
				$("#ContentBody").summernote('destroy');
				// テキストが存在しない場合、空文字にする。
				if($('<span>').html($("#ContentBody").val()).text()=="")
					$("#ContentBody").val("");
				$("#btnPreview").show();
				break;
			case 'html': // リッチテキスト
				// リッチテキストエディタを起動
				CommonUtil.setRichTextEditor('#ContentBody', <?php echo (Configure::read('use_upload_image') ? 'true' : 'false')?>, '<?php echo $this->webroot ?>');
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
			url: "<?php echo Router::url(array('action' => 'preview')) ?>",
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
				var url = '<?php echo Router::url(array('controller' => 'contents', 'action' => 'preview'))?>'.replace('admin/', '');
				
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
		$this->Html->addCrumb('コース一覧', array('controller' => 'courses', 'action' => 'index'));
		$this->Html->addCrumb($course['Course']['title'],  array('controller' => 'contents', 'action' => 'index', $course['Course']['id']));

		echo $this->Html->getCrumbs(' / ');
	?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規コンテンツ'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Content', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => 'コンテンツタイトル'));
				echo $this->Form->input('kind',	array(
					'type' => 'radio',
					'before' => '<label class="col col-sm-3 control-label">コンテンツ種別</label>',
					'separator'=>"<br>",
					'disabled'=>false,
					'legend' => false,
					'class' => false,
					'options' => Configure::read('content_kind_comment')
					)
				);

				echo "<div class='kind kind-movie kind-url kind-file'>";
				echo $this->Form->input('url',		array('label' => 'URL', 'class' => 'form-control form-control-upload'));
				echo "</div>";
				
				// 配布資料
				echo "<div class='kind kind-file'>";
				echo $this->Form->input('file_name', array('label' => 'ファイル名', 'class' => 'form-control-filename', 'readonly' => 'readonly'));
				echo "</div>";

				// テキスト・リッチテキスト
				echo "<div class='kind kind-text kind-html'>";
				echo $this->Form->input('body',		array('label' => '内容'));
				echo "</div>";

				// テスト
				echo "<span class='kind kind-test'>";
				echo $this->Form->input('timelimit', array(
					'label' => '制限時間 (1-100分)',
					'after' => '<div class="col col-sm-3"></div><span class="status-exp">　指定した場合、制限時間を過ぎると自動的に採点されます。</span>',
				));
				
				echo $this->Form->input('pass_rate', array(
					'label' => '合格とする得点率 (1-100%)',
				));
				
				// ランダム出題用
				echo $this->Form->input('question_count', array(
					'label' => '出題数 (1-100問)',
					'after' => '<div class="col col-sm-3"></div><span class="status-exp">　指定した場合、登録した問題の中からランダムに出題されます。</span>',
				));
				echo "</span>";

				// ステータス
				echo $this->Form->input('status',	array(
					'type' => 'radio',
					'before' => '<label class="col col-sm-3 control-label">ステータス</label>',
					'after' => '<div class="col col-sm-3"></div><span class="status-exp">　非公開と設定した場合、管理者権限でログインした場合のみ表示されます。</span>',
					'separator' => '　', 
					'legend' => false,
					'class' => false,
					'default' => 1,
					'options' => Configure::read('content_status')
					)
				);

				// コンテンツ移動用
				if(($this->action == 'admin_edit'))
				{
					echo $this->Form->input('course_id', array(
						'label' => '所属コース',
						'value'=>$course['Course']['id'],
						'after' => '<div class="col col-sm-3"></div><span class="status-exp">　変更することで他のコースにコンテンツを移動できます。</span>',
					));
				}

				echo "<span class='kind kind-text kind-html kind-movie kind-url kind-file kind-test'>";
				echo $this->Form->input('comment', array('label' => '備考'));
				echo "</span>";
			?>
			<div class="form-group">
				<div class="col col-sm-9 col-sm-offset-3">
					<button id="btnPreview" class="btn btn-default" value="プレビュー" onclick="preview(); return false;" type="submit">プレビュー</button>
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<!--ファイルアップロードダイアログ-->
<div class="modal fade" id="uploadDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-id='1'>
	<div class="modal-dialog">
		<div class="modal-content" style="width:660px;">
			<div class="modal-body" id='modal-body_1'>
				<iframe id="uploadFrame" width="100%" style="height: 440px;" scrolling="no" frameborder="no"></iframe>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
