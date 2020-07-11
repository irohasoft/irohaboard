<?php echo $this->element('menu');?>

<div class="progress-detail-index full-view">
  <div class="ib-breadcrumb">
  <?php
		$this->Html->addCrumb('成果発表一覧', array('controller' => 'progresses', 'action' => 'index'));
		$this->Html->addCrumb(h($progress_info['Progress']['title']));

		echo $this->Html->getCrumbs(' / ');
	?>
  <div class="accordion mt-3 mb-3" id="progress">
    <?php $cnt = 0;?>
    <?php foreach ($progress_details as $detail):?>
      <?php 
        $user_id = $detail['user_id'];
        $cnt++;
      ?>
      <div class="card ">
        <div class="card-header" id=<?php echo h("header-".$cnt);?>>

          <h5 class="mb-0">
            <button class="btn row" type="button" data-toggle="collapse" 
              style="width: 100%;"
              data-target=<?php echo h("#collapse-".$cnt);?> 
              aria-expanded="true" aria-controls=<?php echo h("collapse-".$cnt);?>
              >
              <div class="font-weight-bold text-left h4"><?php echo h($detail['title'])?></div>
              <div class="text-left"><?php echo h("Developer: ".$user_list[$user_id])?></div>
            </button>
          </h5>

        </div>

        <div id=<?php echo h("collapse-".$cnt);?> class="collapse"
        aria-labelledby=<?php echo h("header-".$cnt);?>
        data-parent="#progress">
          <div class="card-body">
            <pre><?php echo $detail['body']?></pre>
            <?php
              
              $this->log($detail);

              if(!empty($detail['url'])){
                $url = $detail['url'];
                // 相対URLの場合、絶対URLに変更する
				        if(mb_substr($url, 0, 1)=='/')
                  $url = FULL_BASE_URL.$url;

                $download_link = $this->Html->link(
                  'ダウンロード',
                  $url,
                  array(
                    'target' => '_blank',
                    'download' => $detail['file_name'],
                    'class' => 'btn btn-primary'
                  )
                );
                ?>
                  <p>ソースコード：<?php echo $download_link; ?></p>
                <?php
              }
            ?>

          </div>
        </div>
        
      </div>
    <?php endforeach;?>
  </div>
  </div>
</div>