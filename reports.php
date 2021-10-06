<?php include 'config1.php';?>
<?php
error_reporting(E_PARSE | E_ERROR);
	//echo"Take a view here";
	$suid = $_SESSION['uid'];
	//echo $suid;
?>
<html>
<head>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
</head>
<div id="chart"></div>

<div class="container">
  <div class="row">
    <div class="col-md-12 col-lg-12">
			<h1 class="page-header">Reports</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-lg-12">
			<form action="" method="GET" class="form-inline" data-toggle="validator">
				<div class="form-group">
					<label for="select" class="control-label">Subject:</label>
					<?php
						$query_subject = "SELECT subject.name, subject.id from subject
					INNER JOIN user_subject WHERE user_subject.id = subject.id AND user_subject.uid = $suid ";
						$sub=$conn->query($query_subject);
						$rsub=$sub->fetchAll(PDO::FETCH_ASSOC);
						//print_r($rsub);
						$subnm=$rsub[0]['name'];
						$subid=$rsub[0]['id'];
						//echo "<h3>".$subnm." ".$subid."</h3>";
// aj-- used to select subject
						echo "<select name='subject' class='form-control' required='required'>";
						for($i = 0; $i<count($rsub); $i++)
				
						{
							if ($_GET['subject'] == $rsub[$i]['id']) {
								echo"<option value='". $rsub[$i]['id']."' selected='selected'>".$rsub[$i]['name']."</option>";
							}
							else {
								echo"<option value='". $rsub[$i]['id']."'>".$rsub[$i]['name']."</option>";
							}
						}
						echo "</select><br>";
					?>
				</div>
     <?php //used to pick from date ?>
				<div class="form-group" data-provide="datepicker">
					<label for="select" class="control-label">From:</label>
					<input type="date" name="sdate" class="form-control" value="<?php print isset($_GET['sdate']) ? $_GET['sdate'] : ''; ?>" required>
				</div>
<?php //used to pick to  date ?>
				<div class="form-group" data-provide="datepicker">
					<label for="select" class="control-label">To:</label>
					<input type="date" name="edate" class="form-control" value="<?php print isset($_GET['edate']) ? $_GET['edate'] : ''; ?>" required>
				</div>

				<input type="hidden" name="page" value="reports">
				<input type="submit" class="btn btn-info" name="submit" value="Load Student">
			</form>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

			<p>&nbsp;</p>
			<div class="report-data">


			<?php


				$t=time();

				if(isset($_GET['submit']) && !empty($_GET['sdate']) && !empty($_GET['edate']) && ($_GET['edate'] > $_GET['sdate']) && ($_GET['sdate']<$t) && ($_GET['edate']<$t))
				{
					$sdat = $_GET['sdate'];
					$edat= $_GET['edate'];

					$selsub=$_GET['subject'];

					$sdate = strtotime($sdat);

					$edate = strtotime($edat);


				if(($sdate<$t) && ($edate<=$t) && ($edate >= $sdate))
				{

					$query_student = "SELECT student.sid, student.name, student.rollno from student INNER JOIN student_subject WHERE student.rollno = student_subject.rollno AND student_subject.id  = {$selsub}  ORDER BY student.rollno";
					$stu=$conn->query($query_student);
					$rstu=$stu->fetchAll(PDO::FETCH_ASSOC);
				//	print_r($rstu);
				//	echo "<br><br>--------------<br>";
					echo "<table class='table table-striped table-hover reports-table'>";
					echo "<thead>";
					echo "<tr>";
					echo "<th>Roll No</th>";
					echo "<th>Name</th>";
					for($k=$sdate;$k<=$edate;$k=$k+86400)
					{
						$thisDate = date( 'd-m-Y', $k );
						$weekday= date("l", $k );
						$normalized_weekday = strtolower($weekday);
						//aj disp,ay date only if its nt weekend
				//		if(($normalized_weekday!="saturday") && ($normalized_weekday!="sunday"))
						{
							echo "<th>".$thisDate."</th>";
						}
					}
					echo "<th>Present/Total</th>";
					echo "<th>Percentage</th>";
					
					echo "</tr>";
					echo "</thead>";
					echo "</tbody>";
					for($i=0;$i<count($rstu);$i++)
					{
						//echo $i."--"."<br>";
						$present=0;
						$absent=0;
						$totlec=0;
						$perc=0;
						echo"<tr><td><h6>".$rstu[$i]['rollno']."</h6></td>";
						echo "<td><h5>".$rstu[$i]['name']."</h5></td>";
						$dsid=$rstu[$i]['sid'];

						for($j=$sdate;$j<=$edate;$j=$j+86400)
						{
							 //$thisDate = date( 'Y-m-d', $j );
							 //echo "$j"."=".$thisDate."<br>";

							$weekday= date("l", $j );
							$currentDate = date('Y-m-d', $j);
							$normalized_weekday = strtolower($weekday);
							//aj select from database only if its not weekend
					//		 if(($normalized_weekday!="saturday") && ($normalized_weekday!="sunday"))
							 {


								 $sql = "SELECT sid ,ispresent FROM attendance WHERE sid=$dsid AND
								 id=$selsub AND date=$j AND $suid=uid " ;
								$stmt = $conn->prepare($sql);
								$stmt->execute();
								$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
								if(!empty($result)){
								//print_r($result);
									$totlec++;
									if($result[0]['ispresent']==1)
									{
										$present++;
										echo"<td><span class='text-success'>Present</span></td>";
									}
									else
									{
										echo"<td><span class='text-danger'>Absent</span></td>";
										$absent++;
									}
								}else
								//aj take attendance if not taken
								{
									echo "<td><a href='index.php?subject=" . $selsub . "&date=" . $currentDate . "'>TakeAttendance</a></td>";
								}
							}
						}
						if($totlec!=0)
						{

//aj round a floating point number to 2 digit
							$perc=round((($present*100)/$totlec), 2);
							$chart_data .= "{ attendance:'".$perc."', yattendance:".$perc."}, ";
						}
						else
							$perc=0;
						echo"<td><strong>".$present."</strong>/".$totlec."</td>";
						echo"<td>".$perc."&nbsp;%</td>";
						if ($result[0]['sid']==1|| $result[0]['sid']==2)
						{
						if($present >0 && $present<=4)
	{
						$sal=$present*350*1.07;
							//echo"<td><strong>".$sal."</strong></td>";
}
if($present >4 && $present<=5)
{
$sal=$present*350*1.1;
	
}
if($present >5 && $present<=7)
{
$sal=$present*350*1.15;
	//echo"<td><strong>".$sal."</strong></td>";
}
	}
	if ($result[0]['sid']==3)
	{
	if($present >0 && $present<=4)
{
	$sal=$present*245*1.07;
	//	echo"<td><strong>".$sal."</strong></td>";
}
if($present >4 && $present<=5)
{
$sal=$present*245*1.1;
//echo"<td><strong>".$sal."</strong></td>";
}
if($present >5 && $present<=7)
{
$sal=$present*245*1.15;
//echo"<td><strong>".$sal."</strong></td>";
}

}
if ($result[0]['sid']==4|| $result[0]['sid']==8)
{
if($present >0 && $present<=4)
{
$sal=$present*315*1.07;
	//echo"<td><strong>".$sal."</strong></td>";
}
if($present >4 && $present<=5)
{
$sal=$present*315*1.1;
//echo"<td><strong>".$sal."</strong></td>";
}
if($present >5 && $present<=7)
{
$sal=$present*315*1.15;
//echo"<td><strong>".$sal."</strong></td>";
}
}
if ($result[0]['sid']==12)
{
if($present >0 && $present<=4)
{
$sal=$present*180*1.07;
	//echo"<td><strong>".$sal."</strong></td>";
}
if($present >4 && $present<=5)
{
$sal=$present*180*1.1;
//echo"<td><strong>".$sal."</strong></td>";
}
if($present >5 && $present<=7)
{
$sal=$present*180*1.15;
//echo"<td><strong>".$sal."</strong></td>";
}
}

						echo"</tr>";

					}
					echo "</tbody>";
					echo "</table>";
				}else if (($sdate>$edate) || ($edate>$t))
				{
					print '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Sorry!</strong>Please enter correct date range.
              </div>';
				}

				}



			?>
			</div>
		</div>
	</div>
</div>
</html>

