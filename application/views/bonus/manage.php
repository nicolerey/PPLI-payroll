<?php $url = base_url('bonus')?>
<section class="content-header">
  <h1>
    Bonus
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title?></h3>
    </div>
    <form data-action="<?= base_url('bonus/'.$action);?>" class="form-horizontal">
      <input type="text" class="hidden id" value="<?= (isset($data))?$data['id']:"";?>" name="id"/>
      <div class="box-body">
        <div class="alert alert-info"><p>Fields marked with <span class="fa fa-asterisk text-danger"></span> are required.</p></div>
        <div class="alert alert-danger hidden">
          <ul class="list-unstyled"></ul>
        </div>
        <?php if(isset($data) && $this->session->userdata('account_type')=='ad' && !$data['status']):?>
          <div class="form-group">
            <div class="col-sm-12">
              <button type="button" data-url="<?= base_url('bonus/approve');?>" class="btn btn-success btn-flat pull-right approve"><i class="fa fa-check"></i> Approve bonus</button>
            </div>
          </div>
        <?php endif;?>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Date</label>
          <div class="col-sm-2">     
            <input type="text" class="form-control datepicker" name="date" value="<?= (isset($data))?date_format(date_create($data['date']), 'm/d/Y'):"";?>"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> 
            Particular
          </label>
          <div class="col-sm-3">
            <select class="form-control" name="pay_modifier_id"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>>
              <option value=""></option>
              <?php foreach($pay_modifier as $particular):?>
                <option value="<?= $particular['id'];?>"<?= (isset($data) && $particular['id']==$data['pay_modifier_id'])?'selected':'';?>><?= $particular['name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Type</label>
          <div class="col-sm-9">
            <label class="radio-inline">
              <input type="radio" name="type" value="dep"<?= (isset($data) && $data['type']=='dep')?' checked':'';?> onclick="show_field(this);" onchange="detect_change()"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
              Department
            </label>
            <label class="radio-inline">
              <input type="radio" name="type" value="emp"<?= (isset($data) && $data['type']=='emp')?' checked':'';?> onclick="show_field(this);" onchange="detect_change()"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
              Employee
            </label>
          </div>
        </div>

        <!-- choices -->
        <div class="form-group dep_field"<?= (isset($data) && $data['type']!='dep')?' style="display: none;"':'';?>>
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> 
            Department
          </label>
          <div class="col-sm-3">
            <select class="form-control" name="department_id"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?> onchange="detect_change()">
              <option value=""></option>
              <option value="all"<?= (isset($data) && isset($data['department']) && count($data['department'])>1)?' selected':'';?>>All departments</option>
              <?php foreach($departments as $value):?>
                <option value="<?= $value['id'];?>"<?= (isset($data) && isset($data['department']) && count($data['department'])==1 && $value['id']==$data['department'][0]['department_id'])?' selected':'';?>><?= $value['name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
        <div class="form-group emp_field"<?= (isset($data) && $data['type']!='emp')?' style="display: none;"':'';?>>
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> 
            Employee
          </label>
          <div class="col-sm-3">
            <select class="form-control" name="employee_id"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?> onchange="detect_change()">
              <option value=""></option>
              <?php foreach($employees as $emp_key=>$emp_value):?>
                <option value="<?= $emp_key;?>"<?= (isset($data) && isset($data['employee']) && $emp_key==$data['employee']['employee_id'])?' selected':'';?>><?= $emp_value;?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>        
        <!-- choices -->

        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> 
            Multiplier
          </label>
          <div class="col-sm-1">
            <input name="multiplier" class="form-control" type="number"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?> value="<?= (isset($data))?$data['multiplier']:'';?>"/>
          </div>
        </div>
        <input type="text" name="change" class="change hidden" value="0"/>
        <!-- /.box-body -->
      </div>
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat" name="submit" value="submit"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>>Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>