<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {

    if ($_REQUEST['viewDetails'] == 'TMC'){

        $filterstr = "SELECT * FROM studentfee INNER JOIN studentadmission on studentfee.stud_id = studentadmission.stud_id where MONTH(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and studentfee.amount!=0 ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC";

          
        $countstr = "SELECT count(*) as count FROM studentfee where MONTH(STR_TO_DATE(payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC";
    }
    else if ($_REQUEST['viewDetails'] == 'TMP'){

        $filterstr = "SELECT * FROM studentemidetail where  YEAR(STR_TO_DATE(emiDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and MONTH(STR_TO_DATE(emiDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE())";

      
        $countstr = "SELECT count(*) as count FROM studentemidetail where  YEAR(STR_TO_DATE(emiDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and MONTH(STR_TO_DATE(emiDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE())";
    }
    
    
   $resrowcount = mysqli_query($dbconn,$countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['count'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;


    $filterstr = $filterstr . " LIMIT $startpage, $per_page";



    $resultfilter = mysqli_query($dbconn,$filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        
        $i = 0;
           $serial = 0;
        $serial = ($page * $per_page);
        ?>  
       <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    
                    <th class="desktop">Customer Name</th>
                    <th class="desktop">Employee Name</th>
                    <th class="desktop">Date</th>
                    <?php if ($_REQUEST['viewDetails'] == 'TMC'){ ?>
                    <th class="desktop">Gross Amount</th>
                    <th class="desktop">CGST 9%</th>
                    <th class="desktop">CGST 9%</th>
                    <?php } ?>
                    <th class="desktop">Amount</th>
                    <?php if ($_REQUEST['viewDetails'] == 'TMP'){ ?>
                    <th class="desktop">Actual Amount</th>
                    <?php } ?>
                    <th class="desktop">Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                  $walkin = array(0, 0, 0, 0, 0);
                  
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                   $get_inquirysource=  mysqli_fetch_array(mysqli_query($dbconn,"select * from studentadmission where stud_id = '".$rowfilter['stud_id']."'"));
                  if ($_REQUEST['viewDetails'] == 'TMC'){
                    $walkin[0]+=$rowfilter['texFreeAmt'];
                    $walkin[1]+=$rowfilter['decGst'] / 2;
                    $walkin[2]+=$rowfilter['decGst'] / 2;
                    $walkin[3]+=$rowfilter['amount'];
                   }else
                   {
                       $walkin[0]+=$rowfilter['emiAmount'];
                       $walkin[1]+=$rowfilter['actualReceivedAmount'];
                   }
                   
                    
                    $i++;
                    $serial++;
                    ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input count"> <?php echo $serial; ?>
                            </div>
                        </td>
                        
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                
                                echo $get_inquirysource['title'] . ' ' . $get_inquirysource['firstName'] . ' ' . $get_inquirysource['middleName'] . ' ' . $get_inquirysource['surName'];
                                ?> 
                            </div>
                        </td>  
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $employeemaster = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1' and employeeMasterId='" . $get_inquirysource['employeeMasterId'] . "'"));
                                echo $employeemaster['employeeName'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                if ($_REQUEST['viewDetails'] == 'TMC'){
                                    echo $rowfilter['payDate'];
                                }
                                else
                                {
                                     echo $rowfilter['emiDate'];
                                }

                                ?> 
                            </div>
                            <?php if ($_REQUEST['viewDetails'] == 'TMC'){ ?>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo  $rowfilter['texFreeAmt'];
                              ?> 
                            </div>
                        </td>
                         <td>
                            <div class="form-group form-md-line-input "><?php
                                echo  $rowfilter['decGst']/2;
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                 echo  $rowfilter['decGst']/2;
                                ?> 
                            </div>
                        </td>
                            <?php } ?>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                   if ($_REQUEST['viewDetails'] == 'TMC'){ 
                                    echo $rowfilter['amount'];
                                   }
                                    else {
                                        echo $rowfilter['emiAmount'];
                                    }
                                ?> 
                            </div>
                        </td>
                        <?php
                        if ($_REQUEST['viewDetails'] == 'TMP'){ ?>
                        <td>
                            <div class="form-group form-md-line-input "><?php 
                                
                                    if($rowfilter['actualReceivedAmount'] != 0 ){
                                        echo $rowfilter['actualReceivedAmount']; 
                                    }else{
                                            echo 0;
                                        
                                    }
                                    ?>
                            </div>
                        </td>
                <?php } ?>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['comments']; ?> 
                            </div>
                        </td>
                     </tr>
                   <?php
                    }
                    ?>
            </tbody>
            <tr style="background-color:#f3f3f3">
                <td colspan="1"><b>Total</b></td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <?php if ($_REQUEST['viewDetails'] == 'TMP'){ ?>
                <td colspan="1"><?php echo $walkin[0]; ?></td>
                <td colspan="1"><?php echo $walkin[1]; ?></td>
                <td colspan="1">--</td>
                <?php } ?>
                <?php if ($_REQUEST['viewDetails'] == 'TMC'){ ?>
                <td colspan="1"><?php echo $walkin[0]; ?></td>
                <td colspan="1"><?php echo $walkin[1]; ?></td>                            
                <td colspan="1"><?php echo $walkin[2]; ?></td> 
                <td colspan="1"><?php echo $walkin[3]; ?></td>
                <td colspan="1">--</td>
                <?php } ?>
                
            </tr>
        </table>
    <?php } ?>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $('#tableC').DataTable({
                    
                });
            });
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