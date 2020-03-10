<?php echo $this->element('admin_menu');?>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
  <?php echo $this->Html->css('admin_manage');?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
  <script>
    $(document).ready(function(){
      $('.attendance-slider').bxSlider({
        pager : false,
        startSlide : 1000,
        slideWidth : 600
      });
    });

    $(document).ready(function(){
      $('.enquete-slider').bxSlider({
        pager : false,
        startSlide : 1000,
        slideWidth : 1000
      });
    });

    $(document).ready(function(){
      $('.soap-slider').bxSlider({
        pager : false,
        startSlide : 1000,
        slideWidth : 1000
      });
    });
  </script>
</head>

<div class = "admin-manage-header">
  <div class = "ib-page-title"><?php echo __('管理者用ページ')?></div></br></br>
</div>

<div class="ib-horizontal">
  <?php
    echo $this->Form->create('User');
	  echo '<div class="ib-search-buttons" style = "float : right;">';
	  echo $this->Form->submit(__('ダウンロード'),	array('class' => 'btn btn-info', 'div' => false));
	  //echo $this->Form->hidden('cmd');
	  //echo '<button type="button" class="btn btn-secondary" onclick="downloadCSV()">'.__('CSV出力').'</button>';
	  echo '</div>';
	  echo '<div class="ib-row" >';
	  echo '<div class="ib-search-date-container form-inline">';
	  echo $this->Form->input('target_date',	array(
		  'label' => '授業データダウンロード・日付：',
		  'options'=>$date_list,
		  'selected'=>'',
		  'empty' => '授業日を選択してください',
		  'required'=>false,
		  'style' => '',
		  'value' => $target_date,
      'class'=>'form-control'));
    echo'</div>';
	  echo '</div>';
	  echo $this->Form->end();
?>
</div>

<?php foreach($user_list as $user):?>
<div class = "user-block">

  <div class = "user-info-block">
    <?php //受講生情報ブロック ?>
    <div class = "user-info-block-element username">
      <?php echo h($user['User']['username']);?>
    </div>
    <div class = "user-info-block-element name">
      <?php echo h($user['User']['name']);?>
    </div>
    <div class = "user-info-block-element name-furigana">
      <?php echo h($user['User']['name_furigana']);?>
    </div>
    <div class = "user-info-block-element period">
      <?php echo h(Configure::read('period.'.$user['User']['period']));?>
    </div>
    <div class = "user-info-block-element birthyear">
      <?php
        $birthyear = $user['User']['birthyear'];
        $this_year = date("Y");
        $age = $this_year - $birthyear;

        if($age <= 6) {
          $grade = "未就学";
        } elseif($age <= 12) {
          $grade = "小学" . ($age - 6) . "年";
        } elseif($age <= 15) {
          $grade = "中学" . ($age - 12) . "年";
        } elseif($age <= 18) {
          $grade = "高校" . ($age - 15) . "年";
        } else {
          $grade = "高卒以上";
        }
        echo h($grade);
      ?>
    </div>
    <div class = "user-info-block-element os_type">
      <?php echo h(Configure::read('os_type.'.$user['User']['os_type']));?>
    </div>
  </div>

  <div class = "student-photo">
    <?php
      $img_src = $this->Html->url(array(
        "controller" => "users",
        "action" => "show_picture",
        $user['User']['id']
      ), false);
    ?>
    <img src="<?php echo $img_src; ?>" height="100" alt="<?php echo $user['User']['name'] ?>"/>
  </div>

  <div class = "attendance-block">
    <div class = "attendance-slider">
      <?php
        $attendance_list = $user['Attendance'];
        $array_size = sizeof($attendance_list);
        //$this->log($array_size);
        $cnt = 0; $flag = 0;
        while($cnt < $array_size):
      ?>
      <div class = "attendance-info">
        <div class = "attendance-date-block">

          <div class = "attendance-date">

            <?php
            for($start = $cnt; $start < $cnt + 4; $start++):
              if($start >= $array_size){
                $flag = 1;
                break;
              }
            ?>
            <div class = "date">
              <?php
                $created = new DateTime($attendance_list[$start]['created']);
                $created_day = $created->format('Y-m-d');
                echo h($created_day);
              ?>
            </div>
            <?php endfor; ?>
          </div>
          <div class = "attendance-status">
          <?php
            for($start = $cnt; $start < $cnt + 4; $start++):
              if($start >= $array_size){
                $flag = 1;
                break;
              }
            ?>
            <div class = "status">
              <?php
                if($attendance_list[$start]['status'] != 1){
                  echo h('×');
                }else{
                  if($attendance_list[$start]['late_time'] != 0){
                    $late_time = $attendance_list[$start]['late_time'];
                    echo h('△'."($late_time)");
                  }else{
                    echo h('○');
                  }
                }
              ?>
            </div>
            <?php endfor; ?>
          </div>

          <?php
              if($flag != 1){
                $cnt += 4;
              }else{
                break;
              }
          ?>

        </div>
            </div>
      <?php endwhile; ?>
    </div>
  </div>
  <?php //---------User-info-block?>

    </div>
  </div>

  <div class = "enquete-block">
    <div class = "enquete-slider">
      <?php
        $enquete_list = $user['Enquete'];
        foreach($enquete_list as $enquete):
      ?>
      <div class = "enquete-info">
        <div class = "enquete-hedaer-block">
          <div class = "enquete-header">
            <?php echo h('日付');?>
          </div>
          <div class = "enquete-header">
            <?php echo h('今日の感想');?>
          </div>
          <div class = "enquete-header">
            <?php echo h('前回ゴール達成');?>
          </div>
          <div class = "enquete-header">
            <?php echo h('Fの理由');?>
          </div>
          <div class = "enquete-header">
            <?php echo h('今日のゴール');?>
          </div>
          <div class = "enquete-header">
            <?php echo h('ゴール達成');?>
          </div>
          <div class = "enquete-header">
            <?php echo h('Fの理由');?>
          </div>
          <div class = "enquete-header">
            <?php echo h('次回までのゴール');?>
          </div>
        </div>
        <div class = "enquete-status-block">
          <div class = "enquete-status">
            <?php
              $created = new DateTime($enquete['created']);
              $created_day = $created->format('Y-m-d');
              echo h($created_day);
            ?>
          </div>
          <div class = "enquete-status">
            <?php
              echo h($enquete['today_impressions']);
            ?>
          </div>
          <div class = "enquete-status">
            <?php
              echo h(Configure::read('true_or_false.'.$enquete['before_goal_cleared']));
            ?>
          </div>
          <div class = "enquete-status">
            <?php
              echo h($enquete['before_false_reason']);
            ?>
          </div>
          <div class = "enquete-status">
            <?php
              echo h($enquete['today_goal']);
            ?>
          </div>
          <div class = "enquete-status">
            <?php
              echo h(Configure::read('true_or_false.'.$enquete['today_goal_cleared']));
            ?>
          </div>
          <div class = "enquete-status">
            <?php
              echo h($enquete['today_false_reason']);
            ?>
          </div>
          <div class = "enquete-status">
            <?php
              echo h($enquete['next_goal']);
            ?>
          </div>

        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>

  <div class = "soap-block">
    <div class = "soap-slider">
      <?php
        $soap_list = $user['Soap'];
        foreach($soap_list as $soap):
      ?>
      <div class = "soap-info">
        <div class = "soap-hedaer-block">
          <div class = "soap-header">
            <?php echo h('日付');?>
          </div>
          <div class = "soap-header">
            <?php echo h('担当講師');?>
          </div>
          <div class = "soap-header">
            <?php echo h('Subject');?>
          </div>
          <div class = "soap-header">
            <?php echo h('Object');?>
          </div>
          <div class = "soap-header">
            <?php echo h('Assessment');?>
          </div>
          <div class = "soap-header">
            <?php echo h('Plan');?>
          </div>
          <div class = "soap-header">
            <?php echo h('Comment');?>
          </div>

        </div>
        <div class = "soap-status-block">
          <div class = "soap-status">
            <?php
              $created = new DateTime($soap['created']);
              $created_day = $created->format('Y-m-d');
              echo h($created_day);
            ?>
          </div>
          <div class = "soap-status">
            <?php
              echo h($group_list[$soap['group_id']]);
            ?>
          </div>
          <div class = "soap-status">
            <?php
              echo h($soap['S']);
            ?>
          </div>
          <div class = "soap-status">
            <?php
              echo h($soap['O']);
            ?>
          </div>
          <div class = "soap-status">
            <?php
              echo h($soap['A']);
            ?>
          </div>
          <div class = "soap-status">
            <?php
              echo h($soap['P']);
            ?>
          </div>
          <div class = "soap-status">
            <?php
              echo h($soap['comment']);
            ?>
          </div>
        </div>

      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<?php endforeach;?>
