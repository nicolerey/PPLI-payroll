<section class="content-header">
  <h1>
    <?= $request_status ?> Requests
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-body no-padding">
      <table class="table table-hover table-striped">
      	<thead>
			<tr><th>Request #</th><th>Sender</th><th>Request title</th><th>Filed on</th><th>Status</th></tr>      		
      	</thead>
      	<tbody>
          <?php $url = isset($edit) ? base_url('requests/view') : base_url('requests/view')?>
      		<?php foreach($items AS $row):?>
      			<tr>
              <td><a href="<?= "{$url}/{$row['id']}"?>"><?= $row['id']?></a></td>
      				<td><?= $row['sender_fullname']?></td>
      				<td><?= leave_type($row['type'])?></td>
      				<td><?= date('m/d/Y h:i:s a', strtotime($row['datetime_filed']))?></td>
              <td>
                <?php if($row['status'] == 'p'):?>
                  <span class="label label-warning">Pending</span>
                <?php elseif($row['status'] == 'a'):?>
                  <span class="label label-success">Approved</span>
                <?php else:?>
                  <span class="label label-danger">Discarded</span>
                <?php endif;?>
              </td>
      			</tr>
      		<?php endforeach;?>
      	</tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>