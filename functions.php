<?php
/**
 * Contains all functionality
 */
require('connection.php');

session_start();
$baseurl = $_SERVER['SERVER_NAME'];

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
	}
	
	elseif ($_SERVER["QUERY_STRING"] == 'user') {
		return profile();
	}
	
	elseif ($_SERVER["QUERY_STRING"] == 'editProfile') {
		return edit_profile();
	}
	
	elseif ($_SERVER["QUERY_STRING"] == 'saveProfile') {
		return save_profile();
	}
	
	elseif ($_SERVER["QUERY_STRING"] == 'success') {
		return success();
	}

	if ($_SESSION['ROLE'] == 1) {
		switch ($_SERVER["QUERY_STRING"]) {
		  case "addRole":
			  return add_role();
			  break;
		  
		  case "delRole":
			  return del_role();
			  break;
		  
		  case "saveRole":
			  return save_role();
			  break;
		  
		  case "addUser":
			  return add_user();
			  break;
		  
		  case "saveUser":
		    return save_user();
		    break;
		  
		  case "submitUser":
			  return submit_user();
			  break;
		  
		  case "manageSubject":
			  return manage_subject();
			  break;
		  
		  case "delSubject":
			  return delete_subject();
			  break;
		  
		  case "saveSubject":
			  return add_subject();
			  break;
		  
		  case "manageFaculty":
			  return manage_faculty();
			  break;
		  
		  case "editFaculty":
			  if ( isset($_POST['edit'])) {
				  return edit_faculty();
			  }
			  
			  elseif ( isset($_POST['delete'])) {
				  return del_faculty();
			  }
			  break;
		  
		  case "saveFaculty":
			  return save_faculty();
			  break;
		  
		  case "manageStudent":
		    return manage_student();
		    break;
		  
		  case "editStudent":
			  if ( isset($_POST['edit'])) {
				  return edit_student();
			  }
			  
			  elseif ( isset($_POST['delete'])) {
				  return del_student();
			  }
			  break;
	    
	    case "saveStudent":
			  return save_student();
			  break;
		}
	}
	
	if ($_SESSION['ROLE'] == 2) {
		switch ($_SERVER["QUERY_STRING"]) {
		  case "createQB":
			  return createQB();
			  break;
			
			case "createQB1":
			  return createQB1();
			  break;
			
			case "saveQB":
			  return saveQB();
			  break;
			
			case "createTest":
			  return create_test();
			  break;
			
			case "createTest1":
			  return create_test1();
			  break;
			
			case "saveTest":
			  return save_test();
			  break;
		}
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
				 header("Location: http://".$GLOBALS['baseurl']."/?user");
				//return profile();
			}else
				return fail_page();
	}else {
		die("could not connet fo avaliable data:".mysql_error());
	}
	
}
		// if( isset($_POST['submit']) )
		// 	login_check();

		


		/** 
		 * Profile function
		 * This function provides profile details.
		 */

		function profile() {
			$view = tree_view();
			$conn = dbconnect();
			$uid = $_SESSION['UID'];
			//Chosse
			if ($_SESSION['ROLE'] == 1) {
				$query = "select * from admin_profile where u_id=$uid";
				$rel   = mysql_query($query,$conn);
				if (!$rel) {
					die('could not get admin data:'.mysql_error());
				}
				$result = mysql_fetch_array($rel);
				$_SESSION['adminID'] = $result['a_id'];
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
				$_SESSION['facultyID'] = $result['f_id'];
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
				$_SESSION['studentID'] = $result['s_id'];
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
				header("Location: http://".$GLOBALS['baseurl']."/?user");
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
				header("Location: http://".$GLOBALS['baseurl']."/?user");
				
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
				header("Location: http://".$GLOBALS['baseurl']."/?user");
				
			}

			return $view;
		}

		


	/**  ADMIN  FUNCTIONALITY   **/

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
			header("Location: http://".$GLOBALS['baseurl']."/?addRole");
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
				header("Location: http://".$GLOBALS['baseurl']."/?addRole");
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
				header("Location: http://".$GLOBALS['baseurl']."/?success");
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
				header("Location: http://".$GLOBALS['baseurl']."/?success");
				
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
				header("Location: http://".$GLOBALS['baseurl']."/?success");
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
			header("Location: http://".$GLOBALS['baseurl']."/?manageSubject");
		}

		function add_subject() {
			$subname = $_POST['subname'];
			$subcode = $_POST['subcode'];
			$conn = dbconnect();
			$q1   = "insert into subject(sub_name,sub_code) values('$subname','$subcode')";
			$rel  = mysql_query($q1,$conn);
			if(!$rel)
				die('could not execute:'.mysql_error());
			header("Location: http://".$GLOBALS['baseurl']."/?manageSubject");
		}


	

		/************************** manage faculty *******************************/

		function manage_faculty() {
			$view = tree_view();
			$conn = dbconnect();
			$q1   = "select * from faculty_profile";
			$rel  = mysql_query($q1,$conn);
			if(!$rel)
				die('could not execute:'.mysql_error());
			$i = 0;
			while( $data = mysql_fetch_array($rel) ) {
				$faculty["$i"]=array('fid'=>$data['f_id'],'fname'=>$data['first_name'],'lname'=>$data['last_name'],'dept'=>$data['department'],'post'=>$data['post'],'sal'=>$data['salary']);
				$i++;
			}
			$view .= m_faculty_view($faculty);
			return $view;
		}

		function edit_faculty() {
			$view = tree_view();
			$conn = dbconnect();
			$fid  = $_POST['faculty_id'];
			//echo $fid;
			$q1   = "select * from faculty_profile where f_id=$fid";
			$rel  = mysql_query($q1,$conn);
			if(!$rel)
				die('could not execute:'.mysql_error());
			while( $data = mysql_fetch_array($rel) ) {
				$faculty = array('fid'=>$data['f_id'],'fname'=>$data['first_name'],'lname'=>$data['last_name'],'dept'=>$data['department'],'post'=>$data['post'],'sal'=>$data['salary']);
				//print_r($faculty);
			}
			$view .= faculty_edit($faculty);
			return $view;
		}

		function del_faculty() {
			$fid   = $_POST['faculty_id'];
			$conn  = dbconnect();
			$quer  = "select u_id from faculty_profile where f_id=$fid";
			$re    = mysql_query($quer,$conn);
			$resul = mysql_fetch_array($re);
			$query = "delete from faculty_profile where f_id=$fid";
			$rel   = mysql_query($query,$conn);
			$query2= "delete from login where u_id=$resul[0]";
			$rel1  = mysql_query($query2,$conn);
			header("Location: http://".$GLOBALS['baseurl']."/?manageFaculty");
		}

		function save_faculty() {
			$fid  = $_POST['fid'];
			$dept = $_POST['dept'];
			$post = $_POST['Post'];
			$sal  = $_POST['sal'];
			$conn  = dbconnect();
			$query = "update faculty_profile set department='$dept',post='$post',salary=$sal where f_id=$fid";
			$rel   = mysql_query($query,$conn);
			header("Location: http://".$GLOBALS['baseurl']."/?success");
		}



		/************************** manage student *******************************/

		function manage_student() {
			$view = tree_view();
			$conn = dbconnect();
			$q1   = "select * from student_profile";
			$rel  = mysql_query($q1,$conn);
			if(!$rel)
				die('could not execute:'.mysql_error());
			$i = 0;
			while( $data = mysql_fetch_array($rel) ) {
				$student["$i"]=array('sid'=>$data['s_id'],'fname'=>$data['first_name'],'lname'=>$data['last_name'],'roll'=>$data['roll_no']);
				$i++;
			}
			$view .= m_student_view($student);
			return $view;
		}

		function edit_student() {
			$view = tree_view();
			$conn = dbconnect();
			$sid  = $_POST['s_id'];
			//echo $fid;
			$q   = "select * from student_profile where s_id=$sid";
			$rel  = mysql_query($q,$conn);
			$q1    = "select * from class";
			$rel1 = mysql_query($q1,$conn);
			if(!$rel)
				die('could not execute:'.mysql_error());
			while( $data = mysql_fetch_array($rel) ) {
				$cid   = $data['class_id'];
				$q2    = "select * from class where class_id=$cid";
				$rel2  = mysql_query($q2,$conn);
				$data2 = mysql_fetch_array($rel2);
				$student = array('sid'=>$data['s_id'],'fname'=>$data['first_name'],'lname'=>$data['last_name'],'roll'=>$data['roll_no'],'cid'=>$cid,'batch'=>$data2['batch'],'Section'=>$data2['section'],'Program'=>$data2['program'],'branch'=>$data2['branch']);
				//print_r($faculty);
			}
			$i=0;
			while ( $result2 = mysql_fetch_array($rel1) ) {
				$student["class$i"] = array('Cid'=>$result2['class_id'],'Program'=>$result2['program'],'Branch'=>$result2['branch'],'Section'=>$result2['section'],'Batch'=>$result2['batch']);
				$i++;
			}
			$view .= student_edit($student);
			return $view;
		}

		function del_student() {
			$fid   = $_POST['s_id'];
			$conn  = dbconnect();
			$quer  = "select u_id from student_profile where s_id=$fid";
			$re    = mysql_query($quer,$conn);
			$resul = mysql_fetch_array($re);
			$query = "delete from student_profile where s_id=$fid";
			$rel   = mysql_query($query,$conn);
			$query2= "delete from login where u_id=$resul[0]";
			$rel1  = mysql_query($query2,$conn);
			header("Location: http://".$GLOBALS['baseurl']."/?manageStudent");
		}

		function save_student() {
			$sid  = $_POST['sid'];
			$roll = $_POST['roll'];
			$cid  = $_POST['class'];
			$conn  = dbconnect();
			$query = "update student_profile set roll_no=$roll,class_id=$cid where s_id=$sid";
			$rel   = mysql_query($query,$conn);
			header("Location: http://".$GLOBALS['baseurl']."/?success");
		}


	/**  END OF ADMIN FUNCTIONALITY **/


	/**  FACULTY FUNCTIONALITY      **/

	  /****************** Create Question Bank *******************/

	  function createQB() {
	  	$view  = tree_view();
	  	$conn  = dbconnect();
			$quer  = "select * from subject";
			$re    = mysql_query($quer,$conn);
			$i     = 0;
			while($resul = mysql_fetch_array($re)){
				$subject["$i"] = array('subid'=>$resul['sub_id'],'subname'=>$resul['sub_name'],'subcode'=>$resul['sub_code']);
				$i++;
			}
	  	$view .= QB_view($subject);
	  	return $view;
	  }

	  function createQB1() {
	  	$view  = tree_view();
	  	$conn  = dbconnect();
	  	$subid = $_POST['sub_id'];
	  	$fid   = $_SESSION['facultyID'];
			$quer  = "select * from subject where sub_id = $subid";
			$re    = mysql_query($quer,$conn);
			$resul = mysql_fetch_array($re);
			$quer1  = "select * from level";
			$re1    = mysql_query($quer1,$conn);
			$qb    = array('subid'=>$resul['sub_id'],'subname'=>$resul['sub_name'],'subcode'=>$resul['sub_code'],'facultyID'=>$fid);
			$i = 0;
			while($resul1 = mysql_fetch_array($re1)) {
				$qb["level$i"] = array('lid'=>$resul1['l_id'],'level'=>$resul1['level']);
				$i++;
			}	 
	  	$view .= QB_view1($qb);
	  	return $view;
	  }

	  function saveQB() {
	  	$subid = $_POST['subid'];
	  	$facid = $_POST['facid'];
	  	$ques  = $_POST['qcontent'];
	  	$opA   = $_POST['opA'];
	  	$opB   = $_POST['opB'];
	  	$opC   = $_POST['opC'];
	  	$opD   = $_POST['opD'];
	  	$corr  = strtoupper($_POST['correct']);
	  	$marks = $_POST['marks'];
	  	$qtype = $_POST['lid'];
	  	echo $subid." ".$facid." ".$ques." ".$opA." ".$opB." ".$opC." ".$opD." ".$corr." type ".$qtype ; 
	  	$view  = tree_view();
	  	$conn  = dbconnect();
	  	$quer  = "insert into QB(question,marks,c_A,c_B,c_C,c_D,correct,l_id,f_id,sub_id) values ('$ques',$marks,'$opA','$opB','$opC','$opD','$corr',$qtype,$facid,$subid)";
			$re    = mysql_query($quer,$conn);
			if(!$re)
			 echo "problem";	
			header("Location: http://".$GLOBALS['baseurl']."/?createQB");	
	  }


	  /****************** Create Test *******************/

	  function create_test() {
      $view  = tree_view();
	  	$conn  = dbconnect();
			$quer  = "select Distinct sub_id from QB";
			$re    = mysql_query($quer,$conn);
			$i     = 0;
			while($resul = mysql_fetch_array($re)){
				$subid  = $resul['sub_id'];
				$quer1  = "select * from subject where sub_id=$subid";
				$re1    = mysql_query($quer1,$conn);
				$resul1 = mysql_fetch_array($re1);
				$subject["$i"] = array('subid'=>$subid,'subname'=>$resul1['sub_name'],'subcode'=>$resul1['sub_code']);
				$i++;
			}
	  	$view .= createtest_view($subject);
	  	return $view;
	  }
	  
	  function create_test1() {
	  	$view  = tree_view();
	  	$conn  = dbconnect();
	  	$subid = $_POST['sub_id'];
			$quer  = "select * from QB where sub_id=$subid";
			$re    = mysql_query($quer,$conn);
			$i     = 0;
			while($resul = mysql_fetch_array($re)){
				$ques["$i"] = array('subid'=>$resul['sub_id'],'qbid'=>$resul['qb_id'],'question'=>$resul['question'],'marks'=>$resul['marks'],'level'=>$resul['level']);
				$i++;
			}
	  	$view .= createtest_view1($ques);
	  	return $view;
	  }


	  function save_test() {
	  	$view  = tree_view();
	  	$conn  = dbconnect();
	  	$subid = $_POST['subid'];
	  	$facid = $_SESSION['facultyID'];
	  	$cdate = date("Y-m-d H:i:s");
	  	$quer  = "insert into test_schedule(f_id,sub_id,c_date) values ($facid,$subid,'$cdate')";
			$re    = mysql_query($quer,$conn);
			if(!$re)
			 echo "problem1";	
			$quer1 = "select tid from test_schedule where f_id=$facid and sub_id=$subid order by tid desc
								limit 1";
			$re1   = mysql_query($quer1);
			$resul = mysql_fetch_array($re1);
			if(!empty($_POST['check_list'])) {
   	    foreach($_POST['check_list'] as $check) {
   	    		$quer  = "insert into paper(tid,qb_id) values ($resul[0],$check)";
						$re    = mysql_query($quer,$conn);
						if(!$re)
			 				echo "problem2";
            echo $check;
        }
      }
			header("Location: http://".$GLOBALS['baseurl']."/?success");	
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