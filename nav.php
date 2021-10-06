<?php
	session_start();
?>

<?php if (isset($_SESSION['islogin']) && $_SESSION['islogin'] == 1) : ?>
	<html>
	<body>
	  <header class ="hero">

	<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
			<ul class="nav sidebar-nav">
					<li class="sidebar-brand">
							<a href="index.php">
																<img src="ubdt.jpg" alt="mAttendance" width="200" height="50">
							</a>
					</li>
					<li>
							<a href="index.php?page=dashboard">Dashboard</a>
					</li>
					<li>
							<a href="index.php?page=attendence">Take Attendance</a>
					</li>
					<li>
							<a href="index.php?page=adds">Add Student</a>
					</li> 
					<li>
							<a href="index.php?page=studentinfo">Assigned to You</a>
					</li>
					<li>
							<a href="index.php?page=reports">Reports</a>
					</li>
					<li>
							<a href="index.php?page=logout">Logout</a>
					</li>
			</ul>
	</nav>
<?php else: ?>
	<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
			<ul class="nav sidebar-nav">
					<li class="sidebar-brand">
							<a href="index.php">
								<img src="ubdt.jpg " alt="Attendance"  width="
                                                                210" height="50">
							</a>
					</li>
					<li>
							<a href="index.php">Login</a>
					</li>

			</ul>
	</nav>
<?php endif; ?>
</header>



  </body>
</html>
