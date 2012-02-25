<?php session_start();


function printHeading()
{
print "<html><head><title>Toon Town Mayoral Race</title>

<!-- include css -->



</head>
<body>";
print "<form method='post'>";
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
		$query="INSERT INTO people VALUES ('$number','$name','0')";

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

function displayCandidate($connection)
{

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
			
			print "<div><p>Jessica Rabbit: votes = $jessica</p></div>";
			print '<img src="jrabbit.jpg" width=250px;height=300px;">';
			
			//Add Donald Slideshow within this div
			
			print "<div><p>Donald Duck: votes = $donald</p></div>";
			print '<img src="donald.gif" width=250px;height=300px;">';
			
			//Add Fritz Slideshow within this div
			
			print "<div><p>Fritz the Cat: votes = $fritz</div>";
			print '<img src="fritz.jpg" width=250px;height=300px;">';
			//
			homebutton();
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
	
	
	
	print "<h3>Registration</h3>";
	
	print "<p><input name='idnumber' type='text'> Please enter your ID number</p>";
	print "<p><input name='name' type='text'> Please enter first name</p>";
	print "<p><input name='action' type='submit' value='Submit'>";
	print "<p>Alternatively, you may also view the Public Poll:</p>";
	print "<p><input name='action' type='submit' value='Public Poll'></p> ";
	print "<p>This site has had $hitcount visitors";
	
	/* ==================== END REGISTRATION PAGE ======================= */

}//drawscreen

function getvotes()
{
						//Add Jessica Slidehow Here
						print '<img src="jrabbit.jpg" width=250px;height=300px;">';
						print "<p>Campaign slogan: Who cares what the position entails? I'm sexy.</p>";
						print "<div><p><input name='action' type='submit' value='vote Jessica'></p></div>";
						
						//Add Donald Slideshow Here
						print '<img src="donald.gif" width=250px:height=300px;">';
						print "<p>Campaign slogan: @#!% #@! #?@%!!!</p>";
						print "<div><p><input name='action' type='submit' value='vote Donald'></p></div>";
						
						//Add Fritz Slideshow Here
						print '<img src="fritz.jpg" width=250px;height=300px;">';
						print "<p>Campaign slogan: Party down with the craziest and most oversexed feline in town!";
						print "<div><p><input name='action' type='submit' value='vote Fritz'></p></div>";
						
						//
						//
						//
						/* =========== END INDIVIDUAL VOTE PAGES ============ */
}//getvotes

function pollbutton()
{
	print "<p><input name='action' type='submit' value='Public Poll'></p> ";
}
function homebutton()
{
   	print "<p><input name='action' type='submit' value='home'></p> ";	
}



///////// MAIN //////////////

print "<h2>Toon Town Mayoral Race Vote Page</h2>";

// First, open the Database
	$connection=mysql_connect("localhost","ke994988","simmers")
   //$connection=mysql_connect("localhost","as067159","woods")
   //$connection=mysql_connect("localhost","ke783268","durand")
		or print "connect failed because ".mysql_error();  
		
	mysql_select_db("ke994988",$connection)
	//mysql_select_db("as067159",$connection)
	 	// mysql_select_db("dig4104",$connection)

		or print "select failed because ".mysql_error();

	//////// THE MAIN ACTION /////////////
	

	$action=$_POST['action'];
	$idnumber=$_POST['idnumber'];
	$name=$_POST['name'];
	$idsession=$_SESSION['idnumber'];
	
#print "recovered idsession=$idsession";
	
	if (!$action || $action=='home') //if no action has been taken
	{
	drawscreen1($connection);
	}
	
	
	else if ($action=='Submit')
	{
				//$idsession=$_SESSION['idnumber'];
				$idsession = $idnumber;
				$votequery="SELECT vote,name FROM people where idnumber=$idnumber";
				$result=mysql_query($votequery,$connection) 
				or print "Showhistory query '$votequery' failed because ".mysql_error();
				
				if ($row=mysql_fetch_array($result))	
					$voteresult=$row[0];
				
		if (!$idnumber)
			print "Please enter a name and ID number.";
		else
		{
			$maybename=checkperson($connection, $idnumber);
		
			if ($maybename)
				{
						if ($voteresult == 0) //if user hasnt voted yet
						{
						//$idsession=$_SESSION['idnumber'];
						print "<p>Welcome back $maybename. Your ID number $idnumber has been recorded and you may cast your vote when ready.</p>";
						#####I am not sure exaclty why the above line is included, because it has no functionality, however, when I commented the line out, it affected 
						#####the rest of the already operational functionality. The line is being left intact, though it never shows up on the page.#####
						
						/* =========== START INDIVIDUAL VOTE PAGES ============ */
						//use jQuery
						//to make these divs
						//separate pages
						
						getvotes();
						//pollhomebuttons();

						}
						
						else if ($voteresult == 1) //if user already voted
						{
						//$idsession=$_SESSION['idnumber'];
						print "<p>Hello $maybename. Your ID Number $idnumber has already been used to cast a vote. Here are the current results: </p>";
						 
						 
						displayCandidate($connection);

						}
				}

			else if ($idnumber > 1049 || $idnumber < 1001) //id number entered goes beyond range
					{
					print "<p>The number you entered is not a valid ID number. Please enter a number between 1001 and 1049.</p>";
					homebutton();
					}
					
			else if (!$name)
					{
					print "<p>Please enter a name.</p>";
					homebutton();	
					}

			else
				{
				print "<p>Your name $name, and number $idnumber, are not in the database. They will be added now.";
				//print "<p>Your name and number are being added to the voter database."; cancelled excess statement
				storeperson($connection, $idnumber, $name, "0");
				print "<p>Thank you $name. Your !D number $idnumber is valid. You may now cast your vote.</p>";
				getvotes();
				}
		} // idnumber block
	}

	
	else if ($action == 'vote Jessica' )//if the user votes for Jessica
	{
			//$idsession=$_SESSION['idnumber'];
			print"Thank you for your vote $idsession . You have voted for Jessica Rabbit!";
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
			  
			  pollbutton();
			  homebutton();
	}
	
	
	
	else if ($action == 'vote Donald' )//if they vote for donald
	{
			//$idsession=$_SESSION['idnumber'];
			
			print"Thank you for your vote $idsession . You have voted for Donald Duck!";
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
			  pollbutton();
			  homebutton();

	}
	
		else if ($action == 'vote Fritz' ) //if they vote for Fritz
	{
			//$idsession=$_SESSION['idnumber'];
			
			print"Thank you for you vote $idsession . You have voted for Fritz the Cat!";
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
			   
			pollbutton();
			homebutton();
	}
	
	else if ($action == 'Public Poll')
	{
		displayCandidate($connection);
	}

	#logprint("about to save idnumber in session:$idsession",4);
	
	$_SESSION['idnumber']=$idsession;
	
	
?>


</form></body></html>
