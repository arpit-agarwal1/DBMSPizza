<?php
    $host = "host = localhost";
    $port = "port = 5432";
    $dbname = "dbname = speedzadb";
    $credentials = "user = postgres password=enteryourpass";

    $db = pg_connect("$host $port $dbname $credentials");
    // if (!$db)
    //     echo "Error Error \n";
    // else
    //     echo "Connection successful \n";
    
    $username = "";
    $email = "";
    $errors = array();

    if(isset($_POST['reg_user'])){
        $name = pg_escape_string($db, $_POST['name1']);
        // $username = pg_escape_string($db, $_POST['username']);
        $email = pg_escape_string($db, $_POST['email']);
        $phone = pg_escape_String($db, $_POST['phone']);
        $password_1 = pg_escape_string($db, $_POST['password_1']);
        $password_2 = pg_escape_string($db, $_POST['password_2']);
        // if (empty($username)) { array_push($errors, "Username is required"); }
        if (empty($phone)) { array_push($errors, "Phone is required"); }
        if (empty($email)) { array_push($errors, "Email is required"); }
        if (empty($password_1)) { array_push($errors, "Password is required"); }
        if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
    }

    $user_check_query = "SELECT * FROM customer WHERE customeremail='$email' OR customerphone='$phone' LIMIT 1";
    $result = pg_query($db, $user_check_query);
    $user = pg_fetch_assoc($result);

    // if ($user) { // if user exists
    // if ($user['username'] === $username) {
    //   array_push($errors, "Username already exists");
    // }
    // print_r($user);

    if ($user['customerphone'] === $phone) {
      array_push($errors, "Phone number already exists");
    }

    if ($user['customeremail'] === $email) {
      array_push($errors, "email already exists");
    }

  if (count($errors) == 0){
      $password = md5($password);

      $insertUser = "INSERT INTO Customer (customername, customeremail, customerphone, customerpassword)
                    VALUES ('$name', '$email', '$phone', '$password')";
      pg_query($db, $insertUser);
      $_SESSION['email'] = $username;
      $_SESSION['success'] = "You are now logged in";
      header('location: index.html');
  }
}

  if (isset($_POST['login_user'])) {
    $username = pg_escape_string($db, $_POST['email']);
    $password = pg_escape_string($db, $_POST['password']);
  
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {  
        $password = md5($password);
        // print_r($password);
        $query = "SELECT * FROM customer WHERE customeremail='$username' AND customerpassword='$password'";
        $results = pg_query($db, $query);
        $user = pg_fetch_assoc($results);
        print_r($user);
        if (pg_num_rows($results) >= 1) {
          $_SESSION['email'] = $username;
          $_SESSION['success'] = "You are now logged in";
          header('location: homepage.html');
        }
        else {
            array_push($errors, "Wrong email/password combination");
        }
    }
  }

// LOGIN USER
// if (isset($_POST['login_user'])) {
//   $email = pg_escape_string($db, $_POST['email']);
//   $password = pg_escape_string($db, $_POST['password']);

//   if (empty($email)) {
//       array_push($errors, "Email is required");
//   }
//   if (empty($password)) {
//       array_push($errors, "Password is required");
//   }

//   if (count($errors) == 0) {  
//       $password = md5($password);
//       $query = "SELECT * FROM customer WHERE customeremail='$email' AND customerpassword='$password'";
//       $results = pg_query($db, $query);
//       if (pg_num_rows($results) >= 1) {
//         $_SESSION['email'] = $username;
//         $_SESSION['success'] = "You are now logged in";
//         header('location: index.html');
//       }
//       else {
//           array_push($errors, "Wrong email/password combination");
//       }
//   }
// }

?>