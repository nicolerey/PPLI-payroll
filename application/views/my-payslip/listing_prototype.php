<?php if(empty($items)):?>
  <tr><td class="text-center" colspan="7">Nothing to display</td></tr>
<?php endif;?>
<?php foreach($items AS $row):?>
    <tr>
<?php if($this->session->userdata('account_type')=='ad'):?>
  <td><input type="checkbox" name="checkbox[]" value="<?= $row['id'];?>"/></td>
<?php endif;?>
<td><a href="<?= base_url("my_payslip/view/{$row['id']}")?>"><?= str_pad($row['id'], 4, 0, STR_PAD_LEFT)?></a></td>
<td><?= $row['batch_id'];?></td>
<td><?= "{$row['firstname']} {$row['middleinitial']} {$row['lastname']}";?></td>
<td><?= format_date($row['start_date'], 'd-M-Y')?></td>
<td><?= format_date($row['end_date'], 'd-M-Y')?></td>
<td>
  <span class="label label-<?= ($row['approval_status'])?'success':'warning';?>">
    <?= ($row['approval_status'])?'Approved':'Pending';?>
  </span>
</td>
    </tr>
<?php endforeach;?>