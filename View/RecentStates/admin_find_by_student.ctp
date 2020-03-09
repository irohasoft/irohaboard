<?php
  echo $this->element('admin_menu');
  echo $this->Html->css('recentstate');
  echo $this->Html->css('bootstrap.min');
?>
<div class = "admin-recentstate-findByStudent">
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
        $pic_path = $user['User']['pic_path'];
        if($pic_path === null or $pic_path === '' or $pic_path === 'student_img/'){
          $pic_path = 'student_img/noPic.png';
        }
        echo $this->Html->image($pic_path,
            array(
              //'width' => '150',
              'height'=> '150',
              'alt' => $pic_path,
              'url' => array(
                  'controller' => 'recentstates',
                  'action' => 'student_view',$user['User']['id']

                  )
            ));
      ?>
      </div>
      <div class = "number-block">
        <?php echo h($user['User']['username']);?>
      </div>
      <div class = "name-block">
        <?php echo h($user['User']['name']);?>
      </div>

    </div>
  <?php endforeach; ?>
  </div>
</div>
