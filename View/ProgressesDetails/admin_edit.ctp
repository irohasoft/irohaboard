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

		$url.after('<input id="btnUpload" type="button" value="アップロード">');

		$("#btnUpload").click(function(){
			val = 'file';

			window.open('<?php echo Router::url(array('controller' => 'progressesDetails', 'action' => 'upload'))?>/'+val, '_upload', 'width=650,height=500,resizable=no');
			return false;
		});


		// 保存時、コード表示モードの場合、解除する（編集中の内容を反映するため）

		render();
	});

	function render()
	{
		// var content_kind = $('input[name="data[Content][kind]"]:checked').val();
		
		// document.getElementById("testURL").name = "data[Content][testURL]";
		// document.getElementById("textURL").name = "data[Content][url]";

		// $(".kind").hide();
		// $(".kind-"+content_kind).show();
		// $("#btnPreview").hide();

    $(".form-control-upload").css('width', '85%');
		$("#btnUpload").show();
		// switch(content_kind)
		// {
		// 	case 'text': // テキスト
		// 		$("#ContentBody").summernote('destroy');
		// 		// テキストが存在しない場合、空文字にする。
		// 		if($('<span>').html($("#ContentBody").val()).text()=="")
		// 			$("#ContentBody").val("");
		// 		$("#btnPreview").show();
		// 		break;
		// 	case 'html': // リッチテキスト
		// 		// リッチテキストエディタを起動
		// 		CommonUtil.setRichTextEditor('#ContentBody', <?php echo (Configure::read('use_upload_image') ? 'true' : 'false')?>, '<?php echo $this->webroot ?>');
		// 		$("#btnPreview").show();
		// 		break;
		// 	case 'movie': // 動画
		// 		$(".form-control-upload").css('width', '85%');
		// 		$("#btnUpload").show();
		// 		$("#btnPreview").show();
		// 		break;
		// 	case 'url':
		// 		$(".form-control-upload").css('width', '100%');
		// 		$("#btnUpload").hide();
		// 		$("#btnPreview").show();
		// 		break;
		// 	case 'file':
				
		// 		break;
		// 	case 'test':
				
		// 		document.getElementById("testURL").name = "data[Content][url]";
		// 		document.getElementById("textURL").name = "data[Content][textURL]";
				
		// 		break;
    //   case 'textAndTest':
    //     CommonUtil.setRichTextEditor('#ContentBody', <?php echo (Configure::read('use_upload_image') ? 'true' : 'false')?>, '<?php echo $this->webroot ?>');
		// 		$("#btnPreview").show();
    //     break;
		// }
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
	}

  
</script>
<?php $this->end(); ?>

<div class="admin-contents-edit full-view mb-5">
	<?php
		$this->Html->addCrumb('成果発表一覧', array('controller' => 'progresses', 'action' => 'index'));
		$this->Html->addCrumb($progress_info['Progress']['title'],  array('controller' => 'progresses', 'action' => 'index', $progress_info['Progress']['id']));

		echo $this->Html->getCrumbs(' / ');
	?>
	<div class="card bg-light">
		<div class="card-header">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規発表'); ?>
		</div>
		<div class="card-body">
			<?php echo $this->Form->create('ProgressesDetail',array(
        'inputDefaults' => array(
		      'div' => 'form-group',
		      'label' => array(
			      'class' => 'col col-sm-3 control-label'
		      ),
		      'wrapInput' => 'col col-sm-12',
		      'class' => 'form-control'
	      ),
	        'class' => 'form-horizontal',
          'type' => 'file',
          'enctype' => 'multipart/form-data'
        ));?>
			<?php
				echo $this->Form->input('id');
        echo $this->Form->hidden('progress_id',array(
          'value' => $progress_id
        ));
        echo $this->Form->input('title',	array('label' => '発表タイトル'));
        echo $this->Form->input('user_name',	array(
          'label' => '発表者',
          'id' => 'nameAutoComplete',
          'value' => $user_name
        ));
        echo $this->Form->input('body',	array(
          'label' => '発表内容と資料リンク',
          'type' => 'textarea'
        ));
        echo '<div class="bg-light text-black">ソースコードをアップしたい場合：(できれば<code>.zip</code>でお願いします)</div>';
        echo $this->Form->input('url',		array(
					'label' => 'URL', 
					'class' => 'form-control form-control-upload',
					'id' => 'textURL'
				));
				echo $this->Form->input('file_name', array(
          'label' => 'ファイル名', 
          'class' => 'form-control-filename', 
          'readonly' => 'readonly'
        ));


			?>
			<div class="form-group">
				<div class="col col-sm-12 col-sm-offset-3">
					<!-- <button id="btnPreview" class="btn btn-secondary" value="プレビュー" onclick="preview(); return false;" type="submit">プレビュー</button> -->
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<script>
var availabelNames = <?php echo $user_name_list_json; ?>;
$("#nameAutoComplete").autocomplete({
  source: availabelNames,
  // autoFocus: true,
  // delay: 500,
  // minLength: 1
});
console.log(availabelNames);
</script>
