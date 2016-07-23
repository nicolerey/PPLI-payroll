<style type="text/css">
  table td{
    vertical-align: middle!important;
  }
  .form-group{
    margin-bottom:5px!important;
  }
</style>
<?php $url = base_url('my_payslip')?>
<section class="content-header">
  <h1>
    My Payslips
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->

  <div class="box box-solid">
    <form class="form-horizontal" data-action="<?= base_url("my_payslip/store_manual_payslip")?>">
      <div class="box-body">
        <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>

        <div class="form-group">
          <div class="col-sm-8"></div>
          <label class="col-sm-2 control-label"> Date</label>
          <div class="col-sm-2">
            <p class="form-control-static"><?= date('Y-m-d');?></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label"> Employee name</label>
          <div class="col-sm-3">
            <select class="form-control" name="emp_id" onchange="select_employee(this)" data-url="<?= base_url('my_payslip/select_employee');?>">
                <option value=""></option>
                <?php foreach($employee_data as $emp):?>
                    <option value="<?= $emp['id'];?>"><?= "{$emp['lastname']}, {$emp['firstname']} {$emp['middleinitial']}"?></option>
                <?php endforeach;?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"> Payroll period</label>
          <div class="col-sm-2">
            <input type="text" class="form-control datepicker" name="start_date"/>
          </div>
          <div class="col-sm-2">
            <input type="text" class="form-control datepicker" name="end_date"/>
          </div>
        </div>
        <hr/>
        <div id="payslip_forms"></div>
        <!-- <div class=> -->
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Save payslip</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>