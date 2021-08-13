<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
        $userID='';
        session_start();
        require('./PHP/common_files.php');
        require('./PHP/connect.php');
        if(isset($_GET['userid'])){
            $userID=$_GET['userid'];
        }
    ?> 
	<title>Account</title>
</head>
<body>
    <!-- Check if account id==loggedin user id => if true display pencils else no -->
    <?php
        if(!isset($_SESSION['user_id'])){
            echo "<script> location.href='./LoginPage.php'; </script>";
        }
        require('./PHP/header.php')
    ?>
    <?php
        $selectDetails="SELECT * FROM `user_info` WHERE `user_id`='". $userID ."'";
        $queryResult = mysqli_query($connection, $selectDetails);
        $userDetails = mysqli_fetch_assoc($queryResult);
        $selectStudentDetails="SELECT `batch` FROM `student` WHERE `student_id`='". $userID ."'";
        $studentQueryResult = mysqli_query($connection, $selectStudentDetails);
        $studentDetails = mysqli_fetch_assoc($studentQueryResult);
        // echo($userDetails);
    ?>
    <!-- Profile details update -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col">
                                <label for="first-name">First Name</label>
                                <input type="text" class=" form-control" placeholder="First Name" id="first-name" name="first-name" value="<?php 
                                if(isset($userDetails["username"])){
                                    $text=explode(" ",$userDetails["username"]);
                                    echo $text[0];
                                }else{
                                    echo "";
                                }
                            ?>">
                            </div>
                            <div class="col">
                                <label for="last-name">Last Name</label>
                                <input type="text" class="form-control" placeholder="Last Name" id='last-name' name="last-name" value="<?php 
                                if(isset($userDetails["username"])){
                                    $text=explode(" ",$userDetails["username"]);
                                    echo $text[1];
                                }else{
                                    echo "";
                                }
                            ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="profile-branch" class="col-form-label">Department</label>
                            <input type="text" class="form-control" id="profile-branch" placeholder="Branch" name="branch" value="<?php 
                                if(isset($userDetails["department"])){
                                    echo $userDetails["department"];
                                }else{
                                    echo "";
                                }
                            ?>">
                        </div>
                        <?php
                            $batchVal='';
                            if(isset($studentDetails["batch"])){
                                $bacthVal=$studentDetails["batch"];
                            }else{
                                $bacthVal="";
                            }
                            if($userDetails["type"]==="student"){
                                echo'<div class="form-group">
                                    <label for="pass-year" class="col-form-label">Passing Year:</label>
                                    <input type="text" class="form-control" id="pass-year" name="pass-year" placeholder="eg: 2022"
                                    value="'.$batchVal.'">
                                    </div>';
                            }
                        ?>
                        <!-- <div class="form-group">
                            <label for="profile-location" class="col-form-label">Location:</label>
                            <input type="text" class="form-control" id="profile-location">

                        </div> -->
                        <!-- <div class="row">
                            <div class="col">
                                <label for="branch">Branch</label>
                                <input type="text" class="form-control" placeholder="Branch" id="branch">
                            </div>
                            <div class="col">
                                <label for="pass-year">Passing Year</label>
                                <input type="number" class="form-control" placeholder="Passing Year" id='pass-year'>
                            </div>
                        </div> -->
                        <button type="button" type="submit" class="btn btn-primary" name="details-submit">Update Details</button>
                    </form>
                </div>
              <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                
              </div>
          <!-- </form> -->
            </div>
        </div>
    </div>
    <!-- Only 1 project for student -->
    <!-- Add to guide's account -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProjectModalLabel">Add/Edit Project Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="project-name" class="col-form-label">Project Name</label>
                    <input type="text" class="form-control" id="project-name">
                </div>
                <div class="form-group">
                    <label for="project-guide" class="col-form-label">Guide Name</label>
                    <input type="text" class="form-control" id="project-guide">
                </div>
                <div class="form-group">
                    <label for="member-count" class="col-form-label">Group Member count</label>
                    <input type="text" class="form-control" id="member-count" placeholder="Member count (excluding you)">
                    <p id="error-text"></p>
                </div>
                <div class="row">
                    <div class="col member-input-container hide">
                        <label for="member-1">Member Name</label>
                        <input type="text" class=" form-control" placeholder="Member 1 Name" id="member-1">
                    </div>
                    <div class="col member-input-container hide">
                        <label for="member-2">Member Name</label>
                        <input type="text" class="form-control" placeholder="Member 2 Name" id='member-2'>
                    </div>
                </div>
                <!-- <div class="form-group member-input-container">
                    <label for="project-members" class="col-form-label">Group Members</label>
                    <input type="text" class="form-control" id="project-members">
                </div> -->
                <div class="form-group">
                    <label for="project-desc" class="col-form-label">Project Description</label>
                    <textarea class="form-control" id="project-desc"></textarea>
                </div>
                
                
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Update Details</button>
          </div>
        </div>
      </div>
    </div>
    
	<div class="container-fluid account-section mt-3">
        <div class="row my-4">
	        <div class="col-lg-4 border-right border-default">
                <div class="container">
                    <img src="<?php
                       echo $userDetails['image'];
                    ?>" alt="Profile" class="img-fluid rounded d-block mx-auto" style="height: 30vh; position:relative;">
                    <!-- Tablet Updates needed -->
                    <div class="profile-details my-2 container mx-auto" style="padding-bottom: 5em;">
                        <div class="d-flex justify-content-center">
                            
                            <div class="text-center pr-lg-3 pl-lg-0 pl-5">
                                <h5><?php
                                    echo $userDetails['username']; //19101A2001
                                ?></h5>
                                <!-- <span><span class="fa fa-map-marker-alt"></span> Mumbai</span> -->
                                <span class="d-block">
                                    <?php
                                        echo $userDetails['department'];
                                    ?>
                                </span>
                                <?php
                                    if($userDetails["type"]==="student"){
                                        if($studentDetails["batch"]==0){
                                            echo "<p>Enter Batch</p>";
                                        }else{
                                            echo "<p>Batch of </p>".$studentDetails['batch'];
                                        }
                                    }
                                 ?>
                                <?php
                                        //check session variable
                                        if($_SESSION["user_id"]===$userID){
                                        // Display Pencil for Editing
                                            echo'<button class="btn btn-outline-secondary align-self-start my-2" data-toggle="modal" data-target="#editProfileModal"><i class="fas fa-plus"></i> Edit Details</i></button>';
                                        }
                                    ?>
                            </div>
                            
                        </div>
                        
                    </div>
                </div>     
           </div>
           <div class="col-md-8">
               <div class="container">
                   <!-- <h2>Account Details</h2>  -->
                   
                   <div class="projects">
                        <div class="d-flex justify-content-between align-items-center py-3">
                            <h2>Project Details</h2>
                            
                        </div>
                        <div class="container">
                            <?php
                                $check="";                            
                                $selectID="SELECT `group_id` FROM `student` WHERE `student_id`='" .$userID. "'";
                                $selectResult = mysqli_query($connection, $selectID);
                                if(mysqli_num_rows($selectResult) > 0){
                                    $details = mysqli_fetch_assoc($result);
                                    $check=is_null($details['group_id']);
                                }
                                else{
                                    //Do not Display any projects
                                    // echo "No Rows Found";
                                }
                                // Type=student
                                if($_SESSION["user_id"]===$userID){
                                    echo 
                                    '<button class="btn btn-outline-secondary my-2" data-toggle="modal" data-target="#addProjectModal">
                                        
                                        <i class="fas fa-plus"></i>
                                        Add Project
                                    </button>';
                                }
                            ?>
                           <!-- <div class="card mb-4">
                                <div class="card-header">
                                   BookBarn: Web Based Book Recommendation and E-commerce System
                                </div>
                                <div class="card-body">
                                   <h5 class="card-title">Guide Name: Deepali Shrikhande</h5>
                                    <p class="card-text">
                                        BookBarn is a website that would allow users to buy, sell as well as rent books all at a single place. The website would further recommend books to the users based on their
                                        buying/search history and based on similar user's preferences.
                                    </p>
                                </div>
                                <div class="card-body">
                                    <p>Group Members: Rohana Survase, Sayali Khamgaonkar</p>
                                    <--<p>Technology used: HTML, CSS, Javascript, SQL, PHP, Python. --
                                    </p>
                                    <a href="#" class="btn btn-primary">View Project</a>
                                </div>
                           </div>
                           <div class="card mb-2">
                                <div class="card-header">
                                   BookBarn: Web Based Book Recommendation and E-commerce System
                                </div>
                                <div class="card-body">
                                   <h5 class="card-title">Guide Name: Deepali Shrikhande</h5>
                                    <p class="card-text">
                                        BookBarn is a website that would allow users to buy, sell as well as rent books all at a single place. The website would further recommend books to the users based on their
                                        buying/search history and based on similar user's preferences.
                                    </p>
                                </div>
                                <div class="card-body">
                                    <p>Group Members: Rohana Survase, Sayali Khamgaonkar</p>
                                    <-- <p>Technology used: HTML, CSS, Javascript, SQL, PHP, Python. ->
                                    </p>
                                    <a href="#" class="btn btn-primary">View Project</a>
                                </div>
                           </div> -->
                        </div>         
                   </div>
                   <?php
                       if($_SESSION["user_id"]===$userID){
                           echo'<form method="POST" class="container d-flex justify-content-end">
                               <button class="btn btn-outline-secondary" name="logout" type="submit">Log Out</button> 
                           </form>';
                        }
                    ?>
                </div>
           </div>
            
        </div>
    </div>
	<!--<div class="col col-lg-9 border border-danger">
		
	</div> -->
	<!--</div> -->
	<!-- </div> -->
    <?php
        require('./PHP/footer.php')
    ?>

    <script>
        let count=document.getElementById('member-count');
        count.addEventListener('input', ()=>{
            document.getElementById('error-text').innerText=``;
            //Value
            let count_value=parseInt(count.value);
            //Class refer
            let container_ref=document.getElementsByClassName('member-input-container')
            //So atleast 2 members
            if(count_value>0 && count_value<=2){

                for(let i=1;i<=count_value;i++){
                    container_ref[i-1].classList.remove('hide');
                }
            }
            else{
                for(let i of container_ref){
                    
                    if(!i.classList.contains('hide')){
                        i.classList.add('hide');
                    }
                } 
                if(count_value>2){
                    document.getElementById('error-text').innerText=`Group Member count cannot be more than 2`;
                    // $("#error-modal").modal()
                }

            }
        });
    </script>
    <?php
    if(isset($_POST['logout'])){
        unset($_SESSION['user_id']);
        // $_SESSION['loggedin']=false;
        session_destroy();
        echo '<script> location.href="./index.php";</script>';
        // header('Location:./index.php'); 
        exit;
    }
    ?>
</body>
</html>

<!-- For Profile Details -->
<?php 
    if (isset($_POST['details-submit'])) {
        $firstName = $_POST['first-name'];
        $lastName=$_POST['last-name'];
        $name=$firstName.' '.$lastName;     
        $dept=$_POST['department'];
        $year='';
        $query="UPDATE `user_info` SET `username`='". $name ."', `department`='". $dept ."', WHERE `user_id`='". $userID ."'";
        if (mysqli_query($connection, $query)) {
          echo "Record updated successfully";
        } else {
          echo "Error updating record: " . mysqli_error($connection);
        }
        if($userDetails["type"]==="student"){
            $year=$_POST['pass-year'];
            $studentQuery="UPDATE `student` SET `batch`='". $year ."' WHERE `student_id`='". $userID ."'";
            if (mysqli_query($connection, $studentQuery)) {
              echo "<script>
                $('#editProfileModal').modal('hide');
              </script>";
            } else {
              echo "Error updating record: " . mysqli_error($connection);
            }
        }
        // $studentQuery="UPDATE `student` SET ``"
    }else{
        echo "Button Error";
    }
?>
  