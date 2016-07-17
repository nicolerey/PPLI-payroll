<div>
    <div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-striped">
        <thead>
            <tr>
            <th class="col-sm-1"></th>
            <th class="col-sm-3">Particulars</th>
            <th class="col-sm-2">Rate type</th>
            <th class="col-sm-2">Rate</th>
            <th class="col-sm-1">No. of days</th>
            <th class="col-sm-1">No. of hours</th>
            <th class="col-sm-2">Amount</th>
            </tr>
        </thead>
        <tbody class="additional_particulars_container">
            <tr>
                <td></td>
                <td>Basic Rate</td>
                <td>Daily</td>
                <td class="basic_rate">
                    <input name="basic_rate"  min="0" step="0.01" class="form-control pformat particular_rate" onchange="calculate_particular_amount(this, 1);" value="<?= $basic_rate;?>"/>
                </td>
                <td class="particular_days_rendered">
                    <input type="number" class="form-control days_rendered" name="days_rendered" value="0"/>    
                </td>
                <td class="hours_rendered">-</td>
                <td class="particular_amount">
                    0.00
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Overtime</td>
                <td>Daily</td>
                <td>
                    -
                    <input type="text" class="hidden particular_rate" value="<?= $basic_rate * ($overtime_rate/100);?>" name="overtime_rate"/>
                </td>
                <td>-</td>
                <td>
                    <input type="text" onkeypress="return isNumberKey(event)" name="overtime_time"/>
                </td>
                <td class="particular_amount">
                    0.00
                </td>
            </tr>
            <?php foreach($emp_particulars as $emp_part):?>
                <?php if($emp_part['type']=='a'):?>
                    <?php
                        if($emp_part['particular_type']=='d')
                            $add_type = "Daily";
                        else
                            $add_type = "Monthly";
                    ?>
                    <tr>
                        <td></td>
                        <td>
                            <?= $emp_part['name'];?>
                            <input type="hidden" name="particular_id[]" value="<?= $emp_part['id']?>"/>
                        </td>
                        <td><?= $add_type;?></td>
                        <td>
                            <input name="particular_rate[]"  min="0" step="0.01" class="form-control pformat particular_rate" onchange="calculate_particular_amount(this, 1);" value="<?= $emp_part['amount'];?>"/>
                        </td>
                        <td class="particular_days_rendered">
                            <input type="number" class="form-control days_rendered" name="days_rendered" value="0"/>
                        </td>
                        <td>

                        </td>
                        <td class="particular_amount">
                            0.00
                        </td>
                    </tr>
                <?php endif;?>
            <?php endforeach;?>
            <tr class="dynamic_add_particulars hidden particular_group">
                <td>
                    <button type="button" class="btn btn-flat btn-danger" onclick="delete_particular_group(this);">
                    <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </td>
                <td>
                    <select class="form-control additional_name" onchange="change_particular_type(this);">
                    <option value=""></option>
                    <?php if(!empty($particulars)):?>
                        <?php foreach($particulars as $particular):?>
                        <?php if($particular['type']==='a'):?>
                            <option value="<?= $particular['id'];?>" rate_type="<?= $particular['particular_type'];?>"><?= $particular['name'];?></option>
                        <?php endif;?>
                        <?php endforeach;?>
                    <?php endif;?>
                    </select>
                </td>
                <td class="particular_rate_type">-</td>
                <td>
                    <input name=""  min="0" step="0.01" value="0" class="form-control pformat particular_rate" onchange="calculate_particular_amount(this, 1);"/>
                </td>
                <td>
                    <input type="number" class="form-control particular_days_rendered" name="" value="0" onchange="calculate_particular_amount(this, 1);"/>
                </td>
                <td class="particular_amount">
                    0.00
                </td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-2">
        <button type="button" class="btn btn-flat btn-primary" onclick="add_particular_group(this);">
            <span class="glyphicon glyphicon-plus"></span> Add particular
        </button>
        </div>
        <div class="col-sm-7"></div>
        <div class="col-sm-2">
        <table class="table">
            <tbody>
            <tr>
                <td style="text-align: right;">
                <label class="coltrol-label" style="margin-bottom: 0;"> Total:</label>
                </td>
                <td class="total_additional">0.00</td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
    </div>
    <div class="row">
    <div class="row">
        <label class="col-sm-7 control-label text-danger"> Deductions:</label>
    </div>
    <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-5">
        <table class="table table-hover table-striped text-danger">
            <thead>
            <tr>
                <th class="col-sm-1"></th>
                <th class="col-sm-5">Particulars</th>
                <th class="col-sm-2">Rate type</th>
                <th class="col-sm-2">
                <th class="col-sm-3">Amount</th>
            </tr>
            </thead>
            <tbody class="deduction_particulars_container">
            <?php if($payslip['particulars']['deductions']):?>
                <?php foreach($payslip['particulars']['deductions'] as $key=>$deductions):?>
                <?php if($key!=='loan'):?>
                    <?php
                    if($deductions['type']=='d')
                        $ded_type = "Daily";
                    else
                        $ded_type = "Monthly";
                    ?>
                    <tr>
                    <td></td>
                    <td><?= $deductions['name'];?></td>
                    <td><?= $ded_type;?></td>
                    <td>
                        <input  min="0" step="0.01" value="<?= $deductions['amount'];?>" class="form-control pformat deduction_particular_amount" onchange="calculate_total_amount();"<?= ($key!=='loan')?'name="particular_rate[]"':'';?>/>
                        <?php if($key!=='loan'):?>
                        <input type="hidden" name="particular_id[]" value="<?= $deductions['id']?>"/>
                        <?php endif;?>
                    </td>
                    </tr>
                <?php endif;?>
                <?php if($key==='loan'):?>
                    <?php foreach($deductions as $loan):?>
                    <tr>
                        <td></td>
                        <td>Loan Payment - <?= $loan['payment_date'];?></td>
                        <td>-</td>
                        <td class="loan_payment_amount"><?= $loan['payment_amount'];?></td>
                    </tr>
                    <?php endforeach;?>
                <?php endif;?>
                <?php endforeach;?>
            <?php endif;?>
            <tr class="dynamic_ded_particulars hidden particular_group">
                <td>
                <button type="button" class="btn btn-flat btn-danger" onclick="delete_particular_group(this);">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
                </td>
                <td>
                <select class="form-control deduction_name" onchange="change_particular_type(this);">
                    <option value=""></option>
                    <?php if(!empty($particulars)):?>
                    <?php foreach($particulars as $particular):?>
                        <?php if($particular['type']==='d'):?>
                        <option value="<?= $particular['id'];?>" rate_type="<?= $particular['particular_type'];?>"><?= $particular['name'];?></option>
                        <?php endif;?>
                    <?php endforeach;?>
                    <?php endif;?>
                </select>
                </td>
                <td class="particular_rate_type">-</td>
                <td>
                <input name=""  min="0" step="0.01" value="0" class="form-control pformat deduction_particular_amount" onchange="calculate_total_amount();"/>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7"></div>
        <div class="col-sm-2">
        <button type="button" class="btn btn-flat btn-primary" onclick="ded_particular_group();">
            <span class="glyphicon glyphicon-plus"></span> Add particular
        </button>
        </div>
        <div class="col-sm-2">
        <table class="table table-hover table-striped">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: right;"><label class="coltrol-label" style="margin-bottom: 0;"> Net Pay:</label></td><td class="net_pay">2400.00</td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
    </div>
</div>