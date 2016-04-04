<?php
/**
** Contains all functionality
**/
require('connection.php');

session_start();

//for title header part
	function head() {
		$head     = 'Online Exam Portal';
		$showhead = head_view($head);
		return $showhead;
	}


	//for main_content
		function main_content() {
			if ($_SERVER["QUERY_STRING"] == '')
				return login_view();
			elseif ($_SERVER["QUERY_STRING"] == 'login') {
				return login_check();
			}elseif ($_SERVER["QUERY_STRING"] == 'user') {
				return profile();
			}elseif ($_SERVER["QUERY_STRING"] == 'editProfile') {
				return edit_profile();
			}elseif ($_SERVER["QUERY_STRING"] == 'saveProfile') {
				return save_profile();
			}elseif ($_SERVER["QUERY_STRING"] == 'addRole') {
				return add_role();
			}elseif ($_SERVER["QUERY_STRING"] == 'saveRole') {
				return save_role();
			}elseif ($_SERVER["QUERY_STRING"] == 'success') {
				return success();
			}
		}

	//for footer part
		function footer() {

		}	

	//for login checking
		function login_check() {
			//echo "in login_check";
			$user = $_POST['username'];
			$pass = $_POST['password'];
			if ( isset($_POST['username']) ) {	
				$conn  = dbconnect();
				$line  = "select * from login where username='$user'";
				$query = mysql_query($line,$conn);
				if ( !$query ) {
					die('Could not get data: ' . mysql_error());
				}
				$details = mysql_fetch_array($query);
				$pass1   = $details['password'];
				$u_id    = $details['u_id'];
				$role    = $details['r_id'];
				// echo "{$details['password']} is with $pass also $pass1";
					if ( $pass == $pass1 ) {
						$_SESSION['USERNAME'] = $user;
						$_SESSION['PASSWORD'] = $pass;
						$_SESSION['ROLE']     = $role;
						$_SESSION['UID']      = $u_id;
						 header("Location: http://onlineexam.com/?user");
						//return profile();
					}else
						return fail_page();
			}else {
				die("could not connet fo avaliable data:".mysql_error());
			}
			
		}
		// if( isset($_POST['submit']) )
		// 	login_check();

		/* for profile function */
		function profile() {
			$view = tree_view();
			$conn = dbconnect();
			$uid = $_SESSION['UID'];
			if ($_SESSION['ROLE'] == 1) {
				$query = "select * from admin_profile where u_id=$uid";
				$rel   = mysql_query($query,$conn);
				if (!$rel) {
					die('could not get admin data:'.mysql_error());
				}
				$result = mysql_fetch_array($rel);
				$profile_var = array('First Name'=>$result['first_name'],'Last Name'=>$result['last_name']);
				$_SESSION['PROFILE'] = $profile_var;
				$view .= midcontent_profview($profile_var);
			}
			if ($_SESSION['ROLE'] == 2) {
				$query = "select * from faculty_profile where u_id=$uid";
				$rel   = mysql_query($query,$conn);
				if (!$rel) {
					die('could not get faculty data:'.mysql_error());
				}
				$result = mysql_fetch_array($rel);
				$profile_var = array('First Name'=>$result['first_name'],'Last Name'=>$result['last_name'],'Salary'=>$result['salary'],'Department'=>$result['department'],'Post'=>$result['post']);
				$_SESSION['PROFILE'] = $profile_var;
				$view .= midcontent_profview($profile_var);
			}
			if ($_SESSION['ROLE'] == 3) {
				$query = "select * from student_profile where u_id=$uid";
				$rel   = mysql_query($query,$conn);
				if (!$rel) {
					die('could not get faculty data:'.mysql_error());
				}
				$result = mysql_fetch_array($rel);
				$cid = $result['class_id'];
				$_SESSION['CID'] = $cid;
				$query1 = "select * from class where class_id=$cid";
				$rel1 = mysql_query($query1,$conn);
				$result2 = mysql_fetch_array($rel1);
				$profile_var = array('First Name'=>$result['first_name'],'Last Name'=>$result['last_name'],'Roll No'=>$result['roll_no'],'Program'=>$result2['program'],'Branch'=>$result2['branch'],'Section'=>$result2['section'],'Batch'=>$result2['batch']);
				$_SESSION['PROFILE'] = $profile_var;
				$view .= midcontent_profview($profile_var);
			}

			return $view;
		}

		/* for edit profile */
		function edit_profile() {
			$pro  = $_SESSION['PROFILE'];
			$view = tree_view(). midcontent_editview($pro);
			return $view;
		}

		/* for save profile */
		function save_profile() {
			print_r( $_POST);
			echo $_POST['First_Name'];
			$view = tree_view();
			$conn = dbconnect();
			$uid = $_SESSION['UID'];
			if ($_SESSION['ROLE'] == 1) {
				$fname = $_POST['First_Name'];
				$lname = $_POST['Last_Name'];
				$query = "update admin_profile set first_name='$fname',last_name='$lname' where u_id=$uid";
				$rel   = mysql_query($query,$conn);
				if (!$rel) {
					die('could not get admin data:'.mysql_error());
				}
				header("Location: http://onlineexam.com/?user");
			}
			if ($_SESSION['ROLE'] == 2) {
				$fname = $_POST['First_Name'];
				$lname = $_POST['Last_Name'];
				$sal   = $_POST['Salary'];
				$dept  = $_POST['Department'];
				$po    = $_POST['Post'];
				$query = "update faculty_profile set first_name='$fname',last_name='$lname',salary=$sal,department='$dept',post='$po' where u_id=$uid";
				$rel   = mysql_query($query,$conn);
				if (!$rel) {
					die('could not get admin data:'.mysql_error());
				}
				header("Location: http://onlineexam.com/?user");
				
			}
			if ($_SESSION['ROLE'] == 3) {
				$fname = $_POST['First_Name'];
				$lname = $_POST['Last_Name'];
				$roll  = $_POST['Roll_No'];
				$prog  = $_POST['Program'];
				$brnch = $_POST['Branch'];
				$batch = $_POST['Batch'];
				$sec   = $_POST['Section'];
				$cid   = $_SESSION['CID'];
				$quer  = "update class set program='$prog',section='$sec',batch='$batch',branch='$brnch' where class_id=$cid";
				$query = "update student_profile set first_name='$fname',last_name='$lname' where u_id=$uid";
				$rel1  = mysql_query($quer,$conn);
				$rel   = mysql_query($query,$conn);
				if (!$rel || !$rel1) {
					die('could not get admin data:'.mysql_error());
				}
				header("Location: http://onlineexam.com/?user");
				
			}

			return $view;
		}

		/* for role addition */
		function add_role() {
			$view  = tree_view();
			$conn  = dbconnect();
			$query = "select * from role";
			$rel   = mysql_query($query,$conn);
			while ($data  = mysql_fetch_array($rel)) {
			 	echo $data['r_id']." with ".$data['role']."<br>";
			  $array[$data['r_id']] = $data['role'];
			 } 
			$view .= role_view($array);
			return $view;
		}

		function save_role() {
			$role  = $_POST['role'];
			$conn  = dbconnect();
			$query = "select * from role";
			$rel   = mysql_query($query,$conn);
			if (!$rel) {
					die('could not get role data:'.mysql_error());
			}
			$result = mysql_fetch_array($rel);
			foreach ($result as $res) {
				if ($role == $res['role'])
					$set = 1;
			}
			if (!$set) {
				$query1 = "insert into role(role) values ('$role')";
				$rel1   = mysql_query($query1,$conn);
				if (!$rel1) {
					die('could not insert data:'.mysql_error());
				}
				header("Location: http://onlineexam.com/?success");
			}else {
				return fail_view();
			}
		}

		/* for success function */
		function success() {
			$view = tree_view().success_view();
			return $view;
		}
		/* for failure function */
		function fail_page() {
			return fail_view();
		}
?>