
<html>
    <header class ="hero">


    

   <div align="center"> 
    <form action=" " method="post">
       
        <br><div class="col-md-6 col-md-offset-3 col-lg-6 no-column-padding">
        <div class="form-group alert alert-dismissible alert-danger">
            <input type="text" name="name" placeholder="Enter Student Name" class="form-control" required></div></div>
        <br><br>
        <div class="col-md-6 col-md-offset-3 col-lg-6 no-column-padding">
        <div class="form-group alert alert-dismissible alert-danger">
            <input type="text" name="rollno" placeholder="Enter Student Roll no" class="form-control" required></div></div><br><br>
        
        <div class="col-md-6 col-md-offset-3 col-lg-6 no-column-padding">
        <div class="form-group alert alert-dismissible alert-danger">
            <input type="text" name="subid" placeholder="Enter Student Subject" class="form-control" required></div></div><br><br>
        
           <div class="col-md-6 col-md-offset-3 col-lg-6 no-column-padding">
               <input type="submit" name="submit" value="submit"  class="btn btn-success btn-block"></div>
       </form></div></header>
  </html>  
<?php
include("config1.php");
if(isset($_POST['submit']))
{
    
    $name=$_POST['name'];
    $r=$_POST['rollno'];
    $s=$_POST['subid'];
    $suid = $_SESSION['uid'];
    $query="select * from student where rollno='$r'";
    
    $sub=$conn->query($query);
    $rsub=$sub->fetchAll(PDO::FETCH_ASSOC);
    if(!empty($rsub) && empty($rsu))
    {
        echo'<br><br><br><br><br><br><br><br><br><div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Roll no already exist and Subject do not exist or not assigned to a Teacher</strong>
              </div>';
    }
    else{
    if(!empty($rsub))
    {
       echo'<br><br><br><br><br><br><br><br><br><br><br><div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Roll no already exist!!</strong>
              </div>';
    }
    $q="select * from subject S,user_subject U where S.id='$s' and S.id=U.id and uid='$suid'";
    $su=$conn->query($q);
    $rsu=$su->fetchAll(PDO::FETCH_ASSOC);
    if(empty($rsu))
    {
        echo'<br><br><br><br><br><br><br><br><br><div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Subject do not exist or not assigned to a Teacher</strong>
              </div>';
    }
    if(!empty($rsu) && (empty($rsub)))   
    {
        $query1="insert into student (name,rollno) values ('$name','$r')";
        $sub1=$conn->query($query1);
        
        $q1="insert into student_subject (rollno,id) values ('$r','$s')";
        $sub2=$conn->query($q1);
        
      echo'<br><br><br><br><br><br><br><br><br><br><br><div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Student Added Successfully</strong>
              </div>';
    }
    }
}
?>