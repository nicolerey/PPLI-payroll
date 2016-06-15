<?php $url = base_url('salaries')?>
<section class="content-header">
  <h1>
    Salaries
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title ?></h3>
    </div>
      <form class="form-horizontal" data-action="<?= $mode === MODE_CREATE ? "{$url}/store" : "{$url}/update/{$data['id']}" ?>">
      <div class="box-body">
        <div class="alert alert-info"><p>Fields marked with <span class="fa fa-asterisk text-danger"></span> are required.</p></div>
        <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Position</label>
          <div class="col-sm-3">
            <td><?= form_dropdown('position_id', ['' => ''] + $positions, preset($data, 'position_id', ''), 'class="form-control"')?></td>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Work days</label>
          <div class="col-sm-3">
            <?= form_dropdown('day_of_week_start', ['' => ''] + $days, preset($data, 'day_of_week_start', ''), 'class="form-control"');?>
            <span class="help-block">From</span>
          </div>
          <div class="col-sm-3">
            <?= form_dropdown('day_of_week_end', ['' => ''] + $days, preset($data, 'day_of_week_end', ''), 'class="form-control"');?>
            <span class="help-block">To</span>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Time (AM)</label>
          <div class="col-sm-3">
            <input type="text" name="hour_of_day_start_am" value="<?= preset($data, 'hour_of_day_start_am', '') ? date('H:i', strtotime($data['hour_of_day_start_am'])) : ''?>" class="form-control"/>
            <span class="help-block">In</span>
          </div>
          <div class="col-sm-3">
            <input type="text" name="hour_of_day_end_am" value="<?= preset($data, 'hour_of_day_end_am', '') ? date('H:i', strtotime($data['hour_of_day_end_am'])) : ''?>" class="form-control"/>
            <span class="help-block">Out</span>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Time (PM)</label>
          <div class="col-sm-3">
            <input type="text" name="hour_of_day_start_pm" value="<?= preset($data, 'hour_of_day_start_pm', '') ? date('H:i', strtotime($data['hour_of_day_start_pm'])) : ''?>" class="form-control"/>
            <span class="help-block">In</span>
          </div>
          <div class="col-sm-3">
            <input type="text" name="hour_of_day_end_pm" value="<?= preset($data, 'hour_of_day_end_pm', '') ? date('H:i', strtotime($data['hour_of_day_end_pm'])) : ''?>" class="form-control"/>
            <span class="help-block">Out</span>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Daily wage</label>
          <div class="col-sm-3">
            <input type="number" name="daily_rate"  min="0" step="0.01" value="<?= number_format(preset($data, 'daily_rate', 0), 2)?>" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Overtime rate</label>
          <div class="col-sm-3">
            <input type="number" name="overtime_rate" min="0" step="0.01" value="<?= preset($data, 'overtime_rate', 0)?>" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Allowed late period</label>
          <div class="col-sm-3">
            <input type="number" name="allowed_late_period" min="0" step="0.01" value="<?= preset($data, 'allowed_late_period', 0)?>" class="form-control"/>
            <span class="help-block">in minutes</span>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Late penalty</label>
          <div class="col-sm-3">
            <input type="number" name="late_penalty" min="0" step="0.01" value="<?= preset($data, 'late_penalty', 0)?>" class="form-control"/>
            <span class="help-block">per minute</span>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-8">
            <table class="table" id="particulars">
              <thead><tr class="active"><th>Particulars</th><th>Amount</th><th></th></tr></thead>
              <tbody>
                <?php if(isset($data['particulars'])):?>
                  <?php foreach($data['particulars'] AS $key => $row):?>
                    <tr>
                      <td><?= form_dropdown("particulars[{$key}][particulars_id]", ['' => ''] + $particulars, $row['particulars_id'], 'class="form-control" data-name="particulars[idx][particulars_id]"')?></td>
                      <td><input type="text" class="form-control" data-name="particulars[idx][amount]" name="particulars[<?=$key?>][amount]" value="<?= number_format($row['amount'], 2)?>" /></td>
                      <td><a class="btn btn-flat btn-danger btn-sm remove"><i class="fa fa-times"></i></a></td>
                    </tr>
                  <?php endforeach;?>
                <?php else:?>
                  <tr>
                      <td><?= form_dropdown("particulars[0][particulars_id]", ['' => ''] + $particulars, FALSE, 'class="form-control" data-name="particulars[idx][particulars_id]"')?></td>
                      <td><input type="text" class="form-control" data-name="particulars[idx][amount]" name="particulars[0][amount]"/></td>
                      <td><a class="btn btn-flat btn-danger btn-sm remove"><i class="fa fa-times"></i></a></td>
                    </tr>
                <?php endif;?>
              </tbody>
              <tfoot>
                <tr><td colspan="3"><a id="add-particulars" class="btn btn-default btn-flat btn-sm"><i class="fa fa-plus"></i> Add new line</a></td></tr>
              </tfoot>
            </table>
            <input type="hidden" data-name="index"  data-value="<?= isset($data['particulars']) &&  $data['particulars'] ? count($data['particulars']) : 1?>"/>
          </div>
        </div>
        <!-- <div class=> -->
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>