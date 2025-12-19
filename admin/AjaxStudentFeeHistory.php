<?php
ob_start();
error_reporting(0);
require_once('../common.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    $where = " where 1=1";
    if ($_REQUEST['stud_id'] != NULL && isset($_REQUEST['stud_id']))
        $where.=" and  studentfee.stud_id =" . $_REQUEST[stud_id];
    if ($_REQUEST['studentcourseId'] != NULL && isset($_REQUEST['studentcourseId']))
        $where.=" and  studentcourse.studentcourseId = " . $_REQUEST[studentcourseId] . "";

    $filterstr = "SELECT * FROM `studentfee` join studentcourse on studentcourse.studentcourseId=studentfee.studentcourseId join course on course.courseId=studentcourse.courseId  " . $where . " and studentcourse.istatus=1 order by studentfeeid asc";
    $countstr = "SELECT count(*) as TotalRow FROM `studentfee` join studentcourse on studentcourse.studentcourseId=studentfee.studentcourseId join course on course.courseId=studentcourse.courseId " . $where . " and studentcourse.istatus=1 order by STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') asc";

    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;

    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
    $resultfilter = mysqli_query($dbconn, $filterstr);

    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $serial = 0;
        $serial = ($page * $per_page);
        ?>
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>.
        <div class="table-responsive">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Receipt No</th>
                        <th class="desktop">Pay Date</th>
                        <th class="desktop">Payment Mode</th>
                        <th class="desktop">Bank Name</th>
                        <th class="desktop">Cheque No</th>
                        <th class="desktop">Pay For</th>
                        <th class="desktop">Amount</th>
<!--                        <th class="none">Reason</th>
                        <th class="none">Type</th>-->
                        <th class="desktop">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                        $serial++;
                        ?>
                        <tr>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $serial; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['receiptNo']; ?> 
                                </div>
                            </td>   
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['payDate']; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    $filterMode = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `paymentmode` where paymentId='" . $rowfilter['paymentMode'] . "' and isDelete='0' and iStatus='1'"));
                                    if ($rowfilter['feetype'] == 5){
                                        echo "Discount";
                                    }else{
                                        echo $filterMode['paymentName'];
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input ">
                                    <?php
                                    if (isset($rowfilter['bankName']) && $rowfilter['bankName'] != '') {
                                        $filterBank = mysqli_fetch_array(mysqli_query($dbconn, "SELECT bankName FROM `bankmaster` where isDelete='0' and istatus='1' and bankMasterId='" . $rowfilter['bankName'] . "' "));
                                        echo $filterBank['bankName'];
                                    } else {
                                        echo "NA";
                                    }
                                    ?>                                    
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "> <?php
                                    if (isset($rowfilter['chequeNo']) && $rowfilter['chequeNo'] != '') {
                                        echo $rowfilter['chequeNo'];
                                    } else {
                                        echo 'NA';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                if ($rowfilter['isCancel'] != 1) {
                                    if (isset($rowfilter['payFor'])) {
                                        $filterFeePayFor = mysqli_fetch_array(mysqli_query($dbconn, "SELECT FeePayForName FROM `feepayfor` where isDelete='0' and istatus='1' and FeePayForId='" . $rowfilter['payFor'] . "' "));
                                        echo $filterFeePayFor['FeePayForName'];
                                    } else {
                                        if($rowfilter['feetype'] == 2){
                                            echo "Fee";
                                        }else if ($rowfilter['feetype'] == 5){
                                           echo "Discount"; 
                                        }
                                    }
                                }else{
                                    echo "Cancel Receipt";
                                }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['amount']; ?>
                                </div>
                            </td>
<!--                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['CancellationComment']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
//                                    if ($rowfilter['rejectiontype'] == 1) {
//                                        echo "Cheque bounce";
//                                    } else if ($rowfilter['rejectiontype'] == 2) {
//                                        echo "Wrong Entry";
//                                    } else if ($rowfilter['rejectiontype'] == 3) {
//                                        echo "Other";
//                                    }
                                    ?>
                                </div>
                            </td>-->
                            <td>
                                <div class="form-group form-md-line-input">
                                    <?php if ($rowfilter['isCancel'] != 1) { 
                                        if ($rowfilter['feetype'] != 5){
                                        ?>                                        
                                        <a  class="btn blue" href="<?php echo $web_url; ?>Employee/StudentFeePDF.php?token=<?php echo $rowfilter['studentfeeid']; ?>"  target="_blank" title="View Student Fee PDF"><i class="fa fa-eye"></i></a>
                                        <?php } ?>
                                        <a  class="btn blue" href="<?php echo $web_url; ?>admin/EditStudentfee.php?token=<?php echo $rowfilter['studentfeeid']; ?>" title="EDIT STUDENT"><i class="fa fa-edit"></i></a>
                                        <a type="button" class="btn blue" data-toggle="modal" data-target="#exampleModal_<?php echo $rowfilter['studentfeeid']; ?>" data-whatever="@mdo"><i class="fa fa-trash-o iconshowFirst"></i></a>
                                        <!--<a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php // echo $rowfilter['studentfeeid'];        ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>-->
                                        <?php } ?>
                                </div>
                            </td>
                        </tr>

                    <div class="modal fade" id="exampleModal_<?php echo $rowfilter['studentfeeid']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Free Cancellation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">X</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" role="form" enctype="multipart/form-data" name="frmparameter" id="frmparameter">

                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Cancellation Type:</label>
                                            <select id="rejectiontype_<?php echo $rowfilter['studentfeeid']; ?>" name="rejectiontype" class="form-control">
                                                <option value="">Select Cancellation Reason</option>
                                                <option value="1">Cheque bounce</option>
                                                <option value="2">Wrong Entry</option>
                                                <option value="3">Other</option>
                                            </select>
                                            <!--<input type="text" class="form-control" id="recipient-name">-->
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="col-form-label">Cancellation Comment:</label>
                                            <textarea class="form-control" id="CancellationComment_<?php echo $rowfilter['studentfeeid']; ?>" name="CancellationComment"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="javascript:return deleteFormData('Delete', '<?php echo $rowfilter['studentfeeid']; ?>');">Cancellation Fee</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
                ?>
                </tbody>
            </table>
        </div>





    <?php } ?>
    <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
    <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
    <script>
                                        $(document).ready(function () {
                                            $('#tableC').DataTable({
                                            });
                                        });

                                        function deleteFormData(task, id) {
                                            var rejectiontype = $('#rejectiontype_' + id).val();
                                            var CancellationComment = $('#CancellationComment_' + id).val();
                                            deletedata(task, id, CancellationComment, rejectiontype);
                                        }
    </script>
    <?php
} else {
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
            <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                <h1 class="font-white text-center"> No Data Found ! </h1>
            </div>   
        </div>
    </div>
    <?php
}

if ($_REQUEST['action'] == 'Delete') {
    $studentfeeid = $_REQUEST['ID'];
    $filterstud = "select * from studentfee where studentfeeid=" . $studentfeeid;
    $filterstuddetail = mysqli_fetch_array(mysqli_query($dbconn, $filterstud));
    
    $updateFee = mysqli_query($dbconn, "update studentfee SET isCancel=1 where studentfeeid=" . $studentfeeid);
    $fee = $filterstuddetail['amount'] * (-1);
    $depositAmount = $filterstuddetail['depositAmount'] * (-1);
    $decGst = $filterstuddetail['decGst'] * (-1);
    $texFreeAmt = $filterstuddetail['texFreeAmt'] * (-1);
    $data = array(
        "stud_id" => $filterstuddetail['stud_id'],
        "receiptNo" => $filterstuddetail['receiptNo'],
        "recepitCount" => 0,
        "studentcourseId" => $filterstuddetail['studentcourseId'],
        "feetype" => $filterstuddetail['feetype'],
        "amount" => $fee,
        "payDate" => date('d-m-Y'),
        "paymentMode" => $filterstuddetail['paymentMode'],
        "bankName" => $filterstuddetail['bankName'],
        "chequeNo" => $filterstuddetail['chequeNo'],
        "toBank" => $filterstuddetail['toBank'],
        "deposit" => $filterstuddetail['deposit'],
        "depositAmount" => $depositAmount,
        "comments" => $filterstuddetail['comments'],
        "depositDate" => $filterstuddetail['depositDate'],
        "decGst" => $decGst,
        "texFreeAmt" => $texFreeAmt,
        "strIP" => $_SERVER['REMOTE_ADDR'],
        "payFor" => $filterstuddetail['payFor'],
        "remarks" => $filterstuddetail['remarks'],
        "isCancel" => 1,
        "rejectiontype" => $_REQUEST['type'],
        "CancellationComment" => $_REQUEST['comment']
    );
    $deal_res = $connect->insertrecord($dbconn, "studentfee", $data);
}
?>
<?php if ($totalrecord > $per_page) { ?>
    <div class="row">
        <div class="col-lg-12 m-pager">
            <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark" style="text-align: center;">
                <div class="form-actions noborder">
                    <?php
                    echo '<ul>';
                    if ($totalrecord > $per_page) {
                        echo paginate($reload = '', $show_page, $total_pages);
                    }
                    echo "</ul>";
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>