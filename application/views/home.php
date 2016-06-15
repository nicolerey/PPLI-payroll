<section class="content-header">
  <h1>
    Dashboard
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li class="active"><a><i class="fa fa-dashboard"></i> Dashboard</a></li>
  </ol>
</section>
<section class="content">

  <div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-primary">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="<?= base_url('assets/img/display-photo-placeholder.png')?>" alt="User profile picture">
          <h3 class="profile-username text-center"><?= "{$employee['firstname']} {$employee['middleinitial']} {$employee['lastname']}"?></h3>
          <p class="text-muted text-center"><?= $employee['position']?></p>

          <ul class="list-group list-group-unbordered">
          	<li class="list-group-item">
              <b>Employee No.</b> <a class="pull-right"><?= $employee['id']?></a>
            </li>
            <li class="list-group-item">
              <b>Date Hired</b> <a class="pull-right"><?= date_format(date_create($employee['date_hired']), 'd-M-Y')?></a>
            </li>

          </ul>
          <a href="<?= base_url('logout')?>" class="btn btn-primary btn-flat btn-block"><b>Logout</b></a>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

      <!-- About Me Box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">About Me</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <strong>SSS No.</strong>
          <p class="text-muted">
            <?= $employee['sss_number']?>
          </p>
          <strong>PAG-IBIG No.</strong>
          <p class="text-muted"><?= $employee['pagibig_number']?></p>
          <strong>Email Address</strong>
          <p class="text-muted"><?= $employee['email_address']?></p>
          <strong>Mobile No.</strong>
          <p class="text-muted"><?= $employee['mobile_number']?></p>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#basic" data-toggle="tab">Basic Information</a></li>
          <li><a href="#account" data-toggle="tab">Account</a></li>
        </ul>
        <div class="tab-content">
          <div class="active tab-pane" id="basic">
            <form class="form-horizontal">
            	<div class="form-group">
            		<label class="control-label col-sm-3">Name</label>
            		<div class="col-sm-9">
            			<p class="form-control-static"><?= "{$employee['firstname']} {$employee['middleinitial']} {$employee['lastname']}"?></p>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Gender</label>
            		<div class="col-sm-9">
            			<p class="form-control-static"><?= $employee['gender'] === 'M' ? 'Male' : 'Female'?></p>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Civil Status</label>
            		<div class="col-sm-9">
            			<p class="form-control-static"><?= civil_status($employee['civil_status'])?></p>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Birthdate</label>
            		<div class="col-sm-9">
            			<p class="form-control-static"><?= date_format(date_create($employee['birthdate']), 'd-M-Y')?></p>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Nationality</label>
            		<div class="col-sm-9">
            			<p class="form-control-static"><?= $employee['nationality']?></p>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Religion</label>
            		<div class="col-sm-9">
            			<p class="form-control-static"><?= $employee['religion']?></p>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Address</label>
            		<div class="col-sm-9">
            			<p class="form-control-static"><?= $employee['full_address']?></p>
            		</div>
            	</div>
            </form>
          </div><!-- /.tab-pane -->
          <div class="tab-pane" id="account">
            <form class="form-horizontal" id="change-pw" data-action="<?=base_url('home/save_password')?>">
              <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Account type</label>
            		<div class="col-sm-9">
            			<p class="form-control-static"><?= account_type($account['type'])?></p>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Password</label>
            		<div class="col-sm-8">
            			<input type="password" name="password" class="form-control">
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">Confirm</label>
            		<div class="col-sm-8">
            			<input type="password" name="confirm_password" class="form-control">
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="control-label col-sm-3">&nbsp;</label>
            		<div class="col-sm-8">
            			<button type="submit" class="btn btn-success btn-flat">Save</button>
            		</div>
            	</div>
            	
            </form>
           
          </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
      </div><!-- /.nav-tabs-custom -->
    </div><!-- /.col -->
  </div><!-- /.row -->

</section><!-- /.content -->