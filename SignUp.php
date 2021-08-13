<?php
  session_start();
  require('./PHP/common_files.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="SignUpStyle.css">


	<title>Document</title>
</head>
<body>
  	<section class="container-fluid border border-success">
 		 <div class="SignUp">
    		<form class="form-container d-flex justify-content-center align-items-center" method="POST" >
    			<div class="form-group">
				    <h1 style="padding-bottom: 1em; text-align: center;">Sign Up</h1>
					<label for="name-details"><b>Enter Name</b></label>
                    <div class="input-group" id="name-details">
                        
                        <!-- declaration for first field -->
                        <input type="text" id="firstname" class="form-control" placeholder="First Name" name="firstName" required/>
                        <!-- declaration for second field -->
                        <input type="text" class="form-control" placeholder="Last Name" name="lastName" required/>
                    </div>
                    <!-- <label for="firstname"><b>User name</b></label>
				    <input type="text" placeholder="Enter username" class="Input-first mb-2 d-block" name="username"  required> -->
                    <label for="account-type" class="d-block"><b>Select Type</b></label>
                    <div class="form-check form-check-inline" id="account-type">
                        <input class="form-check-input" type="radio" name="type" id="type1" value="student">
                        <label class="form-check-label" for="type1">Student</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="type2" value="guide">
                        <label class="form-check-label" for="type2">Guide</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="type3" value="admin">
                        <label class="form-check-label" for="type3">Project Co-ordinator</label>
                    </div>

              <!-- <input class="form-check-input" type="radio" name="type" id="flexRadioDefault1" value="admin">
              <label class="form-check-label" for="flexRadioDefault1">
                
              </label>

              <input class="form-check-input" type="radio" name="type" id="flexRadioDefault2" value="student">
              <label class="form-check-label" for="flexRadioDefault2">
               Student
              </label>

              <input class="form-check-input" type="radio" name="type" id="flexRadioDefault3" value="guide">      
                        <label class="form-check-label" for="flexRadioDefault3">  Guide </label>


 -->
					    <label for="email" class="d-block"><b>Email</b></label>
					    <input type="text" placeholder="Enter Email" class=" form-control Input-second mb-2 d-block" name="email" required>

					    <label for="psw"><b>Password</b></label>
					    <input type="password" placeholder="Enter Password" class=" form-control Input-third mb-2 d-block" id="psw" name="password" required>

             

					    <!-- <label for="psw-repeat"><b>Repeat Password</b></label> 
					    <input type="password" placeholder="Repeat Password" class="Input-fourth mb-3 d-block" name="psw-repeat" required> -->
					  
   
    <div class="FormFooter"> 
    	<div class="text-center">
      <button type="submit" class="signupbtn btn btn-primary" name="signup-submit">Sign Up</button>
    </div>
  </div>
</form>
	
</body>
</html>
<?php

  // include("functions.php");
  include('./PHP/connect.php');

  if(isset($_POST['signup-submit']))
  {
    //something was posted
    $firstName = $_POST['firstName'];
    $lastName=$_POST['lastName'];
    $user_name=$firstName.' '.$lastName;
    $email = $_POST['email'];
    $type =  $_POST['type'];
    $password = $_POST['password'];

    $sql = "INSERT INTO user_info (email,password,type,username) VALUES('". $email ."','". $password ."','". $type ."','". $user_name ."')";


    if ((mysqli_query($connection, $sql))){
        // echo("Done");


      $id=mysqli_insert_id($connection);
      // echo($id);
       if(!empty($type))
       {
        $newuserid = $type[0].strval($id);
       }

        $query="UPDATE `user_info` set `user_id`='". $newuserid ."' where `id`='". $id ."'";
        $run_query=mysqli_query($connection,$query);
        if (!$run_query) 
        {
             echo mysqli_error($connection);
        }
        else
        {
            $insert='';
            switch ($type[0]) {
                case 's':
                    $insert="INSERT INTO student (student_id) VALUES ('". $newuserid ."')";
                    break;
                case 'g':
                    $insert="INSERT INTO guide (guide_id) VALUES ('". $newuserid ."')";
                    break;
                // case 'a':
                //     $insert="INSERT INTO admin_details (admin_id) VALUES ('". $newuserid ."')";
                //     break;
            }
            if(mysqli_query($connection, $insert)){
                mkdir('./Uploads/'.$newuserid,0777,true);
                $_SESSION['user_id']=$newuserid;
                echo'<script>location.href="./account.php?userid='.$newuserid.'"</script>';
            }else{
                echo(mysqli_error($connection));
            }
            // echo "'your user name is and user id is '.$newuserid.'" ;
        }   
  
      //folder banana hai inside Uploads
      //folder  name=userid
      // take them to other page

      //display green Modal

      

      // echo "New record created successfully";
    }else{
        echo(mysqli_error($connection));
    }
  }else{
    // echo "Error";
  }
?>

