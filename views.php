<?php
/*
** Defining all views	
*/
//session_start(); 

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