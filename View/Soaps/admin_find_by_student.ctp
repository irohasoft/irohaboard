<?php 
  echo $this->element('admin_menu');
  echo $this->Html->css('soap');
  echo $this->Html->css('bootstrap.min');
?>
<div class = "admin-soap-findByStudent">
  <div class = "ib-page-title">
    <?php echo __('受講生一覧');?>
  </div>
  <br>
  <div class = "student-search-block">
    <?php
      echo $this->Form->create('Search');
      echo $this->Form->input('username',
        array(
          'label' => array(
            'text' => '学籍番号：',
            'class' => 'student-search-input-label',
          ),
          'required' => false,
          'div' => false,
          'class' => 'student-search-input',
          'style' => array(
            'width : 100px'
          )
        ));
      echo $this->Form->input('name',
        array(
          'label' => array(
            'text' => '氏名：',
            'class' => 'student-search-input-label',
          ),
          'required' => false,
          'div' => false,
          'class' => 'student-search-input',
          'style' => array(
            'width : 100px'
          )
        ));
    ?>
    <div class = "white-width">
    </div>
    <div class = "student-search-submit-btn"> 
    <input type = "submit" class = "" value = "検索">
    </div>
    <?php
      echo $this->Form->end();
    ?> 
  </div>
  <div class = "index-block">
  <?php
    foreach ($user_list as $user):  
  ?>
    <div class = "student-block">
      <div class = "pic-block">
      <?php
        echo $this->Html->image('student_img/test-man.jpeg', 
            array(
              'width' => '150',
              'height'=> '150',
              'alt' => 'test-man',
              'url' => array(
                  'controller' => 'soaps',
                  'action' => 'student_edit',$user['ib_users']['id']
                
                  )
            )); 
      ?>
      </div>
      <div class = "number-block">
        <?php echo h($user['ib_users']['username']);?> 
      </div>
      <div class = "name-block">
        <?php echo h($user['ib_users']['name']);?> 
      </div>

    </div>
  <?php endforeach; ?>
  </div>
</div>
