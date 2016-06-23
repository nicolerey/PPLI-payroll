<?php $url = base_url('positions')?>
<section class="content-header">
  <h1>
    Positions
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
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Position name</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" name="name" value="<?= preset($data, 'name', '')?>" />
          </div>
        </div>

        <?php if($this->session->userdata('account_type')!=='pm'):?>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Daily wage</label>
            <div class="col-sm-3">
              <input name="daily_rate"  min="0" step="0.01" value="<?= number_format(preset($data, 'daily_rate', 0), 2)?>" class="form-control pformat"/>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Overtime rate</label>
            <div class="col-sm-3">
              <div class="input-group">
                <input type="text" class="form-control pformat"  name="overtime_rate" value="<?= preset($data, 'overtime_rate', 0)?>" aria-describedby="basic-addon2">
                <span class="input-group-addon" id="basic-addon2">%</span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Allowed late period</label>
            <div class="col-sm-3">
              <input name="allowed_late_period" min="0" step="0.01" value="<?= preset($data, 'allowed_late_period', 0)?>" class="form-control pformat"/>
              <span class="help-block">in minutes</span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Late penalty</label>
            <div class="col-sm-3">
              <input name="late_penalty" min="0" step="0.01" value="<?= preset($data, 'late_penalty', 0)?>" class="form-control pformat"/>
              <span class="help-block">per minute</span>
            </div>
          </div>
          <table class="table" id="particulars">
            <thead><tr class="active"><th>Particulars</th><th>Amount</th><th></th></tr></thead>
            <tbody>
              <?php if(isset($data['particulars']) && $data['particulars']):?>
                <?php foreach($data['particulars'] AS $key => $row):?>
                  <tr>
                    <td><?= form_dropdown("particulars[{$key}][particulars_id]", ['' => ''] + $particulars, $row['particulars_id'], 'class="form-control" data-name="particulars[idx][particulars_id]"')?></td>
                    <td><input type="text" class="form-control pformat" data-name="particulars[idx][amount]" name="particulars[<?=$key?>][amount]" value="<?= number_format($row['amount'], 2)?>" /></td>
                    <td><a class="btn btn-flat btn-danger btn-sm remove"><i class="fa fa-times"></i></a></td>
                  </tr>
                <?php endforeach;?>
              <?php else:?>
                <tr>
                    <td><?= form_dropdown("particulars[0][particulars_id]", ['' => ''] + $particulars, FALSE, 'class="form-control" data-name="particulars[idx][particulars_id]"')?></td>
                    <td><input type="text" class="form-control pformat" data-name="particulars[idx][amount]" name="particulars[0][amount]"/></td>
                    <td><a class="btn btn-flat btn-danger btn-sm remove"><i class="fa fa-times"></i></a></td>
                  </tr>
              <?php endif;?>
            </tbody>
            <tfoot>
              <tr><td colspan="3"><a id="add-particulars" class="btn btn-default btn-flat btn-sm"><i class="fa fa-plus"></i> Add new line</a></td></tr>
            </tfoot>
          </table>
        <?php endif;?>
        
        <hr>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Work days</label>         
          <div class="col-sm-10">
            <table class="table workday_container">
              <tbody class="workday_field hidden">
                <tr>
                  <td class="col-sm-2" rowspan="2" style="vertical-align: middle;">
                    <select class="form-control day_field">
                      <option value="">Select work day</option>
                      <?php foreach($days as $index=>$day):?>
                          <option value="<?= $index;?>"><?= substr($day, 0, 3);?></option>
                      <?php endforeach;?>
                    </select>
                  </td>
                  <td class="col-sm-1 text-right">
                    <label class="control-label">1st half</label>
                  </td>
                  <td class="col-sm-3">
                    <div class="col-sm-6">
                      <input type="text" value="" class="form-control timepicker from_time_field" placeholder="Work time"/>
                    </div>
                    <div class="col-sm-6">
                      <input type="text" value="" class="form-control timepicker to_time_field" placeholder="Work time"/>
                    </div>
                  </td>
                  <td class="col-sm-1" rowspan="2" style="vertical-align: middle;">
                    <button type="button" class="btn btn-flat btn-danger" onclick="delete_workday_group(this);">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td class="col-sm-1 text-right">
                    <label class="control-label">2nd half</label>
                  </td>
                  <td class="col-sm-3">
                    <div class="col-sm-6">
                      <input type="text" value="" class="form-control timepicker from_time_field" placeholder="Work time"/>
                    </div>
                    <div class="col-sm-6">
                      <input type="text" value="" class="form-control timepicker to_time_field" placeholder="Work time"/>
                    </div>
                  </td>
                </tr>
              </tbody>
              <?php if(!empty($data['workday'])):?>
                <?php foreach(json_decode($data['workday'], true) as $value):?>
                  <tbody class="workday_field">
                    <tr>
                      <td class="col-sm-2" rowspan="2" style="vertical-align: middle;">
                        <select class="form-control day_field" name="day[]">
                          <option value="">Select work day</option>
                          <?php foreach($days as $index=>$day):?>
                              <option value="<?= $index;?>"<?= ($value['day']==$index)?" selected":""; ?>><?= substr($day, 0, 3);?></option>
                          <?php endforeach;?>
                        </select>
                      </td>
                      <td class="col-sm-1 text-right">
                        <label class="control-label">1st half</label>
                      </td>
                      <td class="col-sm-3">
                        <div class="col-sm-6">
                          <input type="text" value="<?= $value['time']['from_time_1'];?>" class="form-control timepicker from_time_field" placeholder="Work time" name="from_time_1[]"/>
                        </div>
                        <div class="col-sm-6">
                          <input type="text" value="<?= $value['time']['to_time_1'];?>" class="form-control timepicker to_time_field" placeholder="Work time" name="to_time_1[]"/>
                        </div>
                      </td>
                      <td class="col-sm-1" rowspan="2" style="vertical-align: middle;">
                        <button type="button" class="btn btn-flat btn-danger" onclick="delete_workday_group(this);">
                          <span class="glyphicon glyphicon-remove"></span>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-sm-1 text-right">
                        <label class="control-label">2nd half</label>
                      </td>
                      <td class="col-sm-3">
                        <div class="col-sm-6">
                          <input type="text" value="<?= $value['time']['from_time_2'];?>" class="form-control timepicker from_time_field" placeholder="Work time" name="from_time_2[]"/>
                        </div>
                        <div class="col-sm-6">
                          <input type="text" value="<?= $value['time']['to_time_2'];?>" class="form-control timepicker to_time_field" placeholder="Work time" name="to_time_2[]"/>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                <?php endforeach;?>
              <?php endif;?>
            </table>
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-sm-10"></div>
          <div class="col-sm-1">
            <button type="button" class="btn btn-flat btn-success" onclick="add_workday(this);">
              <span class="glyphicon glyphicon-plus"></span> Add work day
            </button>
          </div>
        </div>

      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>

<script>
  function add_workday(element){
    var workday_group = $('.workday_field').first().clone().removeClass('hidden');

    workday_group.find('.timepicker').timepicker({'defaultTime':false});
    workday_group.find('.day_field').attr('name', 'day[]');

    workday_group.find('.from_time_field').first().attr('name', 'from_time_1[]');
    workday_group.find('.to_time_field').first().attr('name', 'to_time_1[]');
    workday_group.find('.from_time_field').last().attr('name', 'from_time_2[]');
    workday_group.find('.to_time_field').last().attr('name', 'to_time_2[]');

    $('.workday_container').append(workday_group);
  }
  
  function delete_workday_group(element){
    $(element).closest('.workday_field').remove();
  }
</script>