 <?php
$MasterEntry = array("CompanyMaster.php","JobMaster.php","InquirySource.php", "Status.php", "Expense.php", "CategoryOfInquiry.php", "State.php", "City.php","SupportEmployeeMaster.php");
$Report = array("walkin-report.php", "FreshCollectionReport.php", "FeeCollectionReport.php", "studentGstReport.php", "CashDepositReport.php", "ProjectionReport.php", "StudentEnrollmentReport.php", "CollectionReport.php", "EmployeePerfomance.php", "InquirySourceReport.php", "booked-inquiry.php", "InquiryLeadReport.php", "InquiryFollowupReport.php", "InquiryBookedReport.php");
?>
<div class="page-header">
    <div class="page-header-top">
        <div class="container">
            <div class="page-logo">
                <a href="<?php echo $web_url; ?>admin/index.php">
                    <img src="../images/logo.png" width="120px" alt="logo" class="logo-default">
                </a>
            </div>
            <a href="javascript:;" class="menu-toggler"></a>
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-user fa-2x"></i>
                            <span class="username username-hide-mobile"><?php echo $_SESSION['AdminName']; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?php echo $web_url; ?>admin/ChangePassword.php">
                                    <i class="icon-lock"></i>Change Password 
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $web_url; ?>admin/Logout.php">
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
                    <?php
                    if (isset($_SESSION['AdminName'])) {
                        if ($_SESSION['AdminType'] == 1) {
                            ?>
                            <li class="<?php
                            if (basename($_SERVER['REQUEST_URI']) == 'index.php') {
                                echo 'active';
                            }
                            ?>">
                                <a href="index.php">Dashboard</a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown <?php
                            if (in_array(basename($_SERVER['REQUEST_URI']), $MasterEntry)) {
                                echo
                                'active';
                            }
                            ?>">
                                <a href="#">Master Entry</a>
                                <ul class="dropdown-menu pull-left">
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/EmployeeMaster.php" class="nav-link">
                                            Employee Master
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/InquirySource.php" class="nav-link">
                                            Inquiry Source
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/Status.php" class="nav-link">
                                            Status
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/Expense.php" class="nav-link">
                                            Expense
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/CategoryOfInquiry.php" class="nav-link">
                                            Category Of Inquiry
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/State.php" class="nav-link">
                                            State
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/City.php" class="nav-link">
                                            City
                                        </a>
                                    </li>
                                    <li>
                                        <a href="Inquiryfor.php" class="nav-link">
                                            Inquiry For
                                        </a>
                                    </li>
                                    <li>
                                        <a href="Course.php" class="nav-link">
                                            Course
                                        </a>
                                    </li>
                                    <li>
                                        <a href="softwareCourse.php" class="nav-link">
                                            Software
                                        </a>
                                    </li>
                                    <li>
                                        <a href="StudentStatus.php" class="nav-link">
                                            Student Status
                                        </a>
                                    </li>
                                    <li>
                                        <a href="PaymentMaster.php" class="nav-link">
                                            Payment Master
                                        </a> 
                                    </li>
                                    <li>
                                        <a href="PayFor.php" class="nav-link">
                                            Pay For
                                        </a>
                                    </li>
                                    <li>
                                        <a href="BankMaster.php" class="nav-link">
                                            Bank Master
                                        </a>
                                    </li>
                                    <li>
                                        <a href="Bank.php" class="nav-link">
                                            Deposit To Bank
                                        </a>
                                    </li>
                                    <li>
                                        <a href="CompanyMaster.php" class="nav-link">
                                            Company Master
                                        </a>
                                    </li>
									<li>
                                        <a href="JobMaster.php" class="nav-link">
                                            Job Master
                                        </a>
                                    </li>
                                    <li>
                                        <a href="SupportEmployeeMaster.php" class="nav-link">
                                            Support Employee Master
                                        </a>
                                    </li>
                                    
                                    
                                </ul>
                            </li>
                            <li>
                                <a class="
                                <?php
                                if (basename($_SERVER['REQUEST_URI']) == 'sms.php') {
                                    echo 'curr';
                                }
                                ?>"
                                   href="sms.php">Send SMS </a>
                            </li>
                            <li class="  <?php
                            if (basename($_SERVER['REQUEST_URI']) == 'CustomerEntry.php') {
                                echo 'active';
                            }
                            ?>">
                                <a href="<?php echo $web_url; ?>admin/CustomerEntry.php">Customer Entry</a>
                            </li>
                            <li class="  <?php
                            if (basename($_SERVER['REQUEST_URI']) == 'Target.php') {
                                echo 'active';
                            }
                            ?>">
                                <a href="<?php echo $web_url; ?>admin/Target.php">Target</a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown <?php
                            if (in_array(basename($_SERVER['REQUEST_URI']), $Report)) {
                                echo
                                'active';
                            }
                            ?>">
                                <a href="#">Report</a>
                                <ul class="dropdown-menu pull-left">
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/InquiryLeadReport.php" class="nav-link">
                                            Inquiry Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="walkin-report.php" class="nav-link">
                                            Walk-In Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="booked-inquiry.php" class="nav-link">
                                            Booked Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/EmployeePerfomance.php" class="nav-link">
                                            Employee Perfomance
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>admin/InquirySourceReport.php" class="nav-link">
                                            Inquiry Source
                                        </a>
                                    </li>  
                                    <li>
                                        <a href="StudentEnrollmentReport.php" class="nav-link">
                                            Enrollment Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="CollectionReport.php" class="nav-link">
                                            Collection Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="ProjectionReport.php" class="nav-link">
                                            Projection Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="CashDepositReport.php" class="nav-link">
                                            Deposit Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="studentGstReport.php" class="nav-link">
                                            Student GST Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="FeeCollectionReport.php" class="nav-link">
                                            Fee Collection Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="FreshCollectionReport.php" class="nav-link">
                                            Fresh Collection Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="BirthdayRoport.php" class="nav-link">
                                            Birthday Report
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="
                                <?php
                                if (basename($_SERVER['REQUEST_URI']) == 'StudentList.php') {
                                    echo 'active';
                                }
                                ?>">
                                <a href="<?php echo $web_url; ?>admin/StudentList.php">Student Details </a>
                            </li>
                            <li class="
                                <?php
                                if (basename($_SERVER['REQUEST_URI']) == 'ImportLead.php') {
                                    echo 'active';
                                }
                                ?>">
                                <a href="<?php echo $web_url; ?>admin/ImportLead.php">Import Lead</a>
                            </li>
                            <li class="
                                <?php
                                if (basename($_SERVER['REQUEST_URI']) == 'StudentLedger.php') {
                                    echo 'active';
                                }
                                ?>">
                                <a href="<?php echo $web_url; ?>admin/StudentLedger.php">Student Ledger</a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>