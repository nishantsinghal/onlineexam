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
			}elseif ($_SERVER["QUERY_STRING"] == 'delRole') {
				return del_role();
			}elseif ($_SERVER["QUERY_STRING"] == 'saveRole') {
				return save_role();
			}elseif ($_SERVER["QUERY_STRING"] == 'success') {
				return success();
			}elseif ($_SERVER["QUERY_STRING"] == 'addUser') {
				return add_user();
			}elseif ($_SERVER["QUERY_STRING"] == 'saveUser') {
				return save_user();
			}elseif ($_SERVER["QUERY_STRING"] == 'submitUser') {
				return submit_user();
			}elseif ($_SERVER["QUERY_STRING"] == 'manageSubject') {
				return manage_subject();
			}elseif ($_SERVER["QUERY_STRING"] == 'delSubject') {
				return delete_subject();
			}elseif ($_SERVER["QUERY_STRING"] == 'saveSubject') {
				return add_subject();
			}elseif ($_SERVER["QUERY_STRING"] == 'manageFaculty') {
				return add_subject();
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
			// print_r( $_POST);
			// echo $_POST['First_Name'];
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

		



		/********************* for role addition **********************/
		function add_role() {
			$view  = tree_view();
			$conn  = dbconnect();
			$query = "select * from role";
			$rel   = mysql_query($query,$conn);
			while ($data  = mysql_fetch_array($rel)) {
			 	// echo $data['r_id']." with ".$data['role']."<br>";
			  $array[$data['r_id']] = $data['role'];
			 } 
			$view .= role_view($array);
			return $view;
		}

		function del_role() {
			$rid   = $_POST['rid'];
			$role  = $_POST['role'];
			$conn  = dbconnect();
			echo $rid;
			$query = "delete from role where r_id='$rid'";
			$rel   = mysql_query($query,$conn);
			header("Location: http://onlineexam.com/?addRole");
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
				header("Location: http://onlineexam.com/?addRole");
			}else {
				return fail_view();
			}
		}

		
		/*********************for create user************************/

		function add_user() {
			$view  = tree_view();
			$conn  = dbconnect();
			$query = "select * from role";
			$rel   = mysql_query($query,$conn);
			while ($data  = mysql_fetch_array($rel)) {
			 	// echo $data['r_id']." with ".$data['role']."<br>";
			  $array[$data['r_id']] = $data['role'];
			 } 
			$view .= adduserview($array);
			return $view;
		}

		function save_user() {
			$view = tree_view();
			$rid  = $_POST['role'];
			$_SESSION['newUname']= $_POST['uname'];
			$_SESSION['newPass']= $_POST['pass'];
			if ( $rid == 1 ) {
				$_SESSION['newrole'] = 1;
				$profile_var = array('First Name'=>'','Last Name'=>'');
				$view .= create_profileview($profile_var);
			}
			if ( $rid == 2 ) {
				$_SESSION['newrole'] = 2;
				$profile_var = array('First Name'=>'','Last Name'=>'','Salary'=>'','Department'=>'','Post'=>'');
				$view .= create_profileview($profile_var);
			}
			if ( $rid == 3 ) {
				$_SESSION['newrole'] = 3;
				$conn  = dbconnect();
				$query1 = "select * from class";
				$rel1 = mysql_query($query1,$conn);
				$profile_var = array('First Name'=>'','Last Name'=>'','Roll No'=>'');
				$i=0;
				while($result2 = mysql_fetch_array($rel1)) {
					//print_r($result2);
					$profile_var["Class$i"] = array('Cid'=>$result2['class_id'],'Program'=>$result2['program'],'Branch'=>$result2['branch'],'Section'=>$result2['section'],'Batch'=>$result2['batch']);
					//print_r($profile_var);
					//echo" <br><br> $i <br>";
					//echo $profile_var["Class$i"]['Section']."<br><br>";
					$i++;
				}
				$view .= create_profileview($profile_var);
			}
			return $view;
		}

		function submit_user() {
			$view  = tree_view();
			$conn  = dbconnect();
			$uname = $_SESSION['newUname'];
			$pass  = $_SESSION['newPass'];
			$rid   = $_SESSION['newrole'];
			//echo $uname.$pass.$rid; 
			$query = "insert into login(username,password,r_id) values('$uname','$pass',$rid)";
			$rel   = mysql_query($query,$conn);
			$query1= "select u_id from login where username='$uname'";
			$rel1  = mysql_query($query1,$conn);
			if (!$rel || !$rel1) {
					die('could not data:'.mysql_error());
				}

			$result= mysql_fetch_array($rel1);
			//echo "hhhh $result[0]"	;
			if ($_SESSION['newrole'] == 1) {
				$fname = $_POST['First_Name'];
				$lname = $_POST['Last_Name'];
				$query2 = "insert into admin_profile(first_name,last_name,u_id) values('$fname','$lname',$result[0])";
				$rel2   = mysql_query($query2,$conn);
				if (!$rel2) {
					die('could not get admin data:'.mysql_error());
				}
				header("Location: http://onlineexam.com/?success");
			}
			if ($_SESSION['newrole'] == 2) {
				$fname = $_POST['First_Name'];
				$lname = $_POST['Last_Name'];
				$sal   = $_POST['Salary'];
				$dept  = $_POST['Department'];
				$po    = $_POST['Post'];
				$query2 = "insert into faculty_profile(first_name,last_name,salary,department,post,u_id) values('$fname','$lname',$sal,'$dept','$po',$result[0])";
				$rel2   = mysql_query($query2,$conn);
				if (!$rel2) {
					die('could not get admin data:'.mysql_error());
				}
				header("Location: http://onlineexam.com/?success");
				
			}
			if ($_SESSION['newrole'] == 3) {
				$fname = $_POST['First_Name'];
				$lname = $_POST['Last_Name'];
				$roll  = $_POST['Roll_No'];
				$cid   = $_POST['class'];
				$query = "insert into student_profile(first_name,last_name,roll_no,class_id,u_id) values('$fname','$lname','$roll',$cid,$result[0])";
				$rel   = mysql_query($query,$conn);
				if (!$rel || !$rel1) {
					die('could not get admin data:'.mysql_error());
				}
				header("Location: http://onlineexam.com/?success");
			}
		}


		/***********************  manage subject ****************************/

		function manage_subject() {
			$view = tree_view();
			$conn = dbconnect();
			$q1   = "select * from subject";
			$rel  = mysql_query($q1,$conn);
			if(!$rel)
				die('could not execute:'.mysql_error());
			$i = 0;
			while( $data = mysql_fetch_array($rel) ) {
				$subject["subject$i"]=array('sid'=>$data['sub_id'],'sname'=>$data['sub_name'],'scode'=>$data['sub_code']);
				$i++;
			}
			$view .= subject_view($subject);
			return $view;
		}

		function delete_subject() {
			$subid   = $_POST['sid'];
			$conn  = dbconnect();
			echo $subid;
			$query = "delete from subject where sub_id=$subid";
			$rel   = mysql_query($query,$conn);
			header("Location: http://onlineexam.com/?manageSubject");
		}

		function add_subject() {
			$subname = $_POST['subname'];
			$subcode = $_POST['subcode'];
			$conn = dbconnect();
			$q1   = "insert into subject(sub_name,sub_code) values('$subname','$subcode')";
			$rel  = mysql_query($q1,$conn);
			if(!$rel)
				die('could not execute:'.mysql_error());
			header("Location: http://onlineexam.com/?manageSubject");
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