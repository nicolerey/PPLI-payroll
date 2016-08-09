<?php $url = base_url(); ?>
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= base_url('assets/img/display-photo-placeholder.png') ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= user_full_name() ?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">MAIN MENU</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="<?= $active_nav ===  NAV_DASHBOARD ? 'active' : '' ?>">
                <a href="<?= "{$url}home" ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>

            <li class="treeview <?= $active_nav === NAV_DATA_ENTRY ? 'active' : '' ?>">
              <a href="#">
                <i class="fa fa-pencil"></i>
                <span>Data Entry</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?= $active_subnav === SUBNAV_EMPLOYEES ? 'active' : '' ?>">
                    <a href="<?= "{$url}employees" ?>"><i class="fa fa-circle-o"></i> Employees</a>
                </li>
                <li class="<?= $active_subnav === SUBNAV_DEPARTMENTS ? 'active' : '' ?>">
                    <a href="<?= "{$url}departments" ?>"><i class="fa fa-circle-o"></i> Departments</a>
                </li>
                <li class="<?= $active_subnav === SUBNAV_POSITIONS ? 'active' : '' ?>">
                    <a href="<?= "{$url}positions" ?>"><i class="fa fa-circle-o"></i> Positions</a>
                </li>
              </ul>
            </li>
            
            <?php if($this->session->userdata('account_type')!=='pm'):?>
                <li class="<?= $active_nav === NAV_PAY_MODIFIERS? 'active' : '' ?>">
                    <a href="<?= "{$url}pay_modifiers" ?>"><i class="fa fa-cubes"></i> <span>Pay Particulars</span></a>
                </li>
            <?php endif;?>

            <li class="treeview <?= $active_nav === NAV_MY_PAYSLIP ? 'active' : '' ?>">
              <a href="#">
                <i class="fa fa-sticky-note"></i>
                <span> Payslips</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?= $active_subnav === SUBNAV_LISTING ? 'active' : '' ?>">
                    <a href="<?= "{$url}my_payslip" ?>"><i class="fa fa-circle-o"></i> Listing</a>
                </li>
                <li class="<?= $active_subnav === SUBNAV_MANUAL ? 'active' : '' ?>">
                    <a href='<?= "{$url}my_payslip/create_manual_payslip"; ?>'><i class="fa fa-circle-o"></i> Create manual payslip</a>
                </li>
                <li class="<?= $active_subnav === SUBNAV_GENERATE ? 'active' : '' ?>">
                    <a href="<?= "{$url}payslip" ?>"><i class="fa fa-circle-o"></i> Generate payslip</a>
                </li>
              </ul>
            </li>

            <li class="<?= $active_nav === NAV_VIEW_ATTENDANCE? 'active' : '' ?>">
                <a href="<?= "{$url}attendance/view" ?>"><i class="fa fa-calendar"></i> <span> Attendance</span></a>
            </li>

            <li class="<?= $active_nav === NAV_LOANS ? 'active' : '' ?>">
                <a href="<?= "{$url}loan" ?>"><i class="fa fa-usd"></i> <span> Loans</span></a>
            </li>

            <li class="<?= $active_nav === NAV_REPORTS ? 'active' : '' ?>">
                <a href="<?= "{$url}employee_reports" ?>"><i class="fa fa-file-text-o"></i> <span> Employee Reports</span></a>
            </li>

            <li class="<?= $active_nav === NAV_SUSPENSIONS ? 'active' : '' ?>">
                <a href="<?= "{$url}employee_suspensions" ?>"><i class="fa fa-calendar-times-o"></i> <span> Employee Suspensions</span></a>
            </li>

            <li class="<?= $active_nav === NAV_BONUSES ? 'active' : '' ?>">
                <a href="<?= "{$url}bonus" ?>"><i class="fa fa-plus-circle"></i> <span> Bonuses</span></a>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>