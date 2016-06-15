<section class="content-header">
  <h1>
    Attendance
    <small></small>
  </h1>
</section>
<section class="content">
  <input data-name="attendance-url" data-value="<?= base_url('attendance/log')?>" disabled>
  <object id="webcard" type="application/x-webcard" width="0" height="0">
    <param name="onload" value="pluginLoaded" />
  </object>
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="box box-primary">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="http://localhost/seaolympus/assets/img/display-photo-placeholder.png" alt="User profile picture">
          <h3 class="profile-username text-center"><span id="firstname"></span> <span id="middlename"></span> <span id="lastname"></span></h3>
          <p class="text-muted text-center" id="position"></p>

          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Department</b> <a class="pull-right" id="department"></a>
            </li>
            <li class="list-group-item">
              <b>Time in</b> <a class="pull-right" id="datetime_in"></a>
            </li>
            <li class="list-group-item">
              <b>Time out</b> <a class="pull-right" id="datetime_out"></a>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>  
</section>
