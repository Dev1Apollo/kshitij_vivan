<?php
$MenuReports = array("studentGstReport.php","FreshCollection.php", "ProjectionReport.php","CashDepositReport.php", "CollectionReport.php", "StudentEnrollmentReport.php", "FeeCollectionReport.php","CourseRoport.php");
$MenuFees = array("Registration.php", "OnAccount.php", "Enrollment.php", "AddRegisterFee.php","AddOnAccountFee.php","StudentEnrollmentFees.php");
?>
<div class="row">
    <nav class="m-navigation">
        <ul class="m-mainmenu">
            <li><a class="<?php
                if (basename($_SERVER['REQUEST_URI']) == 'StudentRegistration.php') {
                    echo 'curr';
                }
                ?>"  href="StudentRegistration.php">Student Registration</a>
            </li>
            <li><a class="<?php
                if (basename($_SERVER['REQUEST_URI']) == 'StudentEntry.php') {
                    echo 'curr';
                }
                ?>"  href="StudentEntry.php">Enrollment</a></li>
            <li><a class="<?php
                if (in_array(basename($_SERVER['REQUEST_URI']), $MenuFees)) {
                    echo
                    'curr';
                }
                ?>" href="#">Fee Collection<i class="fa fa-chevron-down pull-right"></i></a>
                <ul class="m-submenu">
                    <li>
                        <a a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'Registration.php') {
                            echo 'curr';
                        }
                        ?>"  href="Registration.php">
                            Registration
                        </a>
                    </li>
                    <li>
                        <a a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'Enrollment.php') {
                            echo 'curr';
                        }
                        ?>"  href="Enrollment.php">
                            Fee
                        </a>
                    </li>
                    <li>
                        <a a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'OnAccount.php') {
                            echo 'curr';
                        }
                        ?>"  href="OnAccount.php">
                            Other
                        </a>
                    </li>
                    
                </ul>
            </li>
            <li><a class="<?php
                if (basename($_SERVER['REQUEST_URI']) == 'StudentLedger.php') {
                    echo 'curr';
                }
                ?>"  href="StudentLedger.php">Student Ledger</a></li>
            <li>
            <li><a class="<?php
                if (basename($_SERVER['REQUEST_URI']) == 'BankDeposit.php') {
                    echo 'curr';
                }
                ?>"  href="BankDeposit.php">Cash Deposit</a></li>
            <li>
            <li><a class="<?php
                if (in_array(basename($_SERVER['REQUEST_URI']), $MenuReports)) {
                    echo
                    'curr';
                }
                ?>" href="#">Report<i class="fa fa-chevron-down pull-right"></i></a>
                <ul class="m-submenu">
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'StudentEnrollmentReport.php') {
                            echo 'curr';
                        }
                        ?>"  href="StudentEnrollmentReport.php">
                            Enrollment Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'CollectionReport.php') {
                            echo 'curr';
                        }
                        ?>"  href="CollectionReport.php">
                            Collection Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'ProjectionReport.php') {
                            echo 'curr';
                        }
                        ?>" href="ProjectionReport.php">
                            Projection Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'CashDepositReport.php') {
                            echo 'curr';
                        }
                        ?>" href="CashDepositReport.php">
                            Deposit Report
                        </a>
                    </li>
<!--                    <li>
                        <a class="<?php
//                        if (basename($_SERVER['REQUEST_URI']) == 'BusinessAnalysisReport.php') {
//                            echo 'curr';
//                        }
                        ?>" href="BusinessAnalysisReport.php">
                            Business Analysis Report
                        </a>
                    </li>-->
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'studentGstReport.php') {
                            echo 'curr';
                        }
                        ?>"  href="studentGstReport.php">
                            GST Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'FeeCollectionReport.php') {
                            echo 'curr';
                        }
                        ?>"  href="FeeCollectionReport.php">
                            Fee Collection Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'FreshCollection.php') {
                            echo 'curr';
                        }
                        ?>"  href="FreshCollection.php">
                            Fresh Collection Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'BirthdayRoport.php') {
                            echo 'curr';
                        }
                        ?>"  href="BirthdayRoport.php">
                            Birthday Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'CourseRoport.php') {
                            echo 'curr';
                        }
                        ?>"  href="CourseRoport.php">
                            Course Report
                        </a>
                    </li>
                </ul>
            </li>
            <li><a class="<?php
                if (basename($_SERVER['REQUEST_URI']) == 'StudentActiveStatus.php') {
                    echo 'curr';
                }
                ?>"  href="StudentActiveStatus.php">Student Status</a></li>
            <li>
        </ul>
    </nav>
</div>
