<?php $url = base_url('payslip')?>
<section class="content-header">
  <h1>
    Payslip
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title ?></h3>
    </div>
      <form class="form-horizontal" action="<?= "{$url}/generate"?>" method="GET" onsubmit="return confirm('Are you sure?')">
      <div class="box-body">
        <div class="alert alert-info"><p>Fields marked with <span class="fa fa-asterisk text-danger"></span> are required.</p></div>
        <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
        <?php if(is_numeric($num = $this->session->flashdata('mass_payroll_status_complete'))):?>
          <div class="alert alert-success"><?= $num?> payroll(s) has been created!</div>
        <?php endif;?>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Employee</label>
          <div class="col-sm-5">
            <?= form_dropdown('employee_number', [ 'all' => '*All employees*' ]+ $employees, FALSE, 'class="form-control" required="required"')?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Month</label>
          <div class="col-sm-4">
            <?= form_dropdown('month', ['' => '', 1  => 'First half', 2 => 'Second half'], FALSE, 'class="form-control" required="required" ')?>
          </div>
        <!-- <div class=> -->
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?= base_url('my_payslip');?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>