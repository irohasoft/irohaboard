<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('custom');?>
<?php echo $this->Html->css('soap');?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawPeriod1Chart);
  google.charts.setOnLoadCallback(drawPeriod2Chart);

  function drawPeriod1Chart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Status');
    // Use custom HTML content for the domain tooltip.
    data.addColumn('number', 'Number');
    data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});

    var p1_sub_count = <?php echo $period_1_submitted['Count'];?>;
    var p1_sub_member = p1_sub_count >= 10 ? "" : "<p style='font-size : 20px; white-space : nowrap;'>" + "<?php echo $period_1_submitted['Member'];?>" + "</p>";
    var p1_unsub_count = <?php echo $period_1_unsubmitted['Count'];?>;
    var p1_unsub_member = "<p style='font-size : 20px; white-space : nowrap;'>" + "<?php echo $period_1_unsubmitted['Member'];?>" + "</p>";
    data.addRows([
      ['記入数',p1_sub_count, p1_sub_member],
      ['未記入数',p1_unsub_count, p1_unsub_member]
    ])
    var options = {
      pieHole: 0.6,
      fontSize: 18,
      legend: { position: 'top', alignment: 'center' },
      'chartArea': {'width': '100%', 'height': '80%'},
      tooltip: { isHtml: true }
    }
    var chart = new google.visualization.PieChart(document.getElementById('period1Chart'));
    chart.draw(data, options);
  }

  function drawPeriod2Chart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Status');
    // Use custom HTML content for the domain tooltip.
    data.addColumn('number', 'Number');
    data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});

    var p2_sub_count = <?php echo $period_2_submitted['Count'];?>;
    var p2_sub_member = p2_sub_count >= 10 ? "" : "<p style='font-size : 20px; white-space : nowrap;'>" + "<?php echo $period_2_submitted['Member'];?>" + "</p>";
    var p2_unsub_count = <?php echo $period_2_unsubmitted['Count'];?>;
    var p2_unsub_member = "<p style='font-size : 20px; white-space : nowrap;'>" + "<?php echo $period_2_unsubmitted['Member'];?>" + "</p>";
    data.addRows([
      ['記入数',p2_sub_count, p2_sub_member],
      ['未記入数',p2_unsub_count, p2_unsub_member]
    ])
    var options = {
      pieHole: 0.6,
      fontSize: 18,
      legend: { position: 'top', alignment: 'center' },
      'chartArea': {'width': '100%', 'height': '80%'},
      tooltip: { isHtml: true }
    }
    var chart = new google.visualization.PieChart(document.getElementById('period2Chart'));
    chart.draw(data, options);
  }



</script>
<div class="admin-submission-status full-view">
  <div class="row">
    <div class="col" style = "font-size : 24px;">
      <?php echo __('SOAP記入状況'); ?>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <h5><?php echo __('授業日:').$last_day;?></h5>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <p class="text-center" style="font-size:18px;">１限 出席人数: <?php echo ($period_1_submitted['Count'] + $period_1_unsubmitted['Count']);?>人, 記入: <?php echo ($period_1_submitted['Count']);?>人, 未記入: <?php echo ($period_1_unsubmitted['Count']);?>人</p>
    </div>
    <div class="col">
      <p class="text-center" style="font-size:18px;">２限 出席人数: <?php echo ($period_2_submitted['Count'] + $period_2_unsubmitted['Count']);?>人, 記入: <?php echo ($period_2_submitted['Count']);?>人, 未記入: <?php echo ($period_2_unsubmitted['Count']);?>人</p>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="card" id="chart" onclick="location.href='<?php echo Router::url(array('controller' => 'soaprecords', 'action' => 'index')) ?>'">
        <div class="card-body" id="chart-body">
          <div class="pie-chart" id="period1Chart"></div>
          <div class="labelOverlay">
            <p class="total-caption">一限出席者</p>
            <p class="total-value"><?php echo ($period_1_submitted['Count'] + $period_1_unsubmitted['Count']);?>人</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card" id="chart" onclick="location.href='<?php echo Router::url(array('controller' => 'soaprecords', 'action' => 'index')) ?>'">
        <div class="card-body" id="chart-body">
          <div class="pie-chart" id="period2Chart"></div>
          <div class="labelOverlay">
            <p class="total-caption">二限出席者</p>
            <p class="total-value"><?php echo ($period_2_submitted['Count'] + $period_2_unsubmitted['Count']);?>人</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
