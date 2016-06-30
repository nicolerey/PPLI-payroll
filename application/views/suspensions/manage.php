<?php $url = base_url('employee_suspensions')?>
<section class="content-header">
  <h1>
    Employee Suspensions
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title?></h3>
    </div>
    <form data-action="<?= base_url('employee_suspensions/'.$action);?>" class="form-horizontal">
      <input type="text" class="hidden id" value="<?= (isset($data))?$data['id']:"";?>" name="id"/>
      <div class="box-body">
        <div class="alert alert-info"><p>Fields marked with <span class="fa fa-asterisk text-danger"></span> are required.</p></div>
        <div class="alert alert-danger<?= (isset($image_error))?'':' hidden';?>">
          <ul class="list-unstyled"></ul>
        </div>
        <?php if(isset($data) && $this->session->userdata('account_type')=='ad' && !$data['status']):?>
          <div class="form-group">
            <div class="col-sm-12">
              <button type="button" data-url="<?= base_url('employee_suspensions/approve');?>" class="btn btn-success btn-flat pull-right approve"><i class="fa fa-check"></i> Resolve report</button>
            </div>
          </div>
        <?php endif;?>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Start date</label>
          <div class="col-sm-2">     
            <input type="text" class="form-control datepicker" name="start_date" value="<?= (isset($data))?date_format(date_create($data['start_date']), 'm/d/Y'):"";?>"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
          </div>
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> End date</label>
          <div class="col-sm-2">     
            <input type="text" class="form-control datepicker" name="end_date" value="<?= (isset($data))?date_format(date_create($data['end_date']), 'm/d/Y'):"";?>"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> 
            Title
          </label>
          <div class="col-sm-3">
            <input type="text" name="title" value="<?= (isset($data))?$data['title']:'';?>" class="form-control"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> 
            Employee
          </label>
          <div class="col-sm-3">
            <select class="form-control" name="employee_id"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>>
              <option value=""></option>
              <?php foreach($employees as $emp_key=>$emp_value):?>
                <option value="<?= $emp_key;?>"<?= (isset($data) && $emp_key==$data['employee_id'])?' selected':'';?>><?= $emp_value;?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> 
            Body
          </label>
          <div class="col-sm-6">
            <textarea name="body" class="form-control" rows="5"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>><?= (isset($data))?$data['body']:'';?></textarea>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat" name="submit" value="submit"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>>Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>