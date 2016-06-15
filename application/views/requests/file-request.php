<?php $url = base_url('requests')?>
<section class="content-header">
  <h1>
    File request
    <small></small>
  </h1>
</section>
<section class="content">
  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title ?></h3>
    </div>
      <form class="form-horizontal" data-action="<?= base_url('requests/store')?>">
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <div class="alert alert-info"><p>Fields marked with <span class="fa fa-asterisk text-danger"></span> are required.</p></div>
            <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
            <div class="form-group">
              <label class="col-sm-3 control-label">Request type</label>
              <div class="col-sm-9">
                <?php $requests = ['' => '',  'matpat' => 'Maternity / Paternity leave', 'sl' => 'Sick leave', 'wml' => 'Menstruation leave', 'vl' => 'Vacation leave', 'o' => 'Others']; ?>
                <?php if($this->session->userdata('gender') === 'M'):?>
                  <?php unset($requests['wml']);?>
                <?php endif;?>
                <?= form_dropdown('type', $requests,  preset($data, 'type', ''), 'class="form-control"');?>
              </div>
            </div>
            <hr>
            <div class="form-group">
              <label class="col-sm-3 control-label"><span class="fa fa-asterisk text-danger"></span> Date</label>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker" name="date_start" value="<?= preset($data, 'datetime_start', '')?>" />
                <span class="help-block">From</span>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control datepicker" name="date_end" value="<?= preset($data, 'datetime_end', '')?>" />
                <span class="help-block">To</span>
              </div>
            </div>
             <div class="form-group">
              <label class="col-sm-3 control-label">&nbsp;</label>
              <div class="col-sm-2">
                <div class="radio"><label><input type="radio" class="halfday" name="halfday" value="am"/> Halfday AM</label></div>
              </div>
              <div class="col-sm-2">
                <div class="radio"><label><input type="radio" class="halfday" name="halfday" value="pm"/> Halfday PM</label></div>
              </div>
              <div class="col-sm-2">
                <div class="radio"><label><input type="radio" class="halfday" name="halfday" value="wd"/> Wholeday</label></div>
              </div>
            </div>
            <hr>
            <div class="form-group">
              <label class="col-sm-3 control-label"><span class="fa fa-asterisk text-danger"></span> Leave title</label>
              <div class="col-sm-9">
                <input type="text" class="form-control disabled" name="custom_type_name" value="<?= preset($data, 'custom_type_name', '')?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label"><span class="fa fa-asterisk text-danger"> </span> Request content</label>
              <div class="col-sm-9">
                <textarea class="form-control" rows="8" name="content"><?= preset($data, 'content', '')?></textarea>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-md-offset-1">
            <table class="table">
              <tbody>
                <tr class="active"><td colspan="2" class="text-center text-bold">REMAINING CREDITS</td></tr>
                <tr><td>Remaining sick leaves</td><td class="text-bold"><?= $sick ?></td></tr>
                <?php if($this->session->userdata('gender') === 'F'):?>
                  <tr><td>Remaining menstrual leaves</td><td class="text-bold"><?= $menstruation ?></td></tr>
                <?php endif;?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?= base_url('home')?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>