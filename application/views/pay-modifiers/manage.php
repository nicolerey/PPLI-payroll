<?php $url = base_url('pay_modifiers')?>
<section class="content-header">
  <h1>
    Pay Particulars
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
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Name</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" name="name" value="<?= preset($data, 'name', '')?>" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Particular Type</label>
          <div class="col-sm-5">
            <?= form_dropdown('particular_type', ['' => '', 'd' => 'Daily', 'm' => 'Monthly'], preset($data, 'particular_type', ''), 'class="form-control"')?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span></label>
          <div class="col-sm-9">
            <label class="radio-inline"><input type="radio" name="type" value="a"<?php echo (isset($data['type']))?(($data['type']=='a')?' checked':''):'';?>/> Additionals</label>
            <label class="radio-inline"><input type="radio" name="type" value="d"<?php echo (isset($data['type']))?(($data['type']=='d')?' checked':''):'';?>/> Deductions</label>
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