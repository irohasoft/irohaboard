<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->script('select2.min.js');?>
<?php echo $this->Html->css('select2.min.css')?>
<?php echo $this->Html->css('users_admin_edit')?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
	$(function (e) {
		$('#GroupGroup').select2({placeholder:   "所属するグループを選択して下さい。(複数選択可)", closeOnSelect: <?php echo (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		$('#CourseCourse').select2({placeholder: "受講するコースを選択して下さい。(複数選択可)", closeOnSelect: <?php echo (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		// パスワードの自動復元を防止
		setTimeout('$("#UserNewPassword").val("");', 500);
	});
<?php $this->Html->scriptEnd(); ?>
<script>
$(function(){
  $('#myfile').change(function(e){
    //ファイルオブジェクトを取得する
    var file = e.target.files[0];
    var reader = new FileReader();

    //画像でない場合は処理終了
    if(file.type.indexOf("image") < 0){
      alert("画像ファイルを指定してください。");
      return false;
    }

    //アップロードした画像を設定する
    reader.onload = (function(file){
      return function(e){
        $("#img1").attr("src", e.target.result);
        $("#img1").attr("title", file.name);
      };
    })(file);
    reader.readAsDataURL(file);

  });
});
</script>
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
<div class = "admin-users-edit">
  <div class = "index-block">
    <?php echo ($this->request->data) ? __('ユーザー編集') : __('ユーザー新規');?>
  </div>
  <div class = "pic-block">
    <?php
      echo $this->Form->create('User',array(
        'type' => 'file',
        'enctype' => 'multipart/form-data'
      ));
      echo $this->Form->input('id');
      echo $this->Form->input('front_image',array(
        'id' => 'myfile',
        'div' => false,
        'class' => false,
        'label' => '写真',
        'type' => 'file', 'multiple'
      ));
			$img_src = $this->Image->makeInlineImage(Configure::read('student_img').$pic_path);
		?>
		<img src="<?php echo $img_src; ?>" id="img1" width="300" alt="<?php echo $pic_path ?>"/>
  </div>

  <div class = "info-input-block">
  	<?php $password_label = ($this->request->data) ? __('新しいパスワード') : __('パスワード'); ?>
    <div class = "info-input">
			<?php
      echo $this->Form->input('name',	array(
      	'label' => array(
          'text' => '氏名：',
          'class' => 'info-input-label'
        ),
        'required' => false,
        'div' => false,
        'class' => false
				));

			echo $this->Form->input('name_furigana',	array(
				'label' => array(
					'text' => 'ふりがな：',
					'class' => 'info-input-label'
				),
				'required' => false,
				'div' => false,
				'class' => false
				));
			?>
    </div>

    <div class = "info-input">
			<?php
    		echo $this->Form->input('username',	array(
        	'label' => array(
          	'text' => '学籍番号（ログインID）：',
          	'class' => 'info-input-label'
        	),
        	'required' => false,
        	'div' => false,
        	'class' => false
        ));
			?>
    </div>

    <div class = "info-input">
			<?php
    		echo $this->Form->input('birthyear',	array(
        	'label' => array(
          	'text' => '生まれた年度：',
          	'class' => 'info-input-label'
        	),
        	'required' => false,
        	'div' => false,
        	'class' => false
        ));
			?>
    </div>

		<?php
    	//Password
			$password_label = ($this->request->data) ? __('新しいパスワード') : __('パスワード');
		?>

    <div class = "info-input">
			<?php
    		echo $this->Form->input('User.new_password',	array(
        	'label' => array(
          	'text' => $password_label."：",
          	'class' => 'info-input-lable'
        	),
        	'type' => 'password',
        	'autocomplete' => 'new-password'
      	));
			?>
    </div>

		<?php
			// root アカウント、もしくは admin 権限以外の場合、権限変更を許可しない
    	$disabled = (($username == 'root')||($loginedUser['role']!='admin'));
		?>

    <div class = "info-input">
			<?php
    		echo $this->Form->input('role',	array(
					'type' => 'radio',
					'before' => '<label class="col col-sm-3 control-label">権限</label>',
					'separator'=>"　",
					'disabled'=>$disabled,
					'legend' => false,
					'class' => false,
					'options' => Configure::read('user_role')
				));
			?>
    </div>

    <!-- email -->
    <div class = "info-input">
			<?php
    		echo $this->Form->input('email',	array(
        	'label' => array(
          	'text' => 'メールアドレス：',
          	'class' => 'info-input-label'
        	),
        	'required' => false,
        	'div' => false,
        	'class' => false
        ));
			?>
    </div>

    <div class = "info-input">
			<?php
    		echo $this->Form->input('os_type',	array(
        	'label' => array(
          	'text' => 'OS種類：',
          	'class' => 'info-input-label'
        	),
        	'required' => false,
        	'options' => $os_list,
        	'selected' => $os_id,
        	'empty' => ''
        ));
			?>
    </div>

    <div class = "info-input">
			<?php
    		echo $this->Form->input('period',	array(
        	'label' => array(
          	'text' => '受講時間帯：',
          	'class' => 'info-input-label'
        	),
        	'required' => false,
        	'div' => false,
        	'class' => false,
        	'options' => array('1限','2限'),
        	'selected' => $time_id,
        	'empty' => ''
        ));
			?>
    </div>

    <div class = "info-input">
			<?php
    		echo $this->Form->input('group_id',	array(
        	'label' => array(
          	'text' => '所属グループ：',
          	'class' => 'info-input-label'
        	),
        	'required' => false,
        	'div' => false,
        	'class' => false,
        	'options' => $group_list,
        	'selected' => $group_id,
        	'empty' => ''
        ));
			?>
    </div>

    <div class = "info-input">
			<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
			<?php echo $this->Form->end(); ?>
			<?php
			if($this->request->data)
			{
				echo $this->Form->postLink(__('学習履歴を削除'), array(
					'action' => 'clear',
					$this->request->data['User']['id']
				), array(
					'class' => 'btn btn-warning float-left'
				), __('学習履歴を削除してもよろしいですか？', $this->request->data['User']['name']));
				echo $this->Form->postLink(__('削除'), array(
					'action' => 'delete',
					$this->request->data['User']['id']
				), array(
					'class' => 'btn btn-danger btn-delete float-left'
				), __('[%s] を削除してもよろしいですか?', $this->request->data['User']['name']));
			}
			?>
		</div>
  </div>
</div>
