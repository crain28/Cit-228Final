<?php
// Start the session
session_start();
?>
<?php
include 'connect.php';
doDB();

	//haven't seen the selection form, so show it
	$display_block = "<h1>Deletion Confirmation</h1>";
	$saved_id = $_SESSION['topic_id'];
	
	//get parts of records
	$get_list_sql = "SELECT * FROM FunGroup_topics WHERE topic_id = $saved_id;";
	$get_list_res = mysqli_query($mysqli, $get_list_sql) or die(mysqli_error($mysqli));

	if (mysqli_num_rows($get_list_res) < 1) {
		//no records
		$display_block .= "<p><em>There was an error retrieving the topic!</em></p>";

	} else {
		//has a record, so display results for confirmation
		$rec = mysqli_fetch_array($get_list_res);
		$display_title = stripslashes($rec['topic_title']);
		$display_block .= "<p>Topic title: ".$display_title."</p>";
		$display_block .= "<p><a href='do_delete.php'>Confirm Deletion</a></p>";
		$display_block .= "<p><a href='DiscussionMenu.html'>Cancel Deletion and Return to Menu</a></p>";
	}
	//free result
	mysqli_free_result($get_list_res);

?>
<!DOCTYPE html>
<html>
<head>
<title>Delete Posting</title>
<link href="discussion.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php echo $display_block; ?>
</body>
</html>
