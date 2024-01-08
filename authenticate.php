<?php
include "index.php";

// Get the values from the form
$id = $_POST['id'];
$pass = $_POST['pwd'];

$sql = "SELECT * FROM users WHERE id LIKE '$id' and password LIKE '$pass'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result)===1){
    $row = mysqli_fetch_assoc($result);
    if($row['id'] === $id && $row['password']===$pass){
        echo "login successful!"; 
    }else{
        header("Location: index.html?error=incorrect username or password");
        exit();
    }

}else{
    header("Location: index.html?error=incorrect username or password");
    exit();
}
?>