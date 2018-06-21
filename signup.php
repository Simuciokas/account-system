<?php
	//Database connection
	include_once 'includes/dbhPDO.inc.php';
	//Check if inputs exist in database
	function databaseQuery($sql, $conn, array $inputs, $info) {

		//Created and prepared the prepared statement
		try {
			$stmt = $conn->prepare($sql);
		}
		catch(PDOException $e) {
			echo "\nSQL Prepare Error at ".$info."\n";
			echo "\n".$sql."\n";
			echo "\n".$e->getMessage()."\n";
		}

		//Bind parameters to the placeholder
		for ($i = 0; $i < sizeOf($inputs); $i++)
		    $stmt->bindParam($i+1, $inputs[$i], PDO::PARAM_STR, 11);

		//Run parameters inside databse/Query
		$stmt->execute();

		if (strpos($sql, 'SELECT') !== false) {
			//Getting result
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			//Checking if there is a result
			if ($stmt->rowCount() > 0) {
				return $result;
			} else
				return null;
		}
	}

	//Opening a handle to handle inputs
	$handle = fopen ("php://stdin","r");
	$choice = '1';
	while ($choice == '1' || $choice == '2' || $choice == '3') {
		menu:
		echo "\nSelect action";
		echo "\n1. Create an account";
		echo "\n2. Edit an account";
		echo "\n3. Delete an account";
		echo "\nSelect: ";
		$choice = trim(fgets($handle));

		//Creating a new account
		if ($choice == '1') {
			echo "\nAccount Creation!\n";
			//First argument
			$firstName = '';
			//First argument must not be empty, must match /^[a-zA-Z]*$/ regex and cannot be longer than 256 chars
			while ((empty($firstName)) || (!preg_match("/^[a-zA-Z]*$/", $firstName)) || strlen($firstName) > 256 ) {

				echo "\nInput your First Name: ";
				//User's input
				$firstName = trim(fgets($handle));

				//Error messages
				if (empty($firstName))
					echo "\nEmpty!\n";
				else if (!preg_match("/^[a-zA-Z]*$/", $firstName))
					echo "\nInvalid characters!\n";
				else if (strlen($firstName) > 256)
					echo "\nFirst Name is too long!\n";
			}
			//Output of input

			//Second argument
			$lastName = '';
			//Second argument must not be empty, must match /^[a-zA-Z]*$/ regex and cannot be longer than 256 chars
			while ((empty($lastName)) || (!preg_match("/^[a-zA-Z]*$/", $lastName)) || strlen($lastName) > 256 ) {

				echo "\nInput your Last Name: ";
				//User's input
				$lastName = trim(fgets($handle));

				//Error messages
				if (empty($lastName))
					echo "\nEmpty!\n";
				else if (!preg_match("/^[a-zA-Z]*$/", $lastName))
					echo "\nInvalid characters!\n";
				else if (strlen($lastName) > 256)
					echo "\nLast Name is too long!\n";
			}
			//Output of input

			//Third argument
			$email = '';
			$free = false;
			//Third argument must not be empty, must be a valid Email and cannot be longer than 256 chars
			while ((empty($email)) || (!filter_var($email, FILTER_VALIDATE_EMAIL)) || !$free || strlen($email) > 256) {

				echo "\nInput your Email: ";
				//User's input, in lower case
				$email = strtolower(trim(fgets($handle)));

				//Error messages
				if (empty($email))
					echo "\nEmpty!\n";
				else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
					echo "\nInvalid Email!\n";
				else if (strlen($email) > 256)
					echo "\nEmail is too long!\n";

				//Database query to check for taken Email
				else if (empty(databaseQuery("SELECT * FROM users WHERE email=?", $conn, array($email), "signup Email")))
					$free = true;
				else
					echo "\nEmail is taken!\n";
			}
			//Output of input

			//Fourth argument
			$phoneNumber1 = '';
			$taken = false;
			//Fourth argument must not be empty, must match /^\(?\+?([0-9]{1,4})\)? (\d{3})([0-9]{5})$/ regex
			while ((empty($phoneNumber1)) || (!preg_match('/^\(?\+?([0-9]{1,4})\)? (\d{3})([0-9]{5})$/', $phoneNumber1)) || $taken) {
				back:
				echo "\nInput your Phone Number #1: ";
				//User's input
				$phoneNumber1 = preg_replace('/[(+)]/','',trim(fgets($handle)));

				//Error messages
				if (empty($phoneNumber1))
					echo "\nEmpty!\n";
				else if (!preg_match('/^\(?\+?([0-9]{1,4})\)? (\d{3})([0-9]{5})$/', $phoneNumber1))
					echo "\nInvalid characters!\nValid inputs: \n +(370) 12312345\n +370 12312345\n (370) 12312345\n 370 12312345\n";

				//Database query to check for taken phone number 1
				$result = databaseQuery("SELECT * FROM users WHERE phoneNumber1=? OR phoneNumber2=?", $conn, array($phoneNumber1, $phoneNumber1), "PhoneNumber1");
				if (!empty($result)) {
					echo "\nPhone Number is taken\n";
					$taken = true;
				}
				else
					$taken = false;
			}
			//Output of input

			//Fifth argument
			$phoneNumber2 = '';
			$taken = false;
			//Fifth argument must not be empty, must match /^\(?\+?([0-9]{1,4})\)? (\d{3})([0-9]{5})$/ regex
			while ((empty($phoneNumber2)) || (!preg_match('/^\(?\+?([0-9]{1,4})\)? (\d{3})([0-9]{5})$/', $phoneNumber2)) || ($phoneNumber1 == $phoneNumber2) || $taken) {

				echo "\nInput your Phone Number #2: ";
				//User's input, removing '(', ')' and '+' characters
				$phoneNumber2 = preg_replace('/[(+)]/','',trim(fgets($handle)));

				//Error messages
				if (empty($phoneNumber2))
					echo "\nEmpty!\n";
				else if (!preg_match('/^\(?\+?([0-9]{1,4})\)? (\d{3})([0-9]{5})$/', $phoneNumber2))
					echo "\nInvalid characters!\nValid inputs: \n +(370) 12312345\n +370 12312345\n (370) 12312345\n 370 12312345\n";
				else if ($phoneNumber1 == $phoneNumber2)
					echo "\nThis is your #1 number, enter a new one!\n";

				//Database query to check for taken phone number 2
				$result = databaseQuery("SELECT * FROM users WHERE phoneNumber1=? OR phoneNumber2=?", $conn, array($phoneNumber2, $phoneNumber2), "PhoneNumber1");
				if (!empty($result)) {
					echo "\nPhone Number is taken\n";
					$taken = true;
				}
				else
					$taken = false;
			}
			//Output of input

			//Sixth argument
			echo "\nComment: ";
			//User's input
			$comment = trim(fgets($handle));
			//Output of input

			//Output of all inputs
			echo "\n\n\n\nYour inputs:\n\n";
			echo "\nFirst Name: ".$firstName."\n";
			echo "\nLast Name: ".$lastName."\n";
			echo "\nEmail: ".$email."\n";
			echo "\nPhone Number #1: ".$phoneNumber1."\n";
			echo "\nPhone Number #2: ".$phoneNumber2."\n";
			echo "\nComment: ".$comment."\n";
			echo "\n\nDo you want to register an account with this information?  Type 'yes' to confirm: ";

			//Check for confirmation
			if (trim(fgets($handle)) != 'yes') {
				//Return to menu
			    echo "\nRegistration cancelled!";
			    exit;
			}

			echo "\nConfirmed!";

			//Inserting a new account
			databaseQuery("INSERT INTO users (firstName, lastName, email, phoneNumber1, phoneNumber2, comment) VALUES (?, ?, ?, ?, ?, ?)", $conn, array($firstName, $lastName, $email, $phoneNumber1, $phoneNumber2, $comment), "registering an account to database");

			echo "\nSuccesfully registered a new user!\n";

		}

		//Account editing
		if ($choice == '2') {

			//Getting email of account to edit
			emailInput:
			echo "\nInput an email of an account to edit: ";
			$emailtemp = trim(fgets($handle));

			//Database query to find the email
			$result = databaseQuery("SELECT * FROM users WHERE email=?", $conn, array($emailtemp), "Email Search");

			//Error handler
			//Checking if a result exists
			if (empty($result)) {
				echo "\nEmail is not in the database\n";
				goto emailInput;
			}
			echo "\nFound email\n";

			//Get row of found result
			$row = $result;

			//Changing First Name
			firstChange:

			//Getting what to change to
			echo "\nCurrent First name: ".$row['firstName'];
			echo "\nChange to (leave empty if not to change): ";
			$firstToChange = trim(fgets($handle));

			//Error handler
			//Checking if to change to something
			if (empty($firstToChange))
				$firstToChange = $row['firstName'];
			else if (!preg_match("/^[a-zA-Z]*$/", $firstToChange)) {
				echo "\nInvalid characters!\n";
				goto firstChange;
			}
			else if (strlen($firstToChange) > 256) {
				echo "\nFirst Name is too long!\n";
				goto firstChange;
			}

			//Changing Last Name
			lastChange:

			//Getting what to change to
			echo "\nCurrent Last name: ".$row['lastName'];
			echo "\nChange to (leave empty if not to change): ";
			$lastToChange = trim(fgets($handle));

			//Error handler
			//Checking if to change to something
			if (empty($lastToChange))
				$lastToChange = $row['lastName'];
			else if (!preg_match("/^[a-zA-Z]*$/", $lastToChange)) {
				echo "\nInvalid characters!\n";
				goto lastChange;
			}
			else if (strlen($lastToChange) > 256) {
				echo "\nLast Name is too long!\n";
				goto firstChange;
			}

			//Changing Email
			emailChange:

			//Getting what to change to
			echo "\nCurrent Email: ".$row['email'];
			echo "\nChange to (leave empty if not to change): ";
			$emailToChange = trim(fgets($handle));

			//Error handler
			//Checking if to change to something
			if (empty($emailToChange))
				$emailToChange = $row['email'];
			else {

				//Validating email
				if (!filter_var($emailToChange, FILTER_VALIDATE_EMAIL)) {
					echo "\nInvalid Email!\n";
					goto emailChange;
				}

				//Checking length of Email
				else if (strlen($email) > 256) {
					echo "\nEmail is too long!\n";
					goto emailChange;
				}

				//Database query to find if it's taken
				if (!empty(databaseQuery("SELECT * FROM users WHERE email=?", $conn, array($emailToChange), "Email change"))) {
					echo "\nEmail is taken!\n";
					goto emailChange;
				}
			}

			//Changing Phone Number #1
			phone1Change:

			//Getting what to change to
			echo "\nCurrent Phone Number #1: ".$row['phoneNumber1'];
			echo "\nChange to (leave empty if not to change): ";
			$phone1ToChange = trim(fgets($handle));

			//Error handlers
			//Checking if to change to something
			if (empty($phone1ToChange))
				$phone1ToChange = $row['phoneNumber1'];
			else {

				//Validating phone number
				if (!preg_match('/^\(?\+?([0-9]{1,4})\)? (\d{3})([0-9]{5})$/', $phone1ToChange)) {
					echo "\nInvalid characters!\nValid inputs: \n +(370) 12312345\n +370 12312345\n (370) 12312345\n 370 12312345\n";
					goto phone1Change;
				}

				//Database query to find if it's taken
				if (!empty(databaseQuery("SELECT * FROM users WHERE phoneNumber1=? OR phoneNumber2=?", $conn, array($phone1ToChange, $phone1ToChange), "Phone Number #1 change"))) {
					echo "\nPhone Number is taken!\n";
					goto phone1Change;
				}
			}

			//Changing Phone Number #2
			phone2Change:

			//Getting what to change to
			echo "\nCurrent Phone Number #2: ".$row['phoneNumber2'];
			echo "\nChange to (leave empty if not to change): ";
			$phone2ToChange = trim(fgets($handle));

			//Error handlers
			//Checking if to change to something
			if (empty($phone2ToChange))
				$phone2ToChange = $row['phoneNumber2'];

			//Checking if #1 is the same as #2
			else if ($phone2ToChange == $phone1ToChange)	{
				echo "\nPhone Number #2 can't be the same as Phone Number #1!\n";
				goto phone2Change;
			}
			else {

				//Validating phone number
				if (!preg_match('/^\(?\+?([0-9]{1,4})\)? (\d{3})([0-9]{5})$/', $phone2ToChange)) {
					echo "\nInvalid characters!\nValid inputs: \n +(370) 12312345\n +370 12312345\n (370) 12312345\n 370 12312345\n";
					goto phone2Change;
				}

				//Database query to find if it's taken
				if (!empty(databaseQuery("SELECT * FROM users WHERE phoneNumber1=? OR phoneNumber2=?", $conn, array($phone2ToChange, $phone2ToChange), "Phone Number #2 change"))) {
					echo "\nPhone Number is taken!\n";
					goto phone2Change;
				}
			}

			//Changing comment
			commentChange:

			//Getting what to change to
			echo "\nCurrent Comment: ".$row['comment'];
			echo "\nChange to (leave empty if not to change): ";
			$commentToChange = trim(fgets($handle));

			//Checking if to change to something
			if (empty($commentToChange))
				$commentToChange = $row['comment'];

			//Showing what changes to what
			echo "\nPervious and new value\n";
			$toChange = array($firstToChange, $lastToChange, $emailToChange, $phone1ToChange, $phone2ToChange, $commentToChange);
			$i = 0;
			foreach ($row as $value) {
				echo $value." -> ".$toChange[$i]."\n";
				$i++;
			}

			//Get confirmation from user
			echo "\nAre you sure you want to edit this account?  Type 'yes' to confirm: ";
			$confirm = trim(fgets($handle));

			//Checking confirmation
			if ($confirm != 'yes') {
				echo "\nEdit cancelled\n";
				goto menu;
			}

			//Database query to update
			databaseQuery("UPDATE users SET firstName=?, lastName=?, email=?, phoneNumber1=?, phoneNumber2=?, comment=? WHERE email=?", $conn, array($firstToChange, $lastToChange, $emailToChange, $phone1ToChange, $phone2ToChange, $commentToChange, $emailtemp), "Updating user");
			echo "\nSuccessfully updated the user!\n";

		}

		//Account deletion
		if ($choice == '3') {

			//Getting email of account to delete
			emailInputDelete:
			echo "\nInput an email of an account to delete: ";
			$emailtemp = trim(fgets($handle));

			//Searching for email in database
			$result = databaseQuery("SELECT * FROM users WHERE email=?", $conn, array($emailtemp), "Email Search");

			//Error handler
			if (empty($result)) {
				echo "\nEmail is not in the database\n";
				goto emailInputDelete;
			}

			echo "\nEmail found!\n";

			//Confirmation to delete account
			echo "\nAre you sure you want to delete this account?  Type 'yes' to confirm: ";
			$confirm = trim(fgets($handle));

			//Checking confirmation
			if ($confirm != 'yes') {
				echo "\nDeletion cancelled!\n";
				goto menu;
			}

			//Deleting account
			echo "\nDeleting account!\n";
			databaseQuery("DELETE FROM users WHERE email=?", $conn, array($emailtemp), "Email Deletion");
			echo "\nSuccessfully deleted the account!\n";
		}
	}