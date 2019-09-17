<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('soapview');?>
<div class = "admin-student_view-index">

  <div class = "student-view">
    <div class = "student-name">
      <?php echo h($user_list[$user_id]);?>
      <br/>
      <!--学年-->
      <?php echo h($grade);?>
    </div>
    <div class = "student-photo">
      <?php
        echo $this->Html->image($pic_path,
          array(
            'width' => '150',
            'height'=> '150',
            'alt' => $pic_path,
          ));
      ?>
    </div>
    <div class = "student-progress">
      教材の消化率
      <div class="progress-meter">
      <table cellpadding="0" cellspacing="0">
        <tbody>
        <?php foreach($cleared_rates as $cleared_rate):
          $title = $cleared_rate['course_title'];
          $rate  = $cleared_rate['cleared_rate'];
        ?>
          <tr>
            <td><?php echo h($title)?>:&nbsp;<td/>
            <td><?php echo '<meter style="min-width: 200px" low="30" high="90" optimum="100" value='.$rate.'>'.round($rate*100).'%</meter>'; ?></td>
            <td><?php echo h(round($rate*100));?>%&nbsp;<td/>
          </tr>
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
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($recent_soaps as $recent_soap):
        $S = $recent_soap['Soap']['S'];
        $O = $recent_soap['Soap']['O'];
        $A = $recent_soap['Soap']['A'];
        $P = $recent_soap['Soap']['P'];
        $created = new DateTime($recent_soap['Soap']['created']);
        $created_day = $created->format('m/d');
      ?>
      <tr>
        <td><?php echo $created_day; ?></td>
        <td class="studied-material">
          <?php
            $content_id = $recent_soap['Soap']['studied_content'];
            $studied_content = $content_list[$content_id];
            echo h($studied_content);
          ?>
        </td>
        <td class="soap-block">
          <div class="soap-item"><b>S</b>:&nbsp;<?php echo h($S); ?>&nbsp;</div>
          <div class="soap-item"><b>O</b>:&nbsp;<?php echo h($O); ?>&nbsp;</div>
          <div class="soap-item"><b>A</b>:&nbsp;<?php echo h($A); ?>&nbsp;</div>
          <div class="soap-item"><b>P</b>:&nbsp;<?php echo h($P); ?>&nbsp;</div>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>

</div>