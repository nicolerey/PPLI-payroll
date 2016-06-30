<?php $url = base_url('employee_reports')?>
<section class="content-header">
  <h1>
    Employee Report
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title?></h3>
    </div>
    <?= form_open_multipart("employee_reports/{$action}", 'class="form-horizontal"');?>
      <input type="text" class="hidden" value="<?= (isset($data))?$data['id']:"";?>" name="id"/>
      <div class="box-body">
        <div class="alert alert-info"><p>Fields marked with <span class="fa fa-asterisk text-danger"></span> are required.</p></div>
        <div class="alert alert-danger<?= (isset($image_error))?'':' hidden';?>">
          <ul class="list-unstyled">
            <?php if(isset($image_error)):?>
              <li><?= $image_error;?></li>
            <?php endif;?>
            <?php if(isset($edit_error)):?>
              <li>Account is not authorized to edit.</li>
            <?php endif;?>
          </ul>
        </div>
        <?php if(isset($data) && $this->session->userdata('account_type')=='ad' && !$data['status']):?>
          <div class="form-group">
            <div class="col-sm-12">
              <button type="submit" class="btn btn-success btn-flat pull-right" name="resolve" value="resolve"><i class="fa fa-check"></i> Resolve report</button>
            </div>
          </div>
        <?php endif;?>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Date</label>
          <div class="col-sm-2">     
            <input type="text" class="form-control datepicker" name="date" value="<?= (isset($data))?date_format(date_create($data['date']), 'm/d/Y'):"";?>" required<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> 
            Title
          </label>
          <div class="col-sm-3">
            <input type="text" name="title" value="<?= (isset($data))?$data['title']:'';?>" class="form-control" required<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
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
            <textarea name="body" class="form-control" rows="5" required<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>><?= (isset($data))?$data['body']:'';?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"> 
            Resolution
          </label>
          <div class="col-sm-9">
            <?php if(isset($data) && isset($data['image']) && (!$data['status'] || ($data['status'] && $this->session->userdata('account_type')=='ad'))):?>
              <div class="col-sm-2">
                <button type="submit" class="btn btn-danger" name="remove" value="remove">Remove image</button>
              </div>
            <?php endif;?>
            <div class="col-sm-4">
              <input type="file" name="image" id="image"<?= (isset($data) && $data['status'] && $this->session->userdata('account_type')=='pm')?' disabled':'';?>/>
            </div>
          </div>
        </div>
        <?php if(isset($data['image'])):?>
          <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
              <div>
                <a href="#" data-toggle="modal" data-target=".bs-example-modal-lg">
                  <?= image($data['image'], '600px', '100%');?>
                </a>
              </div>
            </div>
          </div>
        <?php endif;?>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
                <h4 id="myLargeModalLabel" class="modal-title">Resolution</h4>
              </div>
              <div class="modal-body">
                <?= image($data['image'], '100%', '100%');?>
              </div>
            </div>
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