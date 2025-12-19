<?php
ob_start();
error_reporting(E_ALL);
include_once 'common.php';
$connect = new connect();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html> 
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
        <title><?php echo $website_name; ?></title> 
    </head> 
    <body style="background-color: #f5f5f5;"> 
        <br><br><br><br><br><br><br> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"> 
            <tr> 
                <td align="center"> 
                    <img src="images/logo.png" style="margin-bottom: 25px; width: 200px" border="0"> 
                    <h1 style="margin:10px 0px; padding:0; font-family: trebuchet ms; color: #666">Welcome to <span style="color: #51c6dd">Kshitij</span> <span style=" color: #f4cc21">Vivan</span></h1> 
                    <h3 style="margin-top:50px ;"><a href="Employee/login.php"  style=" font-family: trebuchet ms; background-color: #51c6dd; padding:  10px; border-radius: 5px;  color: #fff; text-decoration: none">Click here to Login</a></h3>
                </td> 
            </tr> 
        </table> 
    </body> 
</html> 
