<?php
/*
** Defining all views	
*/
session_start(); 

//echo $_SERVER['SERVER_NAME'];
function head_view($data) {
	$mainHead = '<div class="nav">
								<h1>Welcome to '.$data.'</h1>
							</div>';
	return $mainHead;
}

//View for login window
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

//View for left side tree
function tree_view() {
	$content = '<div class="band"><h2>Welcome '.$_SESSION['USERNAME'].'<a href="?logout"><span>Logout</span></a></h2></div>
				<div class="left">';
  
  //Tree for admin
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

	//Tree for faculty
	if ($_SESSION['ROLE'] == 2) {
		$content .=  '<ul>
						<li><a href="?editProfile">Edit profile</a></li>
						<li><a href="?createTest">Create test</a></li>
						<li><a href="?createQB">Create Question Bank</a></li>
					</ul>';
	}

	//Tree for student
	if ($_SESSION['ROLE'] == 3) {
		$content .=  '<ul>
						<li><a href="?editProfile">Edit profile</a></li>
						<li><a href="?giveExam">Give Examination</a></li>
						<li><a href="?testEval">Test Evaluation</a></li>
					</ul>';	
	}
	$content .=  '</div>';
	return $content;
}

//Profile view for loggedin role
function midcontent_profview($data) {
	$content = '<div class="midcon"><div class="prof">';
	foreach ($data as $key => $value)
		$content .= '<label id="tagname">'.$key.'::</label><label id="tagvalue">'.$value.'</label><br>';
	$content .= '</div></div>';
	return $content;
}

function midcontent_editview($data) {
	$content = '<div class="midcon"><div class="prof"><form method="post" action="?saveProfile">';
	foreach ($data as $key => $value) {
		$class = str_replace(" ", "" , $key);
		$content .= '<label id="tagname">'.$key.'::</label><input type="text" class="'.$class.'" id="tagvalue" value="'.$value.'" name="'.$key.'" /><br>';
	}
	$content .= '<input id="editbutton" type="submit" value="save"/>';
	$content .= '</form></div></div>';
	return $content;
}

function role_view($data) {
	$content = '<div class="midcon">
								<div class="prof">
									<div class="roletable">';
	$content.= '<table>
								<tbody style="text-align:center;">
									<tr>
										<td>Roles already defined</td>
									</tr>';
		foreach($data as $key => $value) {
				$content .= '<form method="post" action="?delRole">
										<tr> 
										<input hidden type="text" readonly name="rid" value="'.$key.'"/>
										<td><input id="nochange" type="text" readonly name="role" value="'.$value.'"/>
										<input id="delbutton" type="submit" value="Delete"/></td>
									 </tr>
									 </form>';
	}
	$content.= '</tbody>
									</table></div> <div class="roletable">
									<form method="post" action="?saveRole">
										<label id="tagname">Add Role</label><input type="text" name="role"/>
										<input id="editbutton" type="submit" value="Save"/>
									</form></div>
								</div>
							</div>';
	return $content;
}

function adduserview($data) {
	$content = '<div class="midcon">
								<div class="prof">
									<form method="post" action="?saveUser">
										<label id="tagname" >Username::</label><input type="text" name="uname"/><br>
										<label id="tagname" >Password::</label><input type="text" name="pass"/><br>
										<label id="tagname" >Select Role::</label><select id="dropdown" name="role">';
										foreach($data as $key=>$value) {
											$content .= '<option value="'.$key.'">'.$value.'</option>';
										}
							$content.='</select><br><input id="editbutton" type="submit" value="save"/>
									</form>
								</div>
							</div>';
	return $content;
}

function create_profileview($data) {
	$content = '<div class="midcon">
								<div class="prof">
									<form method="post" action="?submitUser">';
	$content.= '<table>
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
											<td><select id="dropdown" name="class">';
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
									</table><input id="delbutton" type="submit" value="save" /></form>
								</div>
							</div>';
	return $content;
}

function subject_view($data) {
	$content = '<div class="midcon">
								<div class="prof">
									<div class="roletable">';
	$content.= '<table>
								<tbody style="text-align:center;">
									<tr>
										<td>Subject Name(code)</td>
									</tr>';
		$k = 0;
		foreach($data as $key => $value) {
				$sid   = $data["subject$k"]['sid'];
				$sname = $data["subject$k"]['sname'];
				$scode = $data["subject$k"]['scode'];
				$content .= '<form method="post" action="?delSubject">
										<tr> 
										<input hidden type="text" readonly name="sid" value="'.$sid.'"/>
										<td><input id="nochange" type="text" readonly name="sname" value="'.$sname.'( '.$scode.' )"/>
										<input id="delbutton" type="submit" value="Delete"/></td>
									 </tr>
									 </form>';
	$k++;
	}
	$content.= '<form method="post" action="?saveSubject">
										<tr>
										<td><label id="tagname">Add Subject</label>
										</tr><tr><td><input placeholder="Subject Name" type="text" name="subname"/></td></tr>
										<tr><td><input placeholder="Subject Code" type="text" name="subcode"/></td></tr>
										<tr><td><input id="delbutton" type="submit" value="  Add  "/></td>
									</tr></form></tbody></table></div>
								</div>
							</div>';
	return $content;
}

function m_faculty_view($data) {
	$content = '<div class="midcon">
								<div class="prof">
								<form method="post" action="?editFaculty">
									<select id="dropdown" name="faculty_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$fid   = $data["$i"]['fid'];
		$fname = $data["$i"]['fname'];
		$lname = $data["$i"]['lname'];
		$content .= '<option value="'.$fid.'">'.$fname." ".$lname.'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input id="delbutton" type="submit" value="Edit" name="edit"/>
									 <input id="delbutton" type="submit" value="Delete" name="delete"/>
									 </form>
									 </div></div>';
	return $content;
}


function faculty_edit( $data ) {
	$content = '<div class="midcon">
								<div class="prof">
									<table>
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
									<tr><td></td><td><input id="editbutton" type="submit" value="save"/></td></tr>
									</form>';
	$content .= '</tbody></table></div>
								</div>';
	return $content;
}


function m_student_view($data) {
	$content = '<div class="midcon">
								<div class="prof">
								<form method="post" action="?editStudent">
									<select id="dropdown" name="s_id">';
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
									 <input id="delbutton" type="submit" value="Edit" name="edit"/>
									 <input id="delbutton" type="submit" value="Delete" name="delete"/>
									 </form>
									 </div></div>';
	return $content;
}


function student_edit( $data ) {
	$content = '<div class="midcon">
								<div class="prof">
									<table>
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
										<td id="tsel"><input id="nochange" type="text" readonly value="'.$prog.'-'.$brnch.'-'.$sec.'-'.$batch.'"/>
										</td>
										<tr><td>Change class</td><td><select id="dropdown" name="class">
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
									<tr><td></td><td><input id="delbutton" type="submit" value="save"/></td></tr>
									</form>';
	$content .= '</tbody></table></div>
								</div>';
	return $content;
}



function QB_view($data) {
	$content = '<div class="midcon">
	              <div class="prof">
	              	<form method="post" action="?createQB1">
										<select id="dropdown" name="sub_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$subid   = $data["$i"]['subid'];
		$subname = $data["$i"]['subname'];
		$subcode = $data["$i"]['subcode'];
		$content .= '<option value="'.$subid.'">'.$subname." (sub code -- ".$subcode.")".'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input id="delbutton" type="submit" value="create" name="create"/>
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
									<td><input id="nochange" type="text" readonly value="'.$subid.'" name="subid"/></td>
		              </tr>
		              <tr>
		              <td>Subject Code</td>
		              <td><input id="nochange" type="text" readonly value="'.$subcode.'" name="subcode"/></td>
		              </tr>
		              <tr>
		              <td>Subject Name</td>
		              <td><input id="nochange" type="text" readonly value="'.$subname.'" name="subname"/></td>
		              </tr>
		              <tr>
		              <td>Faculty ID</td>
		              <td><input id="nochange" type="text" readonly value="'.$facID.'" name="facid"/></td>
		              </tr>
		              <tr>
		              <td colspan=2><textarea rows="4" cols="30" name="qcontent" placeholder="Enter ur question here....."></textarea></td>
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
		              <td>
		              	<select id="dropdown" name=correct>
		              		<option type="text" value="A"/>A</option>
		              		<option type="text" value="B"/>B</option>
		              		<option type="text" value="C"/>C</option>
		              		<option type="text" value="D"/>D</option>
		              	</select>		
		              </td>
		              </tr>
		              <tr>
		              <td>Question Type</td>
		              <td><select id="dropdown" name=lid>';
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
		              <td></td><td><input id="delbutton" type="submit" value="create" name="create"/></td>
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
										<select id="dropdown" name="sub_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$subid   = $data["$i"]['subid'];
		$subname = $data["$i"]['subname'];
		$subcode = $data["$i"]['subcode'];
		$content .= '<option value="'.$subid.'">'.$subname." (sub code -- ".$subcode.")".'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input id="delbutton" type="submit" value="create" name="create"/>
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
										<td><input hidden type="text" value="'.$subid.'" name="subid"/></td>
										<td id="nochange">Question</td>
										<td id="nochange">Marks</td>
										<td id="nochange">Level</td>';
	
	foreach ($data as $key => $value) {
		$qbid  = $data["$i"]['qbid'];
		$ques  = $data["$i"]['question'];
		$marks = $data["$i"]['marks'];
		$level = $data["$i"]['level'];
		$content .= '<tr>
									<td><input type="checkbox" name="check_list[]" value="'.$qbid.'"></td>
									<td>'.$ques.'</td>
									<td>'.$marks.'</td>
									<td>'.$level.'</td>
								</tr>';	
	$i++;
	}
	$content .= '	 <tr><td></td><td><input id="delbutton" type="submit" value="create" name="create"/></td></tr>
									</tbody></table>
									 </form>
									 </div></div>';
	return $content;
}


/**
 * All views of test given by student
 */

function givetest_view($data) {
	$content = '<div class="midcon">
	              <div class="prof">
	              	<form method="post" action="?giveExam1">
										<select id="dropdown" name="test_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$testid  = $data["$i"]['tid'];
		$subname = $data["$i"]['subname'];
		$subcode = $data["$i"]['subcode'];
		$faculty = $data["$i"]['faculty'];
		$date    = $data["$i"]['date'];
		$content .= '<option value="'.$testid.'">'.$subname." (sub code -- ".$subcode.") by ".$faculty." on ".$date.'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input id="delbutton" type="submit" value="Start Exam" name="start"/>
									 </form>
									 </div></div>';
	return $content;
}

function givetest_view1($data) {
	$content = '<div class="midcon">
	              <div class="prof">
	              <div class="timer"><p id="status"></p></div>
	              <script type="text/javascript">countDown();</script>
	              	<form name="quiz" method="post" action="?saveExam">
	              	<table >
	              	<tbody>';
	$i=0;
	foreach ($data as $key => $value) {
		$ques    = $data["$i"]['question'];
		$marks   = $data["$i"]['marks'];
		$cA      = $data["$i"]['cA'];
		$cB      = $data["$i"]['cB'];
		$cC      = $data["$i"]['cC'];
		$cD      = $data["$i"]['cD'];
		$corr    = $data["$i"]['correct'];
		$pid     = $data["$i"]['pqid'];
		$level   = $data["$i"]['level'];
		$q       = $i+1;
		$tid     = $data["$i"]['tid'];
		$content .= '<tr>
								<td>'."Q.no ".$q.'. </td>
								<td colspan=2>'.$ques.'</td>
								<td><input type="radio" hidden checked name="option['.$i.']" value="0"></td>
								</tr>
								<tr>
								<td><input hidden readonly name="paperid['.$i.']" value="'.$pid.'"/></td>
								<td><input type="radio" name="option['.$i.']" value="A">'.$cA.'</td>
								<td><input type="radio" name="option['.$i.']" value="B">'.$cB.'</td>
		            </tr>
		            <tr>
		            <td><input hidden readonly name="tid" value="'.$tid.'"/></td>
								<td><input type="radio" name="option['.$i.']" value="C">'.$cC.'</td>
								<td><input type="radio" name="option['.$i.']" value="D">'.$cD.'</td>
		            </tr>
		            <tr><td colspan=3><hr></td></tr>';	
	$i++;
	}
	$content .= '<tr><td></td><td colspan=2><input id="editbutton" type="submit" value="Save" name="save"/></td></tr>
								</tbody></table>
									 </form>
									 </div></div>';
	return $content;
}

function result_view($data,$data1) {
	$content = '<div class="midcon">
	              <div class="prof">
	              	<table>
	              	<tbody>';
	$j = 0;
	$i = 0;
	foreach ($data as $key => $value) {
		if($j>1) {
		$ques    = $data["$i"]['ques'];
		$maxmark = $data["$i"]['maxmarks'];
		$resp    = $data["$i"]['response'];
		$corr    = $data["$i"]['correct'];
		$mark    = $data["$i"]['urmark'];
		$level   = $data["$i"]['level'];
		$q       = $i+1;

		if($mark==0){
			$content .= '<tr><td colspan =2 style="color:red;">Attempted Question(wrong)</td></tr>';
		}
		else{
			$content .= '<tr><td colspan =2 style="color:green;">Attempted Question(correct)</td></tr>';
		}

		$content .= '<tr>
								<td>'."Q.N. ".$q.'</td>
								<td>'.$ques.'</td>
								<td>'.$level.'</td>
								</tr>
								<tr>
								<td></td>
								<td>Your Answer</td>
								<td>'.$resp.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Correct Answer</td>
								<td>'.$corr.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Max Marks</td>
								<td>'.$maxmark.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Marks Earned</td>
								<td>'.$mark.'</td>
		            </tr><br>';	
		$i++;         
		          }
	$j++;
	}
	$j1 = 0;
	$i1 = 0;
	foreach ($data1 as $key => $value) {
		if($j1>1) {
		$ques1    = $data1["$i1"]['ques'];
		$maxmark1 = $data1["$i1"]['maxmarks'];
		$resp1    = $data1["$i1"]['response'];
		$corr1    = $data1["$i1"]['correct'];
		$mark1    = $data1["$i1"]['urmark'];
		$level1   = $data1["$i1"]['level'];
		$q1       = $i1+1;
		$content .= '<tr><td style="color:orange;">Unattempted Question</td></tr>
								<tr>
								<td>'."Q.N. ".$q1.'</td>
								<td>'.$ques1.'</td>
								<td>'.$level1.'</td>
								</tr>
								<tr>
								<td></td>
								<td>Your Answer</td>
								<td>'.$resp1.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Correct Answer</td>
								<td>'.$corr1.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Max Marks</td>
								<td>'.$maxmark1.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Marks Earned</td>
								<td>'.$mark1.'</td>
		            </tr><br>';	
		$i1++;         
		          }
	$j1++;
	}
	$content .= '</tbody></table>';
	$total     = $data1['paper_total'];
	$attempt   = $data['attempt'];
	$unattempt = $data1['unattempt'];
	$subtotal  = $data['total'];
	$content .= '	<br>
								<hr>
								<table border=1	>
	              <tbody>
	              <tr><td id="nochange" colspan=2>Report Card</td>
	              <tr><td>Total Attempted questions</td><td>'.$attempt.'</td></tr>
								<tr><td>Total Unattempted questions</td><td>'.$unattempt.'</td></tr
								<tr><td>Total Marks</td><td>'.$total.'</td></tr>
								<tr><td>Sub Total</td><td>'.$subtotal.'</td></tr>
								</tbody></table>
								</form>
								</div></div>';
	return $content;
}

function testeval_view($data) {
	$content = '<div class="midcon">
	              <div class="prof">
	              	<form method="post" action="?testEvaluation1">
										<select id="dropdown" name="test_id">';
	$i=0;
	foreach ($data as $key => $value) {
		$testid  = $data["$i"]['tid'];
		$subname = $data["$i"]['subname'];
		$subcode = $data["$i"]['subcode'];
		$faculty = $data["$i"]['faculty'];
		$date    = $data["$i"]['date'];
		$content .= '<option value="'.$testid.'">'.$subname." (sub code -- ".$subcode.") by ".$faculty." on ".$date.'</option>';	
	$i++;
	}
	$content .= '</select>
									 <input id="delbutton" type="submit" value="See Evaluation" name="start"/>
									 </form>
									 </div></div>';
	return $content;
}

function testeval_view1($data) {
	$content = '<div class="midcon">
	              <div class="prof">
	              	<table border=1>
	              	<tbody>';
	$j = 0;
	$i = 0;
	foreach ($data as $key => $value) {
		if($j>0) {
		$ques    = $data["$i"]['ques'];
		$maxmark = $data["$i"]['maxmarks'];
		$resp    = $data["$i"]['response'];
		$corr    = $data["$i"]['correct'];
		$mark    = $data["$i"]['urmark'];
		$level   = $data["$i"]['level'];
		$q       = $i+1;
		$content .= '<tr>
								<td>'."ques no".$q.'</td>
								<td>'.$ques.'</td>
								<td>'.$level.'</td>
								</tr>
								<tr>
								<td></td>
								<td>Your Answer</td>
								<td>'.$resp.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Correct Answer</td>
								<td>'.$corr.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Max Marks</td>
								<td>'.$maxmark.'</td>
		            </tr>
		            <tr>
		            <td></td>
								<td>Marks Earned</td>
								<td>'.$mark.'</td>
		            </tr><br>';	
		$i++;         
		          }
	$j++;
	}
	$total    = $data['total'];
	$content .= '<tr><td>Total Marks</td><td>'.$total.'</td></tr>
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
	$content = '<div class="midcon"><div class="prof"><h2>Successfully Done</h2></div></div>';
	return $content;
}
function logout_view() {
	$content = '<center><div class="midcon"><div class="prof"><h2>Logout successfully</h2><p><a href="http://'.$GLOBALS['baseurl'].'">Click here</a> for login</p></div></div></center>';
	return $content;
}
function accessdeny_view() {
	$content = '<center><div class="midcon"><div class="prof"><div class="error"><h2>Access Denied</h2></div></div></div><center>';
	return $content;
}  
?>