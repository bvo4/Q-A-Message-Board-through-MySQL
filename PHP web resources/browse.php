<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Databases Project</title>
  </head>

  
<style>
.search_box {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 40%;
}
.container {
  display: flex;
}

.parent {
  width: 1200px;
  background-color: #333;

  margin: 20px 0; /* outer margin doesn't matter */
}

div.demo {
    display:table;
    width:50%;
}
div.demo span {
    display:table-cell;
    text-align:center;
}
.container.space-around {
  padding: 5px;
  text-align: center;
  background: #808080;
  color: white;
  font-size: 15px;
  justify-content: space-around;
}
.container.space-between {  
  justify-content: space-between;
}

.tablink {
  background-color: #555;
  color: white;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  font-size: 17px;
  width: 200%;
  display:inline
}
table, th, td {
  border:1px solid black;
}
.cb-btn:checked + label {
  background-color: Red; 
}

.center {
margin: auto;
width: 60%;
padding: 10px;
}


</style>

<?php
	include 'header.php';
	$header = returnHeader();
	echo $header;
?>

  <body>
    <h2 style="text-align: center">Browsing PAGE</h2>
  </body>
</html>

<?php
	include 'db_connection_project.php';
	echo '<body> <center>Please select the following topics that you believe your answer may belong to.</center></body>';
	grab_topics();
	grab_subtopics();
	search_options();
	
//Present the list of topics choose from based off what was found from the topics table
function grab_topics()
{
	$conn = OpenCon();
	$sql = "select *
			from topic
			";
	$stmt = mysqli_query($conn, $sql);
	$b = 0;

	echo "<form method = 'post' action = 'search.php'>";
	echo '<div id="basic-primary-block" class="col-8 center">
			<div class="input-group mb-1">
				<div>
					<button class="btn btn-outline-secondary" name="search" type="submit"><i aria-hidden="true"></i> Search
					</button>
				</div>
					<input id="basic-search-input" name="search" autofocus="" type="text" class="form-control" placeholder="Input your keywords separated by a space" value="">
					<div>
					</div>
			</div>
		   </div>';
	/* Presents the list of topics */
	echo '<div class="center btn-toolbar demo parent">';
	while($row = mysqli_fetch_array($stmt))
	{
	$test = '<span>'
			.'<input src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" name="topic[]" type="checkbox" value="top_'
			. $row['tname']
			.'" id=tn'
			. $b
			. ' hidden class="cb-btn"><label class="btn btn-primary"'
			. 'for="tn'
			. $b 
			. '">'
			
			.$row['tname']
			.'</label>'
			. '<br/> '
			. '</span>'
			;
			$test = str_replace(PHP_EOL, '<br />', $test);
			echo $test;
	$b = $b + 1;
	echo "</select>";
	}
	echo "</div>";
}
/* Presents the list of subtopics based off what was found in the subtopics table*/
function grab_subtopics()
{
	$conn = OpenCon();
	$sql = "select *
			from subtopic
			";
	$stmt = mysqli_query($conn, $sql);
	$b = 0;

	echo '<div class="center demo btn-toolbar parent">';
	/* Presents the list of subtopics */
	while($row = mysqli_fetch_array($stmt))
	{
	$test =
			'<span>'
			.'<input src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" name="topic[]" type="checkbox" value ="sub_'
			. $row['sname'] . '"'
			.'id=sn'
			. $b
			. ' hidden class="cb-btn"><label class="btn btn-primary"'
			. 'for="sn'
			. $b 
			. '">'
			.$row['sname']
			.'</label>'
			. '<br/>'
			. '</span>'
			;
			$test = str_replace(PHP_EOL, '<br />', $test);
			echo $test;
			$b = $b + 1;
			echo "</select>";
	}
	echo "</div>";
}

/* Provides an optoin to search for answers, questions, or both */
function search_options()
{
	echo '<div class="center demo btn-toolbar parent">';
	echo	'<span>
			<input src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" name="Check_Question" type="checkbox" id=12 class="cb-btn">
			<label class="btn btn-success" for="12">
				Search within Questions
			</label>
			</span>
			
			<span>
			<input src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" name="Check_Answer" type="checkbox" id=13 class="cb-btn">
			<label class="btn btn-success" for="13">
				Search within Answers
			</label>
			</span>
			'
			;
			
	echo "</div></form>";
}
?>
