<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('recentstate');?>
<div class = "admin-group_view-index">

  <?php foreach($members as $member):?>
  <div class = "member-view">
    <?php $user_id = $member['ib_users']['id']; ?>
    <div class = "member-name">
      <!--<div class = "user_name">-->
      <?php echo h($user_list[$user_id]);?>
      <br/>
      <?php echo h($members_grades[$user_id]);?>
    <!--</div>-->
    </div>
    <div class = "member-photo">
      <?php
        if($group_pic_paths[$user_id] !== ''){
          $pic_path = $group_pic_paths[$user_id];
        } else {
          $pic_path = 'student_img/noPic.png';
        }
        echo $this->Html->image($pic_path,
          array(
            'width' => '150',
            'height'=> '150',
            'alt' => $pic_path
          ));
      ?>
    </div>
    <div class = "member-progress">
      Step教材の消化率
    </div>
    <div class = "member-soaps">
      過去4回分のSOAP
      <?php foreach($members_recent_soaps[$user_id] as $recent_soap):?>
      <div class = "soap-view">
        <?php
          $S = $recent_soap['ib_soaps']['S'];
          $O = $recent_soap['ib_soaps']['O'];
          $A = $recent_soap['ib_soaps']['A'];
          $P = $recent_soap['ib_soaps']['P'];
          $created = new DateTime($recent_soap['ib_soaps']['created']);
          $created_day = $created->format('m/d');
        ?>
        <div class = "soap-date"><?php echo $created_day; ?></div>
        <div class = "soap-material">教材</div>
        <div class = "soap-record">
          S: <?php echo $S ?> <br/>
          O: <?php echo $O ?> <br/>
          A: <?php echo $A ?> <br/>
          P: <?php echo $P ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach;?>

</div>
