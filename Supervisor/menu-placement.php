<?php
$MenuMaster = array("CompanyMaster.php","JobMaster.php");
$StudentList = array("RequiredStudentList.php","NotRequiredStudentList.php",'PlacedStudentList.php');
$ActiveJob = array('ActiveJob.php','addstudent.php');
$StudentLedgerList = array('StudentPlacementLedger.php','StudentPlacementHistory.php');
$MenuReports = array('CompanyReport.php','StudentPlacedReport.php');
//echo basename(($_SERVER['REQUEST_URI']));
//echo parse_url(basename(($_SERVER['REQUEST_URI'])), PHP_URL_PATH); exit;
?>
<div class="row">
    <nav class="m-navigation">
        <ul class="m-mainmenu">
			<li>
                <a class="
                <?php
                if (basename($_SERVER['REQUEST_URI']) == 'CompanyMaster.php') {
                    echo 'curr';
                }
                ?>" 
                   href="CompanyMaster.php">New Compnay</a>
            </li>
			<li>
                <a class="
                <?php
                if (basename($_SERVER['REQUEST_URI']) == 'JobMaster.php') {
                    echo 'curr';
                }
                ?>" 
                   href="JobMaster.php">New Job</a>
            </li>
            <li>
                <a class="
                <?php
                if (in_array(parse_url(basename(($_SERVER['REQUEST_URI'])), PHP_URL_PATH), $ActiveJob)) {
                    echo 'curr';
                }
                ?>" 
                   href="ActiveJob.php">Active Job</a>
            </li>
			<li>
                <a class="
                <?php
                if (basename($_SERVER['REQUEST_URI']) == 'studentInterview.php') {
                    echo 'curr';
                }
                ?>" 
                   href="studentInterview.php">Interview Status</a>
            </li>
            <li><a class="
                <?php
                if (in_array(basename($_SERVER['REQUEST_URI']), $StudentList)) {
                    echo 'curr';
                }
                ?>"
                   href="RequiredStudentList.php">Student Placement Status</a>
            </li>
			<li><a class="
                <?php
                if (in_array(parse_url(basename(($_SERVER['REQUEST_URI'])), PHP_URL_PATH), $StudentLedgerList)) {
                    echo 'curr';
                }
                ?>"
                   href="StudentPlacementLedger.php">Student Placement Ledger</a>
            </li>
            <li><a class="<?php
                if (in_array(basename($_SERVER['REQUEST_URI']), $MenuReports)) {
                    echo
                    'curr';
                }
                ?>" href="#">Report<i class="fa fa-chevron-down pull-right"></i></a>
                <ul class="m-submenu">
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'CompanyReport.php') {
                            echo 'curr';
                        }
                        ?>"  href="CompanyReport.php">
                            Company Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'StudentPlacedReport.php') {
                            echo 'curr';
                        }
                        ?>"  href="StudentPlacedReport.php">
                            Student Placed Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'JobReport.php') {
                            echo 'curr';
                        }
                        ?>"  href="JobReport.php">
                            Job Placement Report
                        </a>
                    </li>
                    <li>
                        <a class="<?php
                        if (basename($_SERVER['REQUEST_URI']) == 'companyoffer.php') {
                            echo 'curr';
                        }
                        ?>"  href="companyoffer.php">
                            Company Offer Report
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
