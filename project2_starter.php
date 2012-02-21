<?php session_start();




function printHeading()
{
print "<html><head><title>Project 2 Starter Kit:Namecheck</title>



</head><body>";
}

 printHeading();

// Checknames: Project 2 Starter Kit: DIG 4104c - Spring 12
// Moshell
// This version (project2.starter.php) demonstrates how to use a database
// to maintain two tables: one for counting visits, and one for checking names.


$Testnumber=4;
$onmac=1; // set this to 0 if running on Sulley

function logprint($what,$when)
{global $Testnumber;
	if ($when==$Testnumber)
		print "LP:$what <br />";
}

//checkperson: return person's name if they are found; otherwise, empty string.
function checkperson($connection, $number)
{
		
    	$myquery="SELECT name FROM people where idnumber=$number";
		$result=mysql_query($myquery,$connection) 
		   or print "Showhistory query '$myquery' failed because ".mysql_error();
		
		if ($row=mysql_fetch_array($result))	
			return $row[0];
		else
			return '';
} // checkperson




//storeperson: Add this name and number to the 'people' table
function storeperson($connection, $number, $name)
{
		$query="INSERT INTO people VALUES ('$number','$name')";

		$result=mysql_query($query,$connection) 
		   or print "storeperson query '$query' failed because ".mysql_error();
} /* storeperson */

//erasehistory: remove all people, reset the visit counter
function erasehistory($connection)
{
		$query="TRUNCATE TABLE people";
		$result=mysql_query($query,$connection) 
		   or print "Erasehistory query '$query' failed because ".mysql_error();
		   
		$query="UPDATE visits SET count=0 WHERE item='hits'";
		$result=mysql_query($query,$connection) 
		   or print "Erasehistory query '$query' failed because ".mysql_error();
		
} // erasehistory

//checkcount: increase the visit count by one, and tell 'em about it
function checkcount($connection)
{
	$myquery="SELECT count FROM visits where item='hits'";
	$result=mysql_query($myquery,$connection) 
	   or print "Showhistory query '$myquery' failed because ".mysql_error();
	
	if ($row=mysql_fetch_array($result))	
		$hitcount=$row[0];
	else
		print "Visits table had no rows in it. query=$myquery";
	$hitcount++;
	
	$query="UPDATE visits SET count=$hitcount WHERE item='hits'";
	$result=mysql_query($query,$connection) 
	   or print "Checkcount query '$query' failed because ".mysql_error();
	return $hitcount;
}

function displayCandidate($connection){

	$q="SELECT * FROM candidates WHERE items='hits'";	
	$result = mysql_query ($q, $connection) or print "query '$q' failed";
	
	while($row=mysql_fetch_array($result))
							
	{	
			$jessica=$row[0];
			$donald=$row[1];
			$fritz=$row[2];
		
			/* =========== START INDIVIDUAL DISPLAY TOTAL VOTES PAGE, Where the user already voted ============ */
			//use jQuery
			//to make these divs
			//separate pages
			
			//Add Jessica Slideshow within this div
			print "<div><p>Jessica Rabbit: $jessica</p></div>";
			
			//Add Donald Slideshow within this div
			print "<div><p>Donald Duck: $donald</p></div>";
			
			//Add Fritz Slideshow within this div
			print "<div><p>Fritz the Cat: $fritz</div>";
			//
			//
			//
			/* ============= END INDIVIDUAL DISPLAY TOTAL VOTES PAGE, Where the user already voted ============ */
			
	}
	

}

//drawscreen1: Ask the first question
function drawscreen1($connection)
{
	$hitcount=checkcount($connection); // which increments it by one
	/* ==================== BEGIN REGISTRATION PAGE ======================= */
	
	//make this a page in jQuery too
	
	print "<p>You have visited this system $hitcount times";
	
	print "<h3>Registration</h3>";
	print "<form method='post'>";
	print "<p><input name='idnumber' type='text'> Please enter your ID number</p>";
	print "<p><input name='action' type='submit' value='Submit'>";
	
	/* ==================== END REGISTRATION PAGE ======================= */

}





///////// MAIN //////////////

print "<h2>Starter Kit for DIG4104c Project 2</h2>";

// First, open the Database

   $connection=mysql_connect("localhost","as067159","woods")
		or print "connect failed because ".mysql_error();  
	
	 mysql_select_db("as067159",$connection)
		or print "select failed because ".mysql_error();

	//////// THE MAIN ACTION /////////////
	

	$action=$_POST['action'];
	$idnumber=$_POST['idnumber'];
	$name=$_POST['name'];
	$idsession=$_SESSION['idnumber'];
	

	
	if (!$action) //if no action has been taken
	{
	drawscreen1($connection);
	}
	
	
	else if ($action=='Submit')
	{
				$idsession=$_SESSION['idnumber'];
				$idsession = $idnumber;
				$votequery="SELECT vote FROM people where idnumber=$idnumber";
				$result=mysql_query($votequery,$connection) 
				or print "Showhistory query '$votequery' failed because ".mysql_error();
				
				if ($row=mysql_fetch_array($result))	
					$voteresult=$row[0];
				
		if (!$idnumber)
			print "With no ID number, I don't know what to do.";
		else
		{
			$maybename=checkperson($connection, $idnumber);
		
			if ($maybename)
				{
						if ($voteresult == 0) //if user hasnt voted yet
						{
						$idsession=$_SESSION['idnumber'];
						print "<p>This user $maybename, with this ID number($idnumber), is now able to vote </p>";
						print "<form method='post'>";
						
						/* =========== START INDIVIDUAL VOTE PAGES ============ */
						//use jQuery
						//to make these divs
						//separate pages
						
						//Add Jessica Slidehow Here
						print "<div><p><input name='action' type='submit' value='vote Jessica'></p></div>";
						
						//Add Donald Slideshow Here
						print "<div><p><input name='action' type='submit' value='vote Donald'></p></div>";
						
						//Add Fritz Slideshow Here
						print "<div><p><input name='action' type='submit' value='vote Fritz'></p></div>";
						
						//
						//
						//
						/* =========== END INDIVIDUAL VOTE PAGES ============ */

						}
						
						else if ($voteresult == 1) //if user already voted
						{
						//$idsession=$_SESSION['idnumber'];
						print "<p>ID number( $idnumber ), You already voted! Here are the current results: </p>";
						 
						 
						displayCandidate($connection);

						}
				}

			else if ($idnumber > 1049 || $idnumber < 1001) //id number entered goes beyond range
					{
					print "<p>That number is too big or too small, fool! It must be between 1049 and 1001</p>";
					}

			else
				{
				print "<p>This number ($idnumber) is not in the database.";
				}
		} // idnumber block
	}

	
	else if ($action == 'vote Jessica' )//if the user votes for Jessica
	{
			//$idsession=$_SESSION['idnumber'];
			print $idnumber;
			print"ID number( $idsession ) voted for Jessica Rabbit!";
			$myquery="SELECT jessicarabbit FROM candidates where items='hits'";
			$result=mysql_query($myquery,$connection) 
			   or print "Showhistory query '$myquery' failed because ".mysql_error();
			
			if ($row=mysql_fetch_array($result))	
				$jessica=$row[0];
			else
				print "Visits table had no rows in it. query=$myquery";
			$jessica++;
			
			$query="UPDATE candidates SET jessicarabbit=$jessica WHERE items='hits'";
			$result=mysql_query($query,$connection) 
			   or print "Checkcount query '$query' failed because ".mysql_error();
			
			$votequery="UPDATE people SET vote=1 WHERE idnumber=$idsession";
			$voteresult=mysql_query($votequery,$connection) 
			   or print "Checkcount query '$votequery' failed because ".mysql_error();
	}
	
	
	
	else if ($action == 'vote Donald' )//if they vote for donald
	{
			//$idsession=$_SESSION['idnumber'];
			
			print"ID number( $idsession ) voted for Donald Duck!";
			$myquery="SELECT donaldduck FROM candidates where items='hits'";
			$result=mysql_query($myquery,$connection) 
			   or print "Showhistory query '$myquery' failed because ".mysql_error();
			
			if ($row=mysql_fetch_array($result))	
				$donald=$row[0];
			else
				print "Visits table had no rows in it. query=$myquery";
			$donald++;
			
			$query="UPDATE candidates SET donaldduck=$donald WHERE items='hits'";
			$result=mysql_query($query,$connection) 
			   or print "Checkcount query '$query' failed because ".mysql_error();
			
			$votequery="UPDATE people SET vote=1 WHERE idnumber=$idsession";
			$voteresult=mysql_query($votequery,$connection) 
			   or print "Checkcount query '$votequery' failed because ".mysql_error();
	}
	
		else if ($action == 'vote Fritz' ) //if they vote for Fritz
	{
			$idsession=$_SESSION['idnumber'];
			
			print"ID number( $idsession ) voted for Fritz the Cat!";
			$myquery="SELECT fritz FROM candidates where items='hits'";
			$result=mysql_query($myquery,$connection) 
			   or print "Showhistory query '$myquery' failed because ".mysql_error();
			
			if ($row=mysql_fetch_array($result))	
				$fritz=$row[0];
			else
				print "Visits table had no rows in it. query=$myquery";
			$fritz++;
			
			$query="UPDATE candidates SET fritz=$fritz WHERE items='hits'";
			$result=mysql_query($query,$connection) 
			   or print "Checkcount query '$query' failed because ".mysql_error();
			
			$votequery="UPDATE people SET vote=1 WHERE idnumber=$idsession";
			$voteresult=mysql_query($votequery,$connection) 
			   or print "Checkcount query '$votequery' failed because ".mysql_error();
	}

	
	$_SESSION['idnumber']=$idsession;
	
	
?>


</form></body></html>
