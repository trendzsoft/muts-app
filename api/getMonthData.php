<?php
require 'includes/connectdb.php';
$conn = connect();
 $st=str_replace("T"," ",$_GET["st"]);
 $et=str_replace("T"," ",$_GET["et"]);
 $ip=$_GET["ip"];
 $opid=$_GET["opid"];
$jobid=$_GET["jobid"];
$opidCondtion="";
$jobidCondtion="";
$midCondtion="";
if(isset($opid))  $opidCondtion=	" and machinelog.opid =$opid";
if(isset($jobid)) $jobidCondtion=	" and machinelog.jobno =$jobid";
if(isset($ip)) $midCondtion=	" and machinelog.ioport =$ip";
$sql=<<<EOT
select 
	 CONCAT(MONTH(t.cycledate),'.', MONTHNAME(t.cycledate)) as MonthName, count(*) as  MonthTotal, t.machinename as MachineName, t.opname as opname,t.jobname as jobname
from (select 
			  machine.name as machinename,
			  operator.opname as opname,
			  job.jobname as jobname,
			  case when start_time > STR_TO_DATE(CONCAT( DATE(start_time), ' 00:00:00'),'%Y-%m-%d %H:%i:%s') 
			 		   && start_time < STR_TO_DATE(CONCAT( DATE(start_time), ' 08:00:00'),'%Y-%m-%d %H:%i:%s')
						  then  DATE_SUB(DATE(start_time),INTERVAL 1 DAY)
      			  else DATE(start_time)
      		  end as cycledate,
      		  start_time as start_time
      		  
	  from  
	  		machinelog   inner join  machine
      ON 
      machinelog.ioport=machine.portno
	 	    left join operator
	  on
	  operator.id=machinelog.opid
	    	left join job
	  on
	  job.id=machinelog.jobno
	  where 
	  		cycletime>20  and start_time between $st and $et $opidCondtion $jobidCondtion $midCondtion
	 ) t group by  MONTHNAME(t.cycledate),t.machinename,t.opname,t.jobname;
EOT;
  	$retval = mysql_query( $sql, $conn );
   	if(! $retval )
 	 {
  	 	die('Could not get data:[[[['. mysql_error().']]]SQSL'.$sql);
  	 }
 $rows=[];
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
  	{
		$rows[] = $row;
  	}
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
print("\n"); 
	print json_encode($rows);
  	mysql_close($conn);
?>