<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');
?>
<select name="cid" id="cid"  class="form-control"  onchange="getcourse();">
    <option value="">Select Course Name</option>
<?php
$fetchcourse = mysqli_query($dbconn,"Select *  from studentcourse where stud_id =" . $stud_id ." and studentcourse.istatus=1");
while ($data = mysqli_fetch_array($fetchcourse)) {
    $rowdata = mysqli_query($dbconn,"SELECT * FROM `course` where istatus=1 and courseId='" . $data['courseId'] . "' and isDelete=0 ORDER by courseName ASC");
    while ($resultdata = mysqli_fetch_array($rowdata)) {
        ?>
            <option value="<?php echo $resultdata['courseId'] ?>"><?php echo $resultdata['courseName'] ?></option>
            <?php
        }
    }
    ?>
</select>