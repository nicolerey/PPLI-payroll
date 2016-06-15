<?php $url = base_url('departments')?>
<section class="content-header">
  <h1>
    Departments
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
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Department #</label>
          <div class="col-sm-3">
          <?php if($mode === MODE_CREATE):?>
            <input type="text" class="form-control" name="id_number" value="<?= preset($data, 'id_number', '')?>" />
           <?php else:?>
               <p class="form-control-static"><?= $data['id_number']?></p>
            <?php endif;?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Department name</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" name="name" value="<?= preset($data, 'name', '')?>" />
          </div>
        </div>
        <?php if($mode === MODE_EDIT):?>
          <hr>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Dept. supervisor</label>
              <div class="col-sm-5">
                <?= form_dropdown('employee_id', ['' => ''] + $employees_list, preset($data, 'employee_id', FALSE), 'class="form-control"');?>
              </div>
            </div>
        <?php endif;?>
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>