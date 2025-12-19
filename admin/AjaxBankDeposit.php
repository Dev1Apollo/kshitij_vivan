<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    $where = "and studentadmission.branchId ='" . $_SESSION['branchid'] . "' ";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where.=" and STR_TO_DATE(payDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where.=" and STR_TO_DATE(payDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
//    SELECT studentfee.*,studentadmission.* FROM `studentfee`,studentadmission where studentadmission.stud_id = studentfee.stud_id and studentadmission.employeeMasterId = '1' and deposit = 'No' order by studentfeeid desc
    $filterstr = "SELECT studentfee.*,studentadmission.* FROM studentfee,studentadmission where studentadmission.stud_id = studentfee.stud_id " . $where . " and studentfee.amount!=0 and deposit = 'No' and studentfee.paymentMode in (1,2) and studentadmission.branchId=" . $_SESSION['branchid'] . " order by STR_TO_DATE(payDate,'%d-%m-%Y') asc";
    $countstr = "SELECT count(*) as TotalRow FROM `studentfee` ,studentadmission  where studentadmission.stud_id = studentfee.stud_id " . $where . "  and deposit = 'No' ";

    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;

    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
    ?>  
    <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>  
    <!DOCTYPE html>
    <html lang="en">
        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
            <input type="hidden" value="DepositDetail" name="action" id="action">
            <div class="row m-search-box">
                <div class="row"  >
                    <div class="form-group  col-md-3">
                        <label>Deposit Date*</label>
                        <input type="text" value="" name="depositDate" class="form-control" id="depositDate" placeholder="Enter Deposit Date " required="" />
                    </div>
                    <div class="form-group col-md-3" id="Divdeposit">
                        <label>Deposit Amount*</label>
                        <input type="text" value="" name="depositAmount" class="form-control" id="depositAmount" readonly="" placeholder="Deposit Amount" required=""/>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Bank Name*</label>
                        <!--<input type="text" value="" name="toBank" class="form-control" required="" id="toBank" placeholder="Bank Name" />-->
                        <select name="toBank" id="toBank" class="form-control">
                            <option value="">Select Bank</option>
                            <?php
                            $bank = mysqli_query($dbconn, "Select * from bank where isDelete='0' and iStatus='1' ");
                            while ($rowBank = mysqli_fetch_array($bank)) {
                                ?>
                                <option value="<?php echo $rowBank['bankId'] ?>"> <?php echo $rowBank['bankName']; ?></option>
                            <?php }
                            ?>
                        </select> 
                    </div>
                    <div class="form-actions noborder">
                        <input class="btn blue margin-top-20" type="submit" id="Btnmybtn" onclick="updatefeeDetail();"  value="Submit" name="submit">      
                        <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                    </div>
                </div>
                <hr/>
                <?php
                $resultfilter = mysqli_query($dbconn, $filterstr);
                if (mysqli_num_rows($resultfilter) > 0) {
                    $i = 0;
                    $serial = 0;
                    $serial = ($page * $per_page);
                    ?>
                                                                        <!--                <input type="hidden" name="studentfeeid[]" id="studentfeeid" value="<?php echo $rowfilter['studentfeeid']; ?>">-->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                            <thead class="tbg">
                                <tr>
                                    <th class="desktop">Receipt No.</th>
                                    <th class="desktop">Receipt Date</th>
                                    <th class="desktop">Student Name</th>
                                    <th class="desktop">Received Amount</th>
                                    <td class="desktop">Payment Mode</td>
                                    <th class="desktop">Deposit Mode</th>
                                    <th class="desktop">Deposit Amount</th>
                                    <th class="desktop">Comments</th>
                                </tr>
                            </thead>
                            <?php
                            while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                                $i++;
                                $serial++;
                                ?>
                                <input type="hidden" name="amount" id="amount" value="<?php echo $rowfilter['amount']; ?>"> 
                                <input type="hidden" name="studentfeeid[]" id="studentfeeid" value="<?php echo $rowfilter['studentfeeid']; ?>">
                                <tbody>
                                    <tr>
                                        <td><?php echo $rowfilter['receiptNo']; ?></td>
                                        <td><?php echo $rowfilter['payDate']; ?></td>
                                        <td><?php echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName']; ?></td>
                                        <td><?php echo $rowfilter['amount']; ?></td>
                                        <td>
                                            <?php
                                            $filterMode = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `paymentmode` where paymentId='" . $rowfilter['paymentMode'] . "' and isDelete='0' and iStatus='1'"));
                                            echo $filterMode['paymentName'];
                                            ?>
                                        </td>
                                        <td>
                                            <select class="form-control" id="depositMode" name="depositMode[]" onchange="getAmount('<?php echo $rowfilter['amount']; ?>', '<?php echo $rowfilter['paymentMode']; ?>', 'depAmount', '<?php echo $i ?>');">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                                <?php if ($rowfilter['paymentMode'] == '1') { ?>
                                                    <option id ="divErr" value="Partial">Partial</option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" onkeyup="CalculateDepoisteAmount();" class="form-control" id="depAmount"  name="depAmount[]" placeholder="Enter Amount" /> 
                                        </td>
                                        <td>
                                            <input type="text" name="comment[]" id="comment" class="form-control" placeholder="Emter COmments" /> 
                                        </td>
                                    </tr>
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </form>
            <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <?php } else {
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
    }
    ?>
    <script>
                                                $(document).ready(function () {
                                                    $("#depositDate").datepicker({
                                                        format: 'dd-mm-yyyy',
                                                        autoclose: true,
                                                        todayHighlight: true,
                                                        defaultDate: "now",
                                                        startDate: "-3d",
                                                        endDate: 'now'
                                                    });
                                                });

                                                function CalculateDepoisteAmount()
                                                {
                                                    var TotalAmount = 0;
                                                    TotalAmount = TotalAmount * 1;
                                                    $('input[name="depAmount[]"]').each(function () {
                                                        if ($(this).val() != '')
                                                        {
                                                            TotalAmount = TotalAmount * 1 + $(this).val() * 1;
                                                        }
                                                    });
                                                    $('#depositAmount').val(TotalAmount);
                                                }

                                                function getAmount(amount, paymentMode, depAmount, i) {
                                                    var KCounter = 0;
                                                    var jCounter = 0;

                                                    $('select[name="depositMode[]"]').each(function () {
                                                        jCounter++;
                                                        if (i == jCounter)
                                                        {
                                                            var amountDeposited = $(this).val();
                                                            if (amountDeposited == 'No')
                                                            {
                                                                KCounter = 0;
                                                                $('input[name="depAmount[]"]').each(function () {
                                                                    KCounter++;
                                                                    if (i == KCounter)
                                                                    {
                                                                        $(this).val('0');
                                                                        $(this).prop("readonly", true);
                                                                    }
                                                                    //alert($(this).val());
                                                                });
                                                            } else
                                                            {
                                                                KCounter = 0;
                                                                $('input[name="depAmount[]"]').each(function () {
                                                                    KCounter++;
                                                                    if (i == KCounter)
                                                                    {
                                                                        $(this).val(amount);
                                                                        if (amountDeposited == 'Yes')
                                                                        {
                                                                            $(this).prop("readonly", true);
                                                                        } else
                                                                        {
                                                                            $(this).prop("readonly", false);
                                                                        }
                                                                    }
                                                                    //alert($(this).val());
                                                                });
                                                            }
                                                        }
                                                    });
                                                    CalculateDepoisteAmount();
                                                }

                                                $(document).ready(function () {
                                                    $("#depAmount").keydown(function (e) {
                                                        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                            return;
                                                        }
                                                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                            e.preventDefault();
                                                        }
                                                    });
                                                });

                                                $('#frmparameter').submit(function (e) {
                                                    e.preventDefault();
                                                    var $form = $(this);
                                                    $('#loading').css("display", "block");
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'querydata.php',
                                                        data: $('#frmparameter').serialize(),
                                                        success: function (response) {
                                                            if (response == 1)
                                                            {
                                                                $('#loading').css("display", "none");
                                                                $("#Btnmybtn").attr('disabled', 'disabled');
                                                                alert('Add Sucessfully.');
                                                                window.location.href = '';
                                                            } else
                                                            {
                                                                $('#loading').css("display", "none");
                                                                $("#Btnmybtn").attr('disabled', 'disabled');
                                                                alert('Not Add Please Try Again.');
                                                                window.location.href = '';
                                                            }
                                                        }
                                                    });
                                                });

    </script>    


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