<?php $url = base_url('requests')?>
<section class="content-header">
  <h1>
    <?= $request_status ?> Requests
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title ?></h3>
    </div>
      <form class="form-horizontal" data-action="<?= base_url("requests/update/{$data['id']}")?>">
      <div class="box-body">
        <div class="form-group">
          <label class="col-sm-2 control-label">Requested by</label>
          <div class="col-sm-5">
            <p class="form-control-static"><?= $data['sender_fullname']?>  (<?= date('m/d/Y h:i:s a', strtotime($data['datetime_filed']))?>)</p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Type</label>
          <div class="col-sm-5">
            <p class="form-control-static"><?= leave_type($data['type']) . ($data['custom_type_name'] ? " ({$data['custom_type_name']})" : '')?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Duration</label>
          <div class="col-sm-5">
            <p class="form-control-static">
              <?= date('m/d/Y', strtotime($data['date_start']))?> - <?= date('m/d/Y', strtotime($data['date_end']))?>
              <?php if($data['halfday'] === 'am'):?>
                (AM only)
              <?php elseif($data['halfday'] === 'pm'):?>
                (PM only)
              <?php endif;?>
            </p>
          </div>
        </div>
        <hr/>
        <div class="form-group">
          <label class="col-sm-2 control-label">Request content</label>
          <div class="col-sm-5">
            <p class="form-control-static"><?= $data['content']?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Status</label>
          <div class="col-sm-5">
            <?php $status = ['a' => 'Approved', 'da' => 'Discarded', 'p' => 'Pending'];?>
             <?php if(role_is('sv')):?>
            <?= form_dropdown('status', $status, $data['status'], 'class="form-control"') ?>
            <?php else:?>
              <p class="form-control-static"><?= $status[$data['status']]?></p>
          <?php endif;?>
          </div>
        </div>
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <?php if(role_is('sv')):?>
          <a href="<?= base_url("requests/view_all?status={$data['status']}")?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
          <button type="submit" class="btn btn-success btn-flat">Save</button>
        <?php endif;?>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>