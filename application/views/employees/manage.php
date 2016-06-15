<?php $url = base_url('employees')?>
<section class="content-header">
  <h1>
    Employees
    <small></small>
  </h1>
</section>
<section class="content">
  <div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
      <li class="active"><a href="#basic" data-toggle="tab">Personal Information</a></li>
      <li class="pull-left header"><?= $title ?></li>
    </ul>
    <div class="tab-content">
      <!-- Morris chart - Sales -->
      <div class="tab-pane active" id="basic">
        <form class="form-horizontal" data-action="<?= $mode === MODE_CREATE ? "{$url}/store" : "{$url}/update/{$data['id']}" ?>">
            <div class="alert alert-info"><p>Fields marked with <span class="fa fa-asterisk text-danger"></span> are required.</p></div>
            <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Employee Name</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="lastname" value="<?= preset($data, 'lastname', '')?>" />
                <span class="help-block">Last name</span>
              </div>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="firstname" value="<?= preset($data, 'firstname', '')?>" />
                <span class="help-block">First name</span>
              </div>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="middleinitial" value="<?= preset($data, 'middleinitial', '')?>" />
                <span class="help-block">Middle initial</span>
              </div>
            </div>

            <div class="form-group">              
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Account type</label>
              <div class="col-sm-3">
              <?= form_dropdown('account_type', ['' => '', 'ad' => 'Admin', 'em' => 'Employee', 'pm' => 'Payroll manager'], preset($data, 'account_type', ''), 'class="form-control" onChange="AccountTypeFunc(this.value)"')?>
              </div>
            </div>

            <div class="password_fields"<?= ($data['account_type']!="ad" && $data['account_type']!="pm")?"style='display: none;'":"";?>>
              <?php if($mode!==MODE_CREATE):?>
                <div class="form-group">              
                  <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> User password</label>
                  <div class="col-sm-3">
                    <?= form_password(['name'=>'old_password', 'class'=>'form-control']);?>
                  </div>
                </div>
              <?php endif;?>

              <div class="form-group password_field">              
                <label class="col-sm-2 control-label">
                  <?php if($mode===MODE_CREATE):?>
                    <span class="fa fa-asterisk text-danger"></span>
                  <?php endif;?>
                   Enter account password</label>
                <div class="col-sm-3">
                  <?= form_password(['name'=>'password', 'class'=>'form-control']);?>
                </div>
                <label class="col-sm-2 control-label">
                  <?php if($mode===MODE_CREATE):?>
                    <span class="fa fa-asterisk text-danger"></span>
                  <?php endif;?>
                   Confirm password</label>
                <div class="col-sm-3">
                  <?= form_password(['name'=>'confirm_password', 'class'=>'form-control']);?>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Birthdate</label>
              <div class="col-sm-3">
                <input type="text" class="form-control datepicker" name="birthdate" value="<?= preset($data, 'birthdate', '')?>" />
                <span class="help-block">mm/dd/yyyy</span>
              </div>
            </div>
             <div class="form-group">
              <label class="col-sm-2 control-label">Birth Place</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="birthplace"><?= preset($data, 'birthplace', '')?></textarea>
              </div>
            </div>
              <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Gender</label>
              <div class="col-sm-3">
               <?= form_dropdown('gender', ['' => '', 'M' => 'Male', 'F' => 'Female'], preset($data, 'gender', FALSE), 'class="form-control"')?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Civil Status</label>
              <div class="col-sm-5">
                <?= form_dropdown('civil_status', ['' => '', 'sg' => 'Single', 'm' => 'Married', 'sp' => 'Separated', 'd' => 'Divorced', 'w' => 'Widowed'], preset($data, 'civil_status', FALSE), 'class="form-control"')?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Nationality</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="nationality" value="<?= preset($data, 'nationality', '')?>" />
              </div>
            </div>
             <div class="form-group">
              <label class="col-sm-2 control-label">Religion</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="religion" value="<?= preset($data, 'religion', '')?>" />
              </div>
            </div>
             <div class="form-group">
              <label class="col-sm-2 control-label">Address</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="full_address"><?= preset($data, 'full_address', '')?></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Email address</label>
              <div class="col-sm-3">
                <input type="email" class="form-control" name="email_address" value="<?= preset($data, 'email_address', '')?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Mobile Number</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="mobile_number" value="<?= preset($data, 'mobile_number', '')?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Date hired</label>
              <div class="col-sm-3">
                <input type="text" class="form-control datepicker" name="date_hired" value="<?= preset($data, 'date_hired', '')?>" />
                <span class="help-block">mm/dd/yyyy</span>
              </div>
            </div>
            <hr>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> SSS #</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="sss_number" value="<?= preset($data, 'sss_number', '')?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> PAG-IBIG #</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="pagibig_number" value="<?= preset($data, 'pagibig_number', '')?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"> TIN #</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="tin_number" value="<?= preset($data, 'tin_number', '')?>" />
              </div>
            </div>
            <hr>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Department</label>
              <div class="col-sm-4">
                <?= form_dropdown('department_id', ['' => ''] + $departments, preset($data, 'department_id', FALSE), 'class="form-control"')?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Position</label>
              <div class="col-sm-4">
                <?= form_dropdown('position_id', ['' => ''] + $positions, preset($data, 'position_id', FALSE), 'class="form-control"')?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Biometric ID</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="rfid_uid" value="<?= preset($data, 'rfid_uid', '')?>" />
              </div>
            </div>
            <hr>
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
                <input type="text" class="form-control"  name="overtime_rate" value="<?= preset($data, 'overtime_rate', 0)?>" aria-describedby="basic-addon2">
                <span class="input-group-addon" id="basic-addon2">%</span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Allowed late period</label>
            <div class="col-sm-3">
              <input name="allowed_late_period" min="0" step="0.01" value="<?= preset($data, 'allowed_late_period', 0)?>" class="form-control"/>
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
          <input type="hidden" data-name="index"  data-value="<?= isset($data['particulars']) &&  $data['particulars'] ? count($data['particulars']) : 1?>"/>
            <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
            <button type="submit" class="btn btn-success btn-flat">Submit</button>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
function AccountTypeFunc(account_type_value){
  if(account_type_value=="ad" || account_type_value=="pm")
    $('.password_fields').show();
  else if(account_type_value=="em")
    $('.password_fields').hide();
}
</script>