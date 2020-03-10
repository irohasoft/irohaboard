<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('soapview');?>
<div class = "admin-group_view-index">
  <?php foreach($members as $member):?>
  <div class = "student-block">
  <div class = "student-view">
    <?php $user_id = $member['User']['id']; ?>
    <div class = "student-name">
      <?php echo h($username_list[$user_id]);?><br/>
      <?php echo h($name_list[$user_id]);?><br/>
      <?php echo h($members_grades[$user_id]);?>
    </div>
    <div class = "student-photo">
      <?php
        $img_src = $this->Html->url(array(
          "controller" => "users",
          "action" => "show_picture",
          $user_id
        ), false);
      ?>
      <img src="<?php echo $img_src; ?>" height="150" alt="<?php echo h($name_list[$user_id]); ?>"/>
    </div>
    <div class = "student-progress">
      <div class="progress-meter">
      <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th nowrap>コース</th><th></th>
            <th nowrap>開始日</th><th></th>
            <th nowrap>消化率</th>
            <th></th><th></th>
            <th nowrap>最終学習日</th><th></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($members_cleared_rates[$user_id] as $cleared_rate):
          $title = $cleared_rate['course_title'];
          $start = $cleared_rate['start_date'];
          $last  = $cleared_rate['last_date'];
          $rate  = $cleared_rate['cleared_rate'];
        ?>
        <?php if(!is_null($rate)){?>
          <tr>
            <td nowrap><?php echo h($title)?>:&nbsp;<td/>
            <td nowrap><?php echo h($start)?>&nbsp;<td/>
            <td><?php echo '<meter style="min-width: 200px" low="30" high="90" optimum="100" value='.$rate.'>'.round($rate*100).'%</meter>'; ?></td>
            <td><?php echo h(round($rate*100));?>%&nbsp;<td/>
            <td nowrap><?php echo h($last)?>&nbsp;<td/>
          </tr>
        <?php }?>
        <?php endforeach; ?>
        </tbody>
      </table>
      </div>
    </div>
  </div>

  過去4回分のSOAP
  <table cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th></th>
        <th></th>
        <!--<th></th>-->
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($members_recent_soaps[$user_id] as $recent_soap):
        $S = $recent_soap['Soap']['S'];
        $O = $recent_soap['Soap']['O'];
        $A = $recent_soap['Soap']['A'];
        $P = $recent_soap['Soap']['P'];
        $created = new DateTime($recent_soap['Soap']['created']);
        $created_day = $created->format('m/d');
      ?>
      <tr>
        <td><?php echo $created_day; ?></td>
        <td>
          <?php
            $group_id = $recent_soap['Soap']['group_id'];
            $group_title = $group_list[$group_id];
            echo h($group_title);
          ?>
        </td>
        <!--
        <td class="studied-material">
          <?php
            /*
            $content_id = $recent_soap['Soap']['studied_content'];
            $studied_content = $content_list[$content_id];
            echo h($studied_content);
            */
          ?>
        </td>
        -->
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
  </div>
  <?php endforeach;?>
</div>
