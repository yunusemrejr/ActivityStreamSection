<?php

session_start();

function auth($username,$password){
    
$storedHash='$2a$12$G0XSN0K6cpA5zwQpKQgFmeXf3DeW.8TBql5SNYKkH32SDsv1QL/ei'; 

if(password_verify($username,$storedHash)){
    return true;
}
return false;
}

function connectToMySQL(){
    $server= 'localhost';
    $db= 'blablaDB';
    $username='theGoodAdmin';
    $password='supercoolpassword';

    $conn = new mysql($server,$user,$password,$db);

    if($conn->connect_error){
        die('TECHNICAL ERROR');
    }return $conn;
}

function addActivityToList($date,$time,$title,$body){
    $conn = connectToMySQL();

    $statement=$conn->prepare("INSERT INTO activitylogs (date,time,title,body) VALUES (?,?,?,?)");
    $statement->bind_param("ssss",$date,$time,$title,$body);
    $result=$statement->execute();

    $statement->close();
    $conn->close();

    return $result;
}


function deleteActivityFromList($id){
    $conn=connectToMySQL();
    $statement=$conn->prepare("DELETE FROM activitylogs WHERE id = ?");
    $statement->bind_param("i",$id);
    $result=$statement->execute();
    
    $statement->close();
    $conn->close();
    return $result;
}


if(isset($_POST['login'])){
    $username=$_POST['username'];
    $password=$_POST['password'];

    if(authenticate($username,$password)){
        $_SESSION['username'];
        header('Location: admin.php');
        exit;
    }


    else{
        $error="Wrong credentials!";
    }

}


if(isset($_SESSION['username'])){
    ?>

<!-- HTML code for the authenticated section -->
<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='stylesADMIN.css'>
    <title>Admin - Activity Stream</title>
  
</head>
<body>
    <h1>Admin - Activity Stream</h1>

     <!-- Form for adding a new activity -->
     <h2>Add Activity</h2>
    <form method="POST" action="admin.php">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        <br>
        <label for="body">Body:</label>
        <textarea name="body" id="body" required></textarea>
        <br>
        <input type="submit" name="add" value="Add">
    </form>



        <!-- Form for deleting an activity -->
        <h2>Delete Activity</h2>
    <form method="POST" action="admin.php">
        <label for="id">Activity ID:</label>
        <input type="text" name="id" id="id" required>
        <br>
        <input type="submit" name="delete" value="Delete">
    </form>


<?php
if(isset($_POST['add'])){

$date=date('Y-m-d');
$time=date('H:i:s');
$title=$_POST['title'];
$body=$_POST['body'];

$result=addActivityToList($date,$time,$title,$body);

if($result){
    echo "<p>new log added</p>";
}else{
    echo "error";
}

}

?>

</body></html>


<?php
}
else{

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php

    if(isset($error)){
        echo "$error";
    }

    ?>

    <form method='post' action='admin.php'>
        <label for='username'>Username:</label>
        <input type='text' name='username' id='username' required>
        <br>
        <label for="password">Password:</label>
        <input type='password' name='password' id='password' required>
        <br>
        <input type='submit' name='login' value='login'>
</form>

</body>
</html>

<?php
}
?>

