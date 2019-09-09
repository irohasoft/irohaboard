<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('soapview');?>
<div class = "admin-group_view-index">

  <?php foreach($members as $member):?>
  <div class = "student-view">
    <?php $user_id = $member['ib_users']['id']; ?>
    <div class = "student-name">
      <?php echo h($user_list[$user_id]);?>
      <br/>
      <!--学年-->
      <?php echo h($members_grades[$user_id]);?>
    </div>
    <div class = "student-photo">
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
    <div class = "student-progress">
      Step教材の消化率
    </div>
  </div>

  過去4回分のSOAP
  <table cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($members_recent_soaps[$user_id] as $recent_soap):
        $S = $recent_soap['ib_soaps']['S'];
        $O = $recent_soap['ib_soaps']['O'];
        $A = $recent_soap['ib_soaps']['A'];
        $P = $recent_soap['ib_soaps']['P'];
        $created = new DateTime($recent_soap['ib_soaps']['created']);
        $created_day = $created->format('m/d');
      ?>
      <tr>
        <td><?php echo $created_day; ?></td>
        <td class="studied-material">今回学習した教材</td>
        <td>
          <div class="soap-item"><b>S</b>:&nbsp;<?php echo h($S); ?>&nbsp;</div>
          <div class="soap-item"><b>O</b>:&nbsp;<?php echo h($O); ?>&nbsp;</div>
          <div class="soap-item"><b>A</b>:&nbsp;<?php echo h($A); ?>&nbsp;</div>
          <div class="soap-item"><b>P</b>:&nbsp;<?php echo h($P); ?>&nbsp;</div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endforeach;?>

</div>
