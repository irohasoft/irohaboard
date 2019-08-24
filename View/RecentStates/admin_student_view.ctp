<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('recentstate');?>
<div class = "admin-student_view-index">
  <div class = "member-view">
    <div class = "member-name">
      <!--<div class = "user_name">-->
      受講生名 プロ太郎
      <br/>
      小6
    <!--</div>-->
    </div>
    <div class = "member-photo">
      <?php
        echo $this->Html->image('student_img/test-man.jpeg',
          array(
            'width' => '150',
            'height'=> '150',
            'alt' => 'test-man'
          ));
      ?>
    </div>
    <div class = "member-progress">
      Step教材の消化率
    </div>
    <div class = "member-soaps">
      過去4回分のSOAP
      <div class = "soap-view">
        <div class = "soap-date">6/30</div>
        <div class = "soap-material">電卓</div>
        <div class = "soap-record">
          S:<br/>
          O:<br/>
          A:<br/>
          P:
        </div>
      </div>
      <div class = "soap-view">
        <div class = "soap-date">7/4</div>
        <div class = "soap-material">エラーメッセージの読み方</div>
        <div class = "soap-record">
          S:<br/>
          O:<br/>
          A:<br/>
          P:
        </div>
      </div>
      <div class = "soap-view">
        <div class = "soap-date">7/14</div>
        <div class = "soap-material">自主練(文字列操作1)</div>
        <div class = "soap-record">
          S:<br/>
          O:<br/>
          A:<br/>
          P:
        </div>
      </div>
      <div class = "soap-view">
        <div class = "soap-date">7/21</div>
        <div class = "soap-material">タートルグラフィック(1)</div>
        <div class = "soap-record">
          S:<br/>
          O:<br/>
          A:<br/>
          P:
        </div>
      </div>
    </div>
  </div>

</div>
