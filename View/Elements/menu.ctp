  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse">
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
