<?php
/*
** Defining all views	
*/
//session_start(); 

//echo $_SERVER['SERVER_NAME'];
function head_view($data) {
	$mainHead = '<div class="nav">
								<h1>Welcome to '.$data.'</h1>
							</div>';
	return $mainHead;
}

function login_view() {
	$content = '<marquee id="mar" direction="left">:::::----Please login to view details----:::::</marquee>
							<center>
							<div class="login-bar">
								<form method="POST" action="?login">
									<input id="uname" type="text" name="username" placeholder="Enter your username"/><br>
									<input id="pass" type="password" name="password" placeholder="Enter password" /><br>
									<input id="sbutton" type="submit" name="submit" value="Login"/>
								</form>
							</div>
							</center>';
	return $content;
}

function tree_view() {
	$content = '<div class="band"><h2>Welcome '.$_SESSION['USERNAME'].'</h2></div>
				<div class="left">';
	if ($_SESSION['ROLE'] == 1) {
		$content .= '<ul>
						<li><a href="?editProfile">Edit profile</a></li>
						<li><a href="?addRole">Manage Role</a></li>
						<li><a href="?addUser">Add User</a></li>
						<li><a href="?manageSubject">Manage Subject</a></li>
						<li><a href="?manageFaculty">Manage faculty</a></li>
						<li><a href="?manageStudent">Manage student</a></li>
					</ul>';
	}
	if ($_SESSION['ROLE'] == 2) {
		$content .=  '<ul>
						<li><a href="?editProfile">Edit profile</a></li>
						<li><a href="?createTest">Create test</a></li>
						<li><a href="?createQB">Create Question Bank</a></li>
					</ul>';
	}
	if ($_SESSION['ROLE'] == 3) {
		$content .=  '<ul>
						<li><a href="?editProfile">Edit profile</a></li>
						<li><a href="?giveExam">Give Examination</a></li>
						<li><a href="?viewResult">View Result</a></li>
						<li><a href="?testEval">Test Evaluation</a></li>
					</ul>';	
	}
	$content .=  '</div>';
	return $content;
}

function midcontent_profview($data) {
	$content = '<div class="midcon"><div class="prof">';
	foreach ($data as $key => $value)
		$content .= '<label id="tagname">'.$key.'::</label><label id="tagvalue">'.$value.'</label><br>';
	$content .= '</div></div>';
	return $content;
}

function midcontent_editview($data) {
	$content = '<div class="midcon"><div class="prof"><form method="post" action="?saveProfile">';
	foreach ($data as $key => $value)
		$content .= '<label id="tagname">'.$key.'::</label><input type="text" id="tagvalue" value="'.$value.'" name="'.$key.'" /><br>';
	$content .= '<input type="submit" value="save"/>';
	$content .= '</form></div></div>';
	return $content;
}

function role_view($data) {
	$content = '<div class="midcon">
								<div class="prof">
									<div class="roletable">';
	$content.= '<table border="1">
								<tbody>
									<tr>
										<td>Role id</td>
										<td>Role</td>
									</tr>';
		foreach($data as $key => $value) {
				$content .= '<form method="post" action="?delRole">
										<tr> 
										<td><input type="text" readonly name="rid" value="'.$key.'"/></td>
										<td><input type="text" readonly name="role" value="'.$value.'"/></td>
										<td><input type="submit" value="Delete"/></td>
									 </tr>
									 </form>';
	}
	$content.= '</tbody>
									</table></div> <div class="roletable">
									<form method="post" action="?saveRole">
										<label id="tagname">Add Role</label><input type="text" name="role"/>
										<input type="submit" value="Save"/>
									</form></div>
								</div>
							</div>';
	return $content;
}

function adduserview($data) {
	$content = '<div class="midcon">
								<div class="prof">
									<form method="post" action="?saveUser">
										Username::<input type="text" name="uname"/><br>
										Password::<input type="text" name="pass"/><br>
										Select Role::<select name="role">';
										foreach($data as $key=>$value) {
											$content .= '<option value="'.$key.'">'.$value.'</option>';
										}
							$content.='</select><br><input type="submit" value="save"/>
									</form>
								</div>
							</div>';
	return $content;
}

function create_profileview($data) {
	$content = '<div class="midcon">
								<div class="prof">
									<form method="post" action="?submitUser">';
	$content.= '<table border="1">
								<tbody>';
	if($_SESSION['newrole'] == 3) {
		$i=0;
		foreach($data as $key => $value) {
			if ($i<3) { 
				$content .= '<tr> 
											<td><label>'.$key.'</label></td>
											<td><input type="text" name="'.$key.'" value="'.$value.'"/></td>
										 </tr>';
			}							 
		  $i++;
		}
		$content .= '<tr> 
											<td><label>Select Class:</label></td>
											<td><select name="class">';
		$j=0;
		$k=0;
		foreach	($data as $key => $value) {	
			if ($j>2) {
				$cid  = $data["Class$k"]['Cid'];
				$prog = $data["Class$k"]['Program'];
				$bat  = $data["Class$k"]['Batch'];
				$brnch= $data["Class$k"]['Branch'];
				$sec  = $data["Class$k"]['Section'];
				$content .= '<option value="'.$cid.'">'.$prog."/".$brnch."/".$sec."/".$bat.'</option>';
				$k++;
			}
			$j++;
		}
		$content .= '</select></td>
										 </tr>';
	}else {
		foreach($data as $key => $value) {
			$content .= '<tr> 
										<td><label>'.$key.'</label></td>
										<td><input type="text" name="'.$key.'" value="'.$value.'"/></td>
									 </tr>';
		}
	}								
	$content .= '</tbody>
									</table><input type="submit" value="save" /></form>
								</div>
							</div>';
	return $content;
}

function subject_view($data) {
	$content = '<div class="midcon">
								<div class="prof">
									<div class="roletable">';
	$content.= '<table border="1">
								<tbody>
									<tr>
										<td>Subject ID</td>
										<td>Subject Name</td>
										<td>Subject Code</td>
									</tr>';
		$k = 0;
		foreach($data as $key => $value) {
				$sid   = $data["subject$k"]['sid'];
				$sname = $data["subject$k"]['sname'];
				$scode = $data["subject$k"]['scode'];
				$content .= '<form method="post" action="?delSubject">
										<tr> 
										<td><input type="text" readonly name="sid" value="'.$sid.'"/></td>
										<td><input type="text" readonly name="sname" value="'.$sname.'"/></td>
										<td><input type="text" readonly name="s" value="'.$scode.'"/></td>
										<td><input type="submit" value="Delete"/></td>
									 </tr>
									 </form>';
	$k++;
	}
	$content.= '<form method="post" action="?saveSubject">
										<tr>
										<td><label id="tagname">Add Subject</label></td>
										<td><input type="text" name="subname"/></td>
										<td><input type="text" name="subcode"/></td>
										<td><input type="submit" value="Add"/></td>
									<tr></form></tbody></table></div>
								</div>
							</div>';
	return $content;
}

function m_faculty_view($data) {
	$content = '<div class="midcon">
								<div class="prof">
								<form method="post" action="?editFaculty">
									<select name="faculty_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$fid   = $data["$i"]['fid'];
		$fname = $data["$i"]['fname'];
		$lname = $data["$i"]['lname'];
		$content .= '<option value="'.$fid.'">'.$fname." ".$lname.'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input type="submit" value="Edit" name="edit"/>
									 <input type="submit" value="Delete" name="delete"/>
									 </form>
									 </div></div>';
	return $content;
}


function faculty_edit( $data ) {
	$content = '<div class="midcon">
								<div class="prof">
									<table border="1">
										<tbody>';
		$fid   = $data['fid'];
		$fname = $data['fname'];
		$lname = $data['lname'];
		$dept  = $data['dept'];
		$post  = $data['post'];
		$sal   = $data['sal'];
		$content .= '	<form method="post" action="?saveFaculty">
									<tr><td id="generalhead" colspan="2">Faculty info</td></tr>
									<tr>
										<td>Faculty ID</td>
										<td><input id="nochange" type="text" readonly name="fid" value="'.$fid.'"/>
									</tr>
									<tr>
										<td>First Name</td>
										<td><input id="nochange" type="text" readonly name="fname" value="'.$fname.'"/>
									</tr>
									<tr>
										<td>Last Name</td>
										<td><input id="nochange" type="text" readonly name="lname" value="'.$lname.'"/>
									</tr>
									<tr>
										<td>Department</td>
										<td><input type="text" name="dept" value="'.$dept.'"/>
									</tr>
									<tr>
										<td>Post</td>
										<td><input type="text" name="Post" value="'.$post.'"/>
									</tr>
									<tr>
										<td>Salary</td>
										<td><input type="text" name="sal" value="'.$sal.'"/>
									</tr>
									<tr><td></td><td><input type="submit" value="save"/></td></tr>
									</form>';
	$content .= '</tbody></table></div>
								</div>';
	return $content;
}


function m_student_view($data) {
	$content = '<div class="midcon">
								<div class="prof">
								<form method="post" action="?editStudent">
									<select name="s_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$sid   = $data["$i"]['sid'];
		$fname = $data["$i"]['fname'];
		$lname = $data["$i"]['lname'];
		$roll  = $data["$i"]['roll'];
		$content .= '<option value="'.$sid.'">'.$fname." ".$lname." (roll no-- ".$roll.")".'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input type="submit" value="Edit" name="edit"/>
									 <input type="submit" value="Delete" name="delete"/>
									 </form>
									 </div></div>';
	return $content;
}


function student_edit( $data ) {
	$content = '<div class="midcon">
								<div class="prof">
									<table border="1">
										<tbody>';
		$sid   = $data['sid'];
		$fname = $data['fname'];
		$lname = $data['lname'];
		$roll  = $data['roll'];
		$cid   = $data['cid'];
		$prog  = $data['Program'];
		$batch = $data['batch'];
		$brnch = $data['branch'];
		$sec   = $data['Section'];
		$content .= '	<form method="post" action="?saveStudent">
									<tr><td id="generalhead" colspan="2">Student info</td></tr>
									<tr>
										<td>Student ID</td>
										<td><input id="nochange" type="text" readonly name="sid" value="'.$sid.'"/></td>
									</tr>
									<tr>
										<td>First Name</td>
										<td><input id="nochange" type="text" readonly name="fname" value="'.$fname.'"/>
									</tr>
									<tr>
										<td>Last Name</td>
										<td><input id="nochange" type="text" readonly name="lname" value="'.$lname.'"/>
									</tr>
									<tr>
										<td>Roll No</td>
										<td><input type="text" name="roll" value="'.$roll.'"/>
									</tr>
									<tr>
										<td>Class</td>
										<td id="tsel"><input id="nochange" type="text" readonly value="'.$cid.'"/>
												<input id="nochange" type="text" readonly value="'.$prog.'"/>
												<input id="nochange" type="text" readonly value="'.$brnch.'"/>
												<input id="nochange" type="text" readonly value="'.$sec.'"/>
												<input id="nochange" type="text" readonly value="'.$batch.'"/>
										</td>
										<tr><td>Change class</td><td><select name="class">
										';
			$i = 0;
			$j = 0;
			//print_r($data);
			foreach($data as $key=>$value) {
				
				//echo $i;
				if ($i >=9) {
					$id = $data["class$j"]['Cid'];
					$pr = $data["class$j"]['Program'];
					$br = $data["class$j"]['Branch'];
					$ba = $data["class$j"]['Batch'];
					$se = $data["class$j"]['Section'];
					if ($cid==$id) {
						$content .= '<option value="'.$id.'" selected>'.$pr."-".$br."-".$se."-".$ba.'</option>';
	          $j++;
        	}else {
        		$content .= '<option value="'.$id.'">'.$pr."-".$br."-".$se."-".$ba.'</option>';
         		$j++;
        	}
        }
      $i++;
      }
      $content .=	'</select></td></tr>
									</tr>
									<tr><td></td><td><input type="submit" value="save"/></td></tr>
									</form>';
	$content .= '</tbody></table></div>
								</div>';
	return $content;
}



function QB_view($data) {
	$content = '<div class="midcon">
	              <div class="prof">
	              	<form method="post" action="?createQB1">
										<select name="sub_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$subid   = $data["$i"]['subid'];
		$subname = $data["$i"]['subname'];
		$subcode = $data["$i"]['subcode'];
		$content .= '<option value="'.$subid.'">'.$subname." (sub code -- ".$subcode.")".'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input type="submit" value="create" name="create"/>
									 </form>
									 </div></div>';
	return $content;
}

function QB_view1($data) {
	$content = '<div class="midcon">
	              <div class="prof">
	              	<form method="post" action="?saveQB">';
		$subid   = $data['subid'];
		$subname = $data['subname'];
		$subcode = $data['subcode'];
		$facID   = $data['facultyID'];
		$content .= '<table>
									<tbody>
									<tr>
									<td>Subject ID</td>
									<td id="nochange"><input type="text" readonly value="'.$subid.'" name="subid"/></td>
		              </tr>
		              <tr>
		              <td>Subject Code</td>
		              <td id="nochange"><input type="text" readonly value="'.$subcode.'" name="subcode"/></td>
		              </tr>
		              <tr>
		              <td>Subject Name</td>
		              <td id="nochange"><input type="text" readonly value="'.$subname.'" name="subname"/></td>
		              </tr>
		              <tr>
		              <td>Faculty ID</td>
		              <td id="nochange"><input type="text" readonly value="'.$facID.'" name="facid"/></td>
		              </tr>
		              <tr>
		              <td colspan=2><textarea rows="4" cols="50" name="qcontent" placeholder="Enter ur question here....."></textarea></td>
		              </tr>
		              <tr>
		              <td>Question Marks</td>
		              <td><input type="text" name="marks"/></td>
		              </tr>
		              <tr>
		              <td>Option A</td>
		              <td><input type="text" name="opA"/></td>
		              </tr>
		             <tr>
		              <td>Option B</td>
		              <td><input type="text" name="opB"/></td>
		              </tr>
		              <tr>
		              <td>Option C</td>
		              <td><input type="text" name="opC"/></td>
		              </tr>
		              <tr>
		              <td>Option D</td>
		              <td><input type="text" name="opD"/></td>
		              </tr>
		              <tr>
		              <td>Correct Option</td>
		              <td><input type="text" name="correct"/></td>
		              </tr>
		              <tr>
		              <td>Question Type</td>
		              <td><select name=lid>';
		$i = 0;
		$j = 0;
		foreach ($data as $key => $value) {
			if ($i > 3) {
				$lid      = $data["level$j"]['lid'];
				$level    = $data["level$j"]['level'];
				$content .= '<option value="'.$lid.'">'.$level.'</option>';
				$j++;
			}
			$i++;
		}
		$content .='</select></td>
		              </tr>
		              <tr>
		              <td></td><td><input type="submit" value="create" name="create"/></td>
		              </tr>
		              </tbody>
		              </table>
								 </form>
								 </div></div>';
	return $content;
}


function createtest_view($data) {
	$content = '<div class="midcon">
	              <div class="prof">
	              	<form method="post" action="?createTest1">
										<select name="sub_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$subid   = $data["$i"]['subid'];
		$subname = $data["$i"]['subname'];
		$subcode = $data["$i"]['subcode'];
		$content .= '<option value="'.$subid.'">'.$subname." (sub code -- ".$subcode.")".'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input type="submit" value="create" name="create"/>
									 </form>
									 </div></div>';
	return $content;
}


function createtest_view1($data) {
	$i = 0;
	$subid = $data["$i"]['subid'];
	$content = '<div class="midcon">
	              <div class="prof">
	              	<form method="post" action="?saveTest">
										<table border=1><tbody>
										<tr>
										<td><input type="text" value="'.$subid.'" name="subid"/></td>
										<td>Question</td>
										<td>Marks</td>
										<td>Level of Hardness</td>';
	
	foreach ($data as $key => $value) {
		$qbid  = $data["$i"]['qbid'];
		$ques  = $data["$i"]['question'];
		$marks = $data["$i"]['marks'];
		$level = $data["$i"]['level'];
		$content .= '<tr>
									<td><input type="checkbox" name="check_list[]" value="'.$qbid.'"></td>
									<td><textarea rows=4 cols=50 readonly name="ques" >'.$ques.'</textarea></td>
									<td>'.$marks.'</td>
									<td>'.$level.'</td>
								</tr>';	
	$i++;
	}
	$content .= '	 <tr><td></td><td><input type="submit" value="create" name="create"/></td></tr>
									</tbody></table>
									 </form>
									 </div></div>';
	return $content;
}


/*
** general views
*/
function fail_view() {
	$content = '<center><div class="error"><h2>Something went Wrong</h2></div><center>';
	return $content;
}  
function success_view() {
	$content = '<div class="midcon"><h2>Successfully Done</h2></div>';
	return $content;
}
?>