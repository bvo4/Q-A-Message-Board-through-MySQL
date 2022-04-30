<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Databases Project Title</title>
  </head>

<?php
	include 'header.php';
	$header = returnHeader();
	echo $header;
	
	if(isset($_SESSION['uid']) && isset($_POST['qid']))
	{
		echo "<th><form method='post' action='submit_answer.php'>
			<button input type='link' name='qid' value=$_POST[qid]>Submit Answer</button>
			</form>
		</th>";
	}
?>
  <body>
    <h2 style="text-align: center">ANSWERS PAGE</h2>
  </body>
</html>

<?php
	include 'db_connection_project.php';
	$conn = OpenCon();
	
	if(isset($_POST['like']) && isset($_POST['qid']))
	{
		$qid = $_POST['qid'];
		$aid = $_POST['like'];
		
		$sql = "INSERT INTO likes(aid, uid, points) VALUES ($aid, $_SESSION[uid], 1)";
		mysqli_query($conn, $sql);
	}
	if(isset($_POST['best']))
	{
		select_best();
	}
	
	if(isset($_POST['qid']))
	{
		$qid = $_POST['qid'];
		$sql = "select * 
				from answers, post_answers, users
				where post_answers.qid = $qid
				and answers.aid = post_answers.aid
				and users.uid = post_answers.uid
				order by timeposted desc";
		print_sql($conn, $sql, $qid);
		
	}
	else
	{
		$greenthing = '<div class="row">
					<div class="col-sm">
						<div class="alert alert-danger">
							Unfortunately, we have no answers available.
						</div>
					</div>
					</div>';
			echo $greenthing;	
	}
	
	function print_sql($conn, $sql, $qid)
	{
		$like_stmt = null;
		
		if(isset($_SESSION['uid']))
		{
			$uid = $_SESSION['uid'];
			$sql_like_check = "select * from users, likes
								where likes.uid = users.uid
								and users.uid = $uid
								;";
			$like_stmt = mysqli_query($conn, $sql_like_check);
		}
		
		$stmt = mysqli_query($conn, $sql);
		$num = mysqli_num_rows($stmt);
		if($num > 0)
		{
			print_answer($stmt, $like_stmt, $qid);
		}
		else
		{
		  $greenthing = '<div class="row">
						<div class="col-sm">
							<div class="alert alert-danger">
								Unforutnately, there are no answers for this question.
							</div>
						</div>
						</div>';
			echo $greenthing;
		}
	}
	
	function print_answer($stmt, $like_stmt, $qid)
	{
		include 'reactjs.php';
		$conn = OpenCon();
		$sql = "select uid from post_answers where qid = $qid";
		$user_question_id = grab_first_row($conn, $sql);
		$user_question_id = $user_question_id['uid'];
		
		echo "
			<table class = 'table table-dark table-hover' style = 'width:100%'>
			<tr>
			<th> Aid:  </th>
			<th> Body:  </th>
			<th> Username:  </th>
			<th> Likes:  </th>
			<th> Date:  </th>
			<th>Leave a Like?</th>
			<th>Select as best answer?</th></tr>";
			
			while($row = mysqli_fetch_array($stmt))
			{
				$like_match = check_likes($like_stmt, $row['aid']);
		
				$test =
						"<tr>"
						. "<th>" . $row['aid'] ."</th> "
						. "<th>" . $row['body'] ."</th>". "</th>"
						. "<th>" . $row['username'] ."</th> " . "</th>"
						. "<th>" . $row['grade'] . "</th>"
						. "<th>" . $row['timeposted'] . "</th>";
						
				if(isset($_SESSION['uid']) && $_SESSION['uid'] != $user_question_id)
				{
					if(isset($like_stmt) && $like_match)
					{
						$test .="<th><button type='submit' name='like' value=$row[aid] class='btn btn-secondary'>
									You have already liked this
								</button></th>";
					}
					else
					{
						$test .="<form method='post' action='answer.php'>"
						. "<input type='hidden' name='qid' value=$qid>";
						if(isset($_SESSION['uid']))
						{
							$test .= "<th><button type='submit' name='like' value=$row[aid] class='btn btn-danger'>
								Like
							</button></th>";
						}
					}
				}
					$test .= "</form>";
					if(isset($_SESSION['uid']) && $_SESSION['uid'] == $user_question_id)
					{
						$test .= "
								<th><button type='submit' name='best' value=$row[aid] class='btn btn-light'>This is your answer!</button>
								</th> ";
					}
					else if($row['best'] == False)
					{
					$test .= "<form method='post' action='answer.php?qid=$qid'>
							<th><button type='submit' name='best' value=$row[aid] class='btn btn-light'>Select</button>
							</th> "
						. "<input type='hidden' name='qid' value=$qid>
								</form>";
						}
					else
					{
					$test .= "
							<th><button type='submit' name='best' value=$row[aid] class='btn btn-light'>This is Best Answer</button>
							</th> ";
					}
					$test .="</tr>";
					
					echo $test;
			}
				echo "</table>";
	}
	
	function check_likes($like_stmt, $question_aid)
	{
		$like_match = False;
		if(isset($like_stmt))
		{
			while($like_check = mysqli_fetch_array($like_stmt))
			{
				if($like_check['aid'] == $question_aid)
				{
					$like_match = True;
					break;
				}
			}
		}
		return $like_match;
	}
	
	function select_best()
	{
		$conn = OpenCon();
		$aid = $_POST['best'];
		$uid = $_SESSION['uid'];
		
		$qid = $_POST['qid'];
		$sql = "UPDATE post_answers
				SET best=True, grade = grade + 5, weight = weight + 5
				WHERE qid = $qid
				and aid = $aid";
		
		$stmt = mysqli_query($conn, $sql);
	}
	
?>
