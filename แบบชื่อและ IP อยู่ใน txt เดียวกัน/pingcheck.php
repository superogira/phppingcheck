<html>
 <head>
  <meta http-equiv="refresh" content="60; url="<?php echo $_SERVER['PHP_SELF']; ?>">
 </head>

<script type="text/javascript">
        function zoom() {
           document.body.style.zoom = "85%" 
        }
</script>

<body onload="zoom()">

<?php
$list = file('./iplist.txt', FILE_IGNORE_NEW_LINES);


$ipname=array();
$iplist=array();
$count=1;
foreach($list as $val)
{
    if($count%2==1)
    {
        $ipname[]=$val;
    }
    else
    {
        $iplist[]=$val;
    }
    $count++;
}

$iplist = file('./iplist.txt', FILE_IGNORE_NEW_LINES);
$ipname = file('./ipname.txt', FILE_IGNORE_NEW_LINES);

date_default_timezone_set("Asia/Bangkok");
function DateThai($strDate)
{
	$strYear = date("Y",strtotime($strDate))+543;
	$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
	$strHour= date("H",strtotime($strDate));
	$strMinute= date("i",strtotime($strDate));
	$strSeconds= date("s",strtotime($strDate));
	$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
	$strMonthThai=$strMonthCut[$strMonth];
	return "$strDay $strMonthThai $strYear เวลา $strHour:$strMinute:$strSeconds";
}

$datetime = date("Y-n-j H:i:s");
$strDate = $datetime;
echo '
<table width="100%"  border="0">
	<tr>';
		echo '<td height="40" bgcolor="#80ccff" style="text-align:center"><strong style="font-size: 25px;"><b>สถานภาพเครือข่ายในภาพรวม เมื่อวันที่ '.DateThai($strDate).'</b></strong></td>
	</tr>
</table>
';
?>

<?php
$num = 0;
$i = 0;
$j = 0;
$k = 0;
$l = 0;
$onlinegroup = array();
$unreachgroup = array();
$offlinegroup = array();

foreach($iplist as $ip ) {
    $i++;
	$name = $ipname[$num];
	$list = $name." ///// ".$ip;
	$exe= shell_exec("ping -n 1 -w 500 $ip");
	
	if(strrpos($exe, "(0% loss)") > 0){
		if(strrpos($exe, "unreachable") > 0){
			$repeatexe= shell_exec("ping -n 1 -w 500 $ip");
			if(strrpos($repeatexe, "unreachable") > 0){
				array_push($unreachgroup, $list);
			} else {
				array_push($onlinegroup, $name);
			}
		} else {
			array_push($onlinegroup, $name);
		}
	}
	
	if(strrpos($exe, "(100% loss)") > 0){
		$repeatexe= shell_exec("ping -n 1 -w 500 $ip");
		if(strrpos($repeatexe, "(100% loss)") > 0){
			array_push($offlinegroup, $list);
		} else {
			array_push($onlinegroup, $name);
		}
	}
	$num = $num+1;
}

echo '    <table width="100%"  border="0">
            <tr>';
			
foreach($offlinegroup as $offline ) {
    $k++;
	
	echo '<td bgcolor="#ff8080"><b>'.$offline." - Offline".'</b</td>';

    if($k == 4) {
        echo '</tr><tr>';
        $k = 0;
    }
}

echo '    </tr>
        </table>';		
echo '    <table width="100%"  border="0">
            <tr>';
			
foreach($unreachgroup as $unreach ) {
    $l++;
	
	echo '<td bgcolor="#b3b3b3"><b>'.$unreach." - unreachable".'</b</td>';

    if($l == 4) {
        echo '</tr><tr>';
        $l = 0;
    }
}

echo '    </tr>
        </table>';
echo '    <table width="100%"  border="0">
            <tr>';
			
foreach($onlinegroup as $online ) {
    $j++;
	
	echo '<td bgcolor="#66ff66">'.$online.'</td>';

    if($j == 5) {
        echo '</tr><tr>';
        $j = 0;
    }
}

echo '    </tr>
        </table>';		
?>
 </body>
</html>
