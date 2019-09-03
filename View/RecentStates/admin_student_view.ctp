<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('recentstate');?>
<div class = "admin-student_view-index">

  <div class = "member-view">
    <div class = "member-name">
      <!--<div class = "user_name">-->
      <?php echo h($user_list[$user_id]);?>
      <br/>
      <!--学年-->
      <?php echo h($grade);?>
    <!--</div>-->
    </div>
    <div class = "member-photo">
      <?php
        echo $this->Html->image($pic_path,
          array(
            'width' => '150',
            'height'=> '150',
            'alt' => $pic_path,
          ));
      ?>
    </div>
    <div class = "member-progress">
      Step教材の消化率
    </div>
    <div class = "member-soaps">
      過去4回分のSOAP
      <?php foreach($recent_soaps as $recent_soap):?>
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
      <?php endforeach;?>
    </div>
  </div>

</div>
