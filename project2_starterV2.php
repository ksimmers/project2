<html><head><title>Project 2 Starter Kit:Namecheck</title>
</head><body><form method='post'>
<?php
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

//drawscreen1: Ask the first question
function drawscreen1($connection)
{
	$hitcount=checkcount($connection); // which increments it by one
	
	print "<p>You have visited this system $hitcount times";
	
	print "<h3>Registration</h3>";
	print "<p><input name='idnumber' type='text'> Please enter your ID number</p>";
	//print "<p><input name='name' type='text'> Please enter your name</p>";
	print "<p><input name='action' type='submit' value='Submit'>";
	//print "<p><input name='action' type='submit' value='Clear History'>";
}





///////// MAIN //////////////

print "<h2>Starter Kit for DIG4104c Project 2</h2>";

// First, open the Database


   // $connection=mysql_connect("localhost","dbuser","dbpassword")
   $connection=mysql_connect("localhost","as067159","woods")
		or print "connect failed because ".mysql_error();  
		
    //mysql_select_db("dbname",$connection) // all projects are in ONE db
	 mysql_select_db("as067159",$connection)
		or print "select failed because ".mysql_error();
		
	


	//////// THE MAIN ACTION /////////////

	$action=$_POST['action'];
	$idnumber=$_POST['idnumber'];
	$name=$_POST['name'];
	$action2=$_POST['action2'];
	
	
	
	if ($action=='Submit')
	{
				$votequery="SELECT vote FROM people where idnumber=$idnumber";
				$result=mysql_query($votequery,$connection) 
				or print "Showhistory query '$votequery' failed because ".mysql_error();
				
				if ($row=mysql_fetch_array($result))	
					$voteresult=$row[0];
				//else
					//print "Visits table had no rows in it. query=$voteresult";
	
	
		if (!$idnumber)
			print "With no ID number, I don't know what to do.";
		else
		{
			$maybename=checkperson($connection, $idnumber);
		
			if ($maybename)
				{
				
						if ($voteresult == 0)
						{
							
						print "<p>This user $maybename, with this ID number($idnumber), is now able to vote </p>";
						print "<p><input name='action' type='submit' value='vote Jessica'></p>";
						}
						else if ($voteresult == 1)
						{
						print "<p>You already voted, I can't believe you.</p>";
						}
				}
			
				
			else if ($idnumber > 1049 || $idnumber < 1001)
					{
					print "<p>That number is too big or too small, fool! It must be between 1049 and 1001</p>";
					}
					/* else
					
					storeperson($connection, $idnumber, $name); */

			else
				{
				print "<p>This number ($idnumber) is not in the database.";
				}
		} // idnumber block
	}
	
	
	
	/* else if ($action=='Clear History') 
	{	
		erasehistory($connection);
		print "History was cleared";
	} */
	
	else if ($action == 'vote Jessica' )
	{
		print "cool, you voted for Jessica, and your vote status has now been updated to 1, so you can no longer vote again.";
	}
	else if ($action) // ignore null, as it's probably your first visit. 
		print "What shall I do with action=$action?";

	drawscreen1($connection);
?>
</form></body></html>
