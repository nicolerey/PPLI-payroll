<section class="content-header">
  <h1>
    Attendance
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
      <div class="box-body">
        <div class="form-group">
          <form class="form-inline" method="GET" action="<?= current_url()?>">
            <?php if(isset($search_employee)):?>
               <div class="form-group">
                  <label>Employee number</label>
                  <select class="search_employee form-control" name="employee_number">
                    <option value=""></option>
                    <?php foreach($employee as $emp):?>
                      <option value="<?= $emp['id'];?>"><?= "{$emp['lastname']}, {$emp['firstname']} {$emp['middleinitial']}."?></option>
                    <?php endforeach;?>
                  </select>
                </div>
            <?php endif;?>
            <div class="form-group">
              <label for="start-date">Start date</label>
              <input type="text" class="form-control datepicker" id="start-date" name="start_date" value="<?= $this->input->get('start_date')?>">
            </div>
            <div class="form-group">
              <label for="end-date">End date</label>
              <input type="text" class="form-control datepicker" id="end-date" name="end_date" value="<?= $this->input->get('end_date')?>">
            </div>
            <button type="submit" class="btn btn-default btn-flat">Go!</button>
          </form>
        </div>

        <div class="row form-group">
          <?= form_open_multipart('attendance/upload_attendance', 'class="form-inline"');?>
          <div class="col-sm-1">
            <button type="submit" class="btn btn-primary btn-flat"><span class="glyphicon glyphicon-upload"></span> Upload</button>
          </div>
          <div class="col-sm-3" style="padding-top: 5px;">
            <input type="file" name="userfile"/>
          </div>
          </form>
          <div class="col-sm-2 pull-right">
            <button class="btn btn-success pull-right save_attendance" disabled="true" data-toggle="modal" data-target="#myModal"><i class="fa fa-floppy-o "></i> Save</button>
          </div>
        </div>

        <hr>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Authorize changes</h4>
              </div>
              <div class="modal-body form-horizontal">
                <div class="form-group">
                  <label class="col-sm-5 control-label">Admin password</label>
                  <div class="col-sm-6">
                    <input class="form-control" type="password" id="password" required="true" />
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" authorize-url="<?= base_url('attendance/authorize_changes');?>" save-url="<?= base_url('attendance/save_datetime');?>" id="save">Save changes</button>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button class="close" aria-label="Close" data-dismiss="modal" type="button">
                  <span aria-hidden="true">x</span>
                </button>
                <h4 id="mySmallModalLabel" class="modal-title">Authorize changes</h4>
              </div>
              <div class="modal-body form-horizontal">
                <div class="form-group">
                  <label class="col-sm-5 control-label">Admin password</label>
                  <div class="col-sm-6">
                    <input class="form-control" type="text" id="password"/>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-4 pull-right">
                    <button class="btn btn-success pull-right" aria-label="OK" data-dismiss="modal" type="button" id="login">OK</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <table class="table table-bordered table-condensed table-striped">
          <thead><tr class="active"><th>Employee Name</th><th>Time in</th><th>Time out</th><th></th></tr></thead>
          <tbody>
            <?php if(empty($data)):?>
              <tr><td class="text-center" colspan="5">Nothing to display</td></tr>
            <?php else:?>
              <?php foreach($data as $key=>$attendance):?>
                <tr>
                  <td>
                    <?= $attendance['name'];?>
                  </td>
                  <td>
                    <a href="#" data-type="combodate" data-title="Select time in" class="editable_time time_in" data-name="datetime_in" attendance-id="<?= $attendance['emp_attendance_id'];?>" table-row="<?= $key;?>">
                      <?= ($attendance['datetime_in'])?$attendance['datetime_in']:"-"; ?>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-type="combodate" data-title="Select time out" class="editable_time time_out" data-name="datetime_out" attendance-id="<?= $attendance['emp_attendance_id'];?>" table-row="<?= $key;?>">
                      <?= ($attendance['datetime_out'])?$attendance['datetime_out']:"-"; ?>
                    </a>
                  </td>
                  <td class="time_diff"><?= $attendance['total_hours'];?> hrs</td>
                </tr>
              <?php endforeach;?>
            <?php endif;?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>