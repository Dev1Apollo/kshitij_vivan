<?php
$lms = array("CustomerEntry.php", "AddCustomerEntry.php", "sms.php", "NewInquiry.php", "MyFollowup.php", "TransferInquiry.php", "other.php", "Student-Councillor.php", "InquirySourceReport.php", "walkin-report.php", "booked-inquiry.php", "EmployeePerfomance.php", "StudentCouncillor.php");
$admission = array("Registration.php", "AddNewAdmission.php", "OnAccount.php", "AjaxStudentSoftware.php", "AjaxStudentRegistrarionFee.php", "AjaxStudentOnAccountFee.php", "AjaxStudentGSTReport.php", "AjaxStudentEntry.php", "AjaxStudentEnrollmentReport.php", "AjaxStudentFee.php", "Enrollment.php", "AddRegisterFee.php", "AjaxEmi.php", "AjaxStudentCourse.php", "AjaxRegistration.php", "AjaxRegistrationStudent.php", "AjaxProjectionReport.php", "AjaxOnAccount.php", "AjaxGetStudentFee.php", "AjaxFeeCollectionReport.php", "ajaxCollectionReport.php", "AjaxEnrollment.php", "AddOnAccountFee.php", "StudentEnrollmentFees.php", "StudentRegistration.php", "studentGstReport.php", "ProjectionReport.php", "CollectionReport.php", "StudentEnrollmentReport.php", "FeeCollectionReport.php", "StudentFeePDF.php", "AddNewRegister.php", "EditRegisterStudent.php", "EditStudent.php", "StudentEntry.php", "student-course.php", "student-course-details.php", "EditStudentSoftware.php", "student-fees.php", "student-emi.php", "EditStudentCourse.php", "EditStudentfee.php", "Registration.php", "OnAccount.php", "Enrollment.php", "studentGstReport.php", "ProjectionReport.php", "CollectionReport.php", "StudentEnrollmentReport.php", "FeeCollectionReport.php");
$JobSubEntry = array("CompanyMaster.php", "JobMaster.php", "RequiredStudentList.php", "NotRequiredStudentList.php", 'PlacedStudentList.php', 'ActiveJob.php', 'addstudent.php', 'studentInterview.php', 'StudentPlacementLedger.php', 'StudentPlacementHistory.php');
?>
<div class="page-header">
    <div class="page-header-top">
        <div class="container">
            <div class="page-logo">
                <a href="<?php echo $web_url; ?>Supervisor/index.php">
                    <img src="../../images/logo.png" width="120px" alt="logo" class="logo-default">
                </a>
            </div>
            <a href="javascript:;" class="menu-toggler"></a>
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">

                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-user fa-2x"></i>
                            <span class="username username-hide-mobile"><?php echo $_SESSION['SuperEmployeeName']; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?php echo $web_url; ?>Supervisor/ChangePassword.php">
                                    <i class="icon-lock"></i>Change Password
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo $web_url; ?>Supervisor/Logout.php">
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

                    <?php if (isset($_SESSION['SuperEmployeeId']) && isset($_SESSION['SuperEmployeeName'])) { ?>

                        <li class="<?php
                                    if (basename($_SERVER['REQUEST_URI']) == 'index.php') {
                                        echo 'active';
                                    }
                                    ?>">
                            <a href="index.php">Dashboard</a>
                        </li>


                        <li class="menu-dropdown classic-menu-dropdown  <?php
                                                                        if (in_array(basename($_SERVER['REQUEST_URI']), $lms)) {
                                                                            echo
                                                                            'active';
                                                                        }
                                                                        ?>">
                            <a href="<?php echo $web_url; ?>Supervisor/CustomerEntry.php">LMS</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                                                                        if (in_array(basename($_SERVER['REQUEST_URI']), $admission)) {
                                                                            echo 'active';
                                                                        }
                                                                        ?>">
                            <a href="<?php echo $web_url; ?>Supervisor/StudentRegistration.php">Student Admission</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown <?php if (in_array(parse_url(basename(($_SERVER['REQUEST_URI'])), PHP_URL_PATH), $JobSubEntry)) {
                                                                            echo 'active';
                                                                        } ?>">
                            <a href="<?php echo $web_url; ?>Supervisor/CompanyMaster.php">Placement</a>
                        </li>
                    <?php
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>