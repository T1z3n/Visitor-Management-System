
<?php
include("connect.php");
include("function.php");
?>

<?php
// define variables and set to empty values
$UserId = $Name = $Email = $Phone = $HostName = $HostEmail = $HostPhone = $Depart = "ccc";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $UserId = test_input($_POST["UserId"]);
  $Email = test_input($_POST["Email"]);
}

function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<?php
date_default_timezone_set('Asia/Kolkata');

$Arrive = "";




if ($Email != $Phone) {
  $sql1 = "SELECT * FROM visitors WHERE UserId = '$UserId' and Email = '$Email' ";

  $sql1 = mysqli_query($conn, $sql1) or die(mysqli_error($conn));

  if ($sql1->num_rows == 0) {
    $message = "Die eingegebenen Daten stimmen nicht überein. Überprüfe deine Mails";
    echo "<script type='text/javascript'>;alert('$message');window.location.href='index.html';</script>";
  }

  $row = $sql1->fetch_assoc();


  $Username = $row["Username"];
  $Arrive = $row["Arrival"];
  $Depart = $row["Departure"];
  $Phone = $row["Phone"];
  $Address = $row["Address"];
  $HostName = $row["HostName"];

  if ($Depart != "Check-In") {
    $message = "Du bist bereits eingecheckt";
    echo "<script type='text/javascript'>;alert('$message');window.location.href='index.html';</script>";
  }
  $Depart = date('Y/m/d H:i:s');
  // echo $sql1->num_rows ."<br>";

  //echo $Username.$Arrive.$Depart.$Phone;

  $sql3 = "UPDATE visitors SET Departure='$Depart'  WHERE UserId='$UserId' ";

  // echo $sql3." cvcxv ".$Depart;

  if (mysqli_query($conn, $sql3) == TRUE) {
    $message = "Vielen Dank. Überprüfe deine Mails um weiterzumachen.";

    //echo $message, "Your UserID is ", $UserId;
    send_email_depart($Username, $UserId, $Email, $Phone, $Arrive, $Depart, $HostName, $Address);
    echo "<script type='text/javascript'>;alert('$message');
    window.location.href='index.html';
    </script>";
  } else {
    /*printf("error: %s\n", mysqli_error($conn)); //very important to check error
        */
    $message = "Verbindung mit dem Server konnte nicht hergestellt werden, bitte versuche es später erneut.";
    echo "<script type='text/javascript'>;alert('$message');window.location.href='index.html';</script>";
  }
} else {
  $message = "E-Mail od. Telefonnummer fehlerhaft.";
  echo "<script type='text/javascript'>;alert('$message');window.location.href='index.php';</script>";
}


?>
