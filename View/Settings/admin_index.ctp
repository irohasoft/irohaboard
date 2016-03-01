<?php echo $this->element('admin_menu');?>
<div class="settings index">
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo __('システム設定'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Setting', array('class' => 'form-horizontal')); ?>
			
			<div class="form-group">
				<label for="vs1" class="control-label col-sm-2">システム名</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="title" value="<?php echo h(SessionHelper::read('Setting.title'))?>">
				</div>
			</div>

			<div class="form-group">
				<label for="vs1" class="control-label col-sm-2">コピーライト</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="copyright" value="<?php echo h(SessionHelper::read('Setting.copyright'))?>">
				</div>
			</div>

			<div class="form-group">
				<label for="vs1" class="control-label col-sm-2">テーマカラー</label>
				<div class="col-sm-8">
					<select class="form-control" name="color">
						<option value="#337ab7">default</option>
						<option value="royalblue">royalblue</option>
						<option value="darkgreen">darkgreen</option>
						<option value="crimson">crimson</option>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label for="vs1" class="control-label col-sm-2">全体のお知らせ</label>
				<div class="col-sm-8">
					<textarea class="form-control" name="information"><?php echo h(SessionHelper::read('Setting.information'))?></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-8">
					<?php echo $this->Form->end(array('label' => __('保存'), 'class' => 'btn btn-default')); ?>
				</div>
			</div>
		</div>
	</div>
	
</div>
