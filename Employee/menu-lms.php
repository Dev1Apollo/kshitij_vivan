<?php
$MenuLms = array("InquirySourceReport.php", "walkin-report.php", "booked-inquiry.php", "EmployeePerfomance.php");
$MenuTarget = array("AddTarget.php","ViewEmployeeTarget.php","AchieveTarget.php");
?>
<div class="row">
    <nav class="m-navigation">
        <ul class="m-mainmenu">
            <li>
                <a class="
                <?php
                if (basename($_SERVER['REQUEST_URI']) == 'CustomerEntry.php') {
                    echo 'curr';
                }
                ?>" 
                   href="CustomerEntry.php">Manage Customer</a>
            </li>

            <li><a class="
                <?php
                if (basename($_SERVER['REQUEST_URI']) == 'NewInquiry.php') {
                    echo 'curr';
                }
                ?>"
                   href="NewInquiry.php">New Inquiry</a>
            </li>

            <li>
                <a class="
                <?php
                if (basename($_SERVER['REQUEST_URI']) == 'MyFollowup.php') {
                    echo 'curr';
                }
                ?>"
                   href="MyFollowup.php">My Follow Up</a>
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

            <li>
                <a class="
                <?php
                if (basename($_SERVER['REQUEST_URI']) == 'other.php') {
                    echo 'curr';
                }
                ?>"
                   href="other.php">Search Inquiry</a>
            </li>

            <li>
                <a class="
                <?php
                if (basename($_SERVER['REQUEST_URI']) == 'StudentCouncillor.php') {
                    echo 'curr';
                }
                ?>"
                   href="StudentCouncillor.php">Student Councillor</a>
            </li>
            <li>
                <a class="
                <?php
                if (in_array(basename($_SERVER['REQUEST_URI']), $MenuLms)) {
                    echo
                    'curr';
                }
                ?>"
                   href="#">Report<i class="fa fa-chevron-down pull-right"></i></a>
                <ul class="m-submenu">
                    <li>
                        <a class="
                        <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'InquirySourceReport.php') {
                            echo 'curr';
                        }
                        ?>"
                           href="InquirySourceReport.php">Inquiry Source</a>
                    </li>
                    <li>
                        <a class="
                        <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'walkin-report.php') {
                            echo 'curr';
                        }
                        ?>"
                            href="walkin-report.php">Walk-in Report</a>
                    </li>
                    <li>
                        <a class="
                        <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'booked-inquiry.php') {
                            echo 'curr';
                        }
                        ?>"
                           href="booked-inquiry.php">Booked Report</a>
                    </li>
                    <li>
                        <a class="
                        <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'EmployeePerfomance.php') {
                            echo 'curr';
                        }
                        ?>"
                            href="EmployeePerfomance.php">Employee Performance</a>
                    </li>
                </ul>
            </li>
            
            <li>
                <a class="
                <?php
                if (in_array(basename($_SERVER['REQUEST_URI']), $MenuTarget)) {
                    echo
                    'curr';
                }
                ?>"
                   href="#">Target<i class="fa fa-chevron-down pull-right"></i></a>
                <ul class="m-submenu">
                    <li>
                        <a class="
                        <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'AddTarget.php') {
                            echo 'curr';
                        }
                        ?>"
                           href="AddTarget.php">Add Target</a>
                    </li>
                    <li>
                        <a class="
                        <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'ViewEmployeeTarget.php') {
                            echo 'curr';
                        }
                        ?>"
                            href="ViewEmployeeTarget.php">View Target</a>
                    </li>
                    <li>
                        <a class="
                        <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'AchieveTarget.php') {
                            echo 'curr';
                        }
                        ?>"
                           href="AchieveTarget.php">Target Vs Achieve</a>
                    </li>
                    <li>
                        <a class="
                        <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'AchieveReport.php') {
                            echo 'curr';
                        }
                        ?>"
                           href="AchieveReport.php">Achieve Report</a>
                    </li>
                </ul>
            </li>
            
        </ul>
    </nav>
</div>