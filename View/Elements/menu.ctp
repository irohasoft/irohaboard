  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <ul class="nav navbar-nav">
<?php
$is_active = (($this->name=='UsersCourses')||($this->name=='Contents')||($this->name=='ContentsQuestions')) ? ' active' : '';
echo '<li class="'.$is_active.'">'.$this->Html->link(__('受講コース'), array('controller' => 'users_courses', 'action' => 'index')).'</li>';

$is_active = ($this->name=='Infos') ? ' active' : '';
echo '<li class="'.$is_active.'">'.$this->Html->link(__('お知らせ'), array('controller' => 'infos', 'action' => 'index')).'</li>';
?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>
