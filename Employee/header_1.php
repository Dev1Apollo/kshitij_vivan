<?php
$MasterEntry = array("InquirySource.php", "Status.php", "Expense.php", "CategoryOfInquiry.php", "State.php", "City.php");
$Report = array("InquiryLeadReport.php", "InquiryFollowupReport.php", "InquiryBookedReport.php", "walkin-report.php", "booked-inquiry.php");
?>
<div class="page-header">
    <div class="page-header-top">
        <div class="container">
            <div class="page-logo">
                <a href="<?php echo $web_url; ?>Employee/index.php">
                    <img src="../images/logo.png" width="120px" alt="logo" class="logo-default">
                </a>
            </div>
            <a href="javascript:;" class="menu-toggler"></a>
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">

                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-user fa-2x"></i>
                            <span class="username username-hide-mobile"><?php echo $_SESSION['EmployeeName']; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?php echo $web_url; ?>Employee/ChangePassword.php">
                                    <i class="icon-lock"></i>Change Password 
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo $web_url; ?>Employee/Logout.php">
                                    <i class="icon-key"></i>Log Out 
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="page-header-menu">
        <div class="container">
            <div class="hor-menu">
                <ul class="nav navbar-nav">

                    <?php if (isset($_SESSION['EmployeeId']) && isset($_SESSION['EmployeeName'])) { ?> 

                        <li class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'index.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="index.php">Dashboard</a>
                        </li>

                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'CustomerEntry.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/CustomerEntry.php">Manage Customer</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'NewInquiry.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/NewInquiry.php">New Inquiry</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'MyFollowup.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/MyFollowup.php">My Follow Up</a>
                        </li>

                        <!--<li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'TransferInquiry.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/TransferInquiry.php">Transfer Inquiry</a>
                        </li>-->
                        
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'other.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="other.php">Search Inquiry</a>
                        </li>

                        <li class="menu-dropdown classic-menu-dropdown <?php
                        if (in_array(basename($_SERVER['REQUEST_URI']), $Report)) {
                            echo
                            'active';
                        }
                        ?>">
                            <a href="#">Report</a>
                            <ul class="dropdown-menu pull-left">
                                <!--                                    <li>
                                                                        <a href="InquiryLeadReport.php" class="nav-link">
                                                                            Inquiry Report
                                                                        </a>
                                                                    </li>-->

                                <li>
                                    <a href="InquirySourceReport.php" class="nav-link">
                                        Inquiry Source
                                    </a>
                                </li>
                                <li>
                                    <a href="walkin-report.php" class="nav-link">
                                        Walk-in Report
                                    </a>
                                </li>
                                <li>
                                    <a href="booked-inquiry.php" class="nav-link">
                                        Booked Report
                                    </a>
                                </li>
                                <li>
                                    <a href="EmployeePerfomance.php" class="nav-link">
                                        Employee Performance
                                    </a>
                                </li>
                                <li>
                                    <a href="studentGstReport.php" class="nav-link">
                                        Student GST Report
                                    </a>
                                </li>
                                <li>
                                    <a href="ProjectionReport.php" class="nav-link">
                                        Student Projection Report
                                    </a>
                                </li>
                                <li>
                                    <a href="CollectionReport.php" class="nav-link">
                                        Student Collection Report
                                    </a>
                                </li>
                                <li>
                                    <a href="StudentEnrollmentReport.php" class="nav-link">
                                        Student Enrollment Report
                                    </a>
                                </li>
                                <li>
                                    <a href="FeeCollectionReport.php" class="nav-link">
                                        Fee Collection Report
                                    </a>
                                </li>
                            </ul>
                        </li>  
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'StudentEntry.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/StudentEntry.php">Student Admission</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                            if (basename($_SERVER['REQUEST_URI']) == 'StudentCouncillor.php') {
                                echo 'active';
                            }
                            ?>">
                            <a href="<?php echo $web_url; ?>Employee/StudentCouncillor.php">Student Councillor</a>
                        </li>
                        <?php
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>