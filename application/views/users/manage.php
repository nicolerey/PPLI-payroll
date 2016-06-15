<?php $url = base_url('users')?>
<section class="content-header">
  <h1>
    Manage user accounts
    <small></small>
  </h1>
</section>
<section class="content">

  <div class="row">
    <div class="col-md-5">
      <!-- Default box -->
      <div class="box box-solid">
          
        <div class="box-body">
              <form>
                <div class="form-group">
                  <label>Please input employee number:</label>
                  <div class="input-group">
                    <input type="text" class="form-control" value="<?= $this->input->get('employee_number')?>" name="employee_number" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go</button>
                    </span>
                  </div><!-- /input-group -->
                </div>
              </form>
              
            <?php if(isset($data['employee_data']) && $data['employee_data']):?>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">Account information</h3>
                </div>
                <form data-action="<?= "{$url}/save/{$data['employee_data']['id']}"?>" id="account">
                  <div class="panel-body">
                    <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
                    <?php if(!preset($data['employee_account'], 'type', '')):?>
                      <div class="alert alert-warning">Employee has currently no account. Select an account type below:</div>
                    <?php endif;?>
                    <div class="form-group">
                      <label>Name</label>
                      <p class="form-control-static"><?= "{$data['employee_data']['firstname']} {$data['employee_data']['middlename']} {$data['employee_data']['lastname']}"?> </p>
                    </div>

                      <div class="form-group">
                        <label>Current account type</label>
                        <?= form_dropdown('type', ['' => '', 're' => 'Regular Employee', 'sv' => 'Supervisor', 'hr' => 'HR Officer', 'po' => 'Payroll Officer'], preset($data['employee_account'], 'type', ''), 'class="form-control"')?>
                      </div>
                  
                  </div>
                  <div class="panel-footer">
                    <button class="btn btn-flat btn-success" type="submit">Submit</button>
                  </div>
                </form>
              </div>
            <?php endif;?>
        </div><!-- /.box-body -->
        
      </div><!-- /.box -->
    </div>
  </div>
</section>