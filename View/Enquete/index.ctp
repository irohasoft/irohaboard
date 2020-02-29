<?php echo $this->element('menu');?>
<?php echo $this->Html->css('enquete');?>
<div class="enquete-input-header">
  <div class="ib-page-title mb-3"><?php echo __('アンケート'.'('.$today.')'.'記入')?></div>
  <div class="enquete-input-block">
  <?php
    //echo $this->Form->create("enquete-input",array('novalidate' => true));
    echo $this->Form->create(false,['type' => 'post','url'=> ['controller' => 'enquete','action' => 'index'],'novalidate' => true]);
		echo $this->Form->hidden('group_id',array('value' => $group_id));
  ?>
  <div class="form-input-block enquete-input-group required-input">
  <?php
    echo $this->Form->input('group_id',array(
      'label' => __('個別指導の担当講師：'),
      //'before' => '<label class="col col-sm-3 control-label">コンテンツ種別</label>',
      'after' => '<div class = "text-url-input"></div><span class="status-exp">今日の授業の中で，一番多く指導してくれた講師．</span>',
    	'div' => false,
    	'class' => '',
      'required'=> 'required',
    	'options' => $group_list,
      'empty' => '',
      'value' => $enquete_inputted['Enquete']['group_id'],
  	  'style' => ''
    ));
  echo "</div>";

  echo "<div class = 'form-input-block before-goal-block'>";
  echo $this->Form->input('before_goal_cleared',	array(
    'type' => 'radio',
    'before' => '<label class = "before-goal-label">前回に設定したゴールは達成できたか？</label>',
    'after'  => '<div class = "text-url-input"></div><span class="status-exp">前回設定したゴール: '.h($previous_next_goal).'</span>',
    'separator'=>"  ",
    'legend' => false,
    'div' => '',
    'class' => '',
    'style' => '',
    'required'=> 'required',
    'options' => Configure::read('true_or_false'),
    'value' => $enquete_inputted['Enquete']['before_goal_cleared']
  ));
  echo "</div>";

  echo "<div class = 'form-input-block before-goal-false-reason' >";
  echo $this->Form->input('before_false_reason',array(
    'label' => __('前回に設定したゴールは達成できなかったら，その理由を書いてください．'),
    'type' => 'textarea',
    'div' => false,
    'class' => '',
    'style' => '',
    'value' => $enquete_inputted['Enquete']['before_false_reason']
  ));
  echo "</div>";

  echo "<div class = 'form-input-block today-goal required-input ' >";
  echo $this->Form->input('today_goal',array(
    'label' => __('今日の授業のゴールを書いてください'),
    'type' => 'textarea',
    'div' => false,
    'class' => '',
    'required'=> 'required',
    'style' => '',
    'value' => $enquete_inputted['Enquete']['today_goal']
  ));
  echo "</div>";


  echo "<div class = 'form-input-block today-goal-cleared  ' >";
  echo $this->Form->input('today_goal_cleared',array(
    'before' => '<label class = "before-goal-label">今日の授業のゴールは達成できましたか？(できた人はTrue、そうでない人はFalseを選んでください)</label></br>',
    'type' => 'radio',
    'separator'=>"  ",
    'legend' => false,
    'div' => '',
    'class' => '',
    'style' => '',
    'required'=> 'required',
    'options' => Configure::read('true_or_false'),
    'value' => $enquete_inputted['Enquete']['today_goal_cleared']
  ));
  echo "</br>";
  echo "</div>";

  echo "<div class = 'form-input-block today-false-reason' >";
  echo $this->Form->input('today_false_reason',array(
    'label' => __('今日のゴールの達成度で、Falseを選んだ理由を書いてください'),
    'type' => 'textarea',
    //'before' => '<label class="col col-sm-3 control-label">コンテンツ種別</label>',
    'after' => '<div class = "text-url-input"></div><span class="status-exp">なぜできなかった、何がわからなかった、など</span></br>',
    'div' => false,
    'class' => '',
    'style' => '',
    'value' => $enquete_inputted['Enquete']['today_false_reason']
  ));
  echo "</div>";

  echo "<div class = 'form-input-block next-goal '>";
  echo $this->Form->input('next_goal',array(
    'label' => __('次回の授業に来る時までに達成するゴールを書いてください'),
    'type' => 'textarea',
    //'before' => '<label class="col col-sm-3 control-label">コンテンツ種別</label>',
    'after' => '<div class = "text-url-input"></div><span class="status-exp">プロジェクトのある機能を実現する、あるスキルをマスターする、など</span></br>',
    'div' => false,
    'class' => '',
    'required'=> 'required',
    'style' => '',
    'value' => $enquete_inputted['Enquete']['next_goal']
  ));
  echo "</div>";

  echo "<div class = 'form-input-block today-impressions ' >";
  echo $this->Form->input('today_impressions',array(
    'label' => __('今日の感想を書いてください'),
    'type' => 'textarea',
    //'before' => '<label class="col col-sm-3 control-label">コンテンツ種別</label>',
    'after' => '<div class = "text-url-input"></div><span class="status-exp">今日やったこと、躓いたこと、勉強になったこと、解決できなかったこと、など</span></br>',
    'div' => false,
    'class' => '',
    'required'=> 'required',
    'style' => '',
    'value' => $enquete_inputted['Enquete']['today_impressions']
  ));
  echo "</div>"
;  ?>
  <input type = "submit" class = "btn btn-info btn-add" value = "送信">
  <?php echo $this->Form->end(); ?>
  </div>
</div>
