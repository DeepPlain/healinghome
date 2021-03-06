<!DOCTYPE html>
<?php
session_start();
include("config.php");
$target_file = "";
if(basename($_FILES["files"]["name"][0]) != '') {
  $target_dir = "uploads/";
  $target_file = $target_dir.microtime()._.basename($_FILES["files"]["name"][0]);
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["files"]["tmp_name"][0]);
      if($check !== false) {
          //echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          echo "File is not an image.";
          $uploadOk = 0;
      }
  }
  // Check if file already exists
  /*if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
  }*/
  while (file_exists($target_file)) {
      $target_file = md5(microtime()).$target_file;
  }
  $target_file = preg_replace("/\s+/","",$target_file); //공백 제거

  // Check file size
  if ($_FILES["files"]["size"][0] > 1000000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
  }
  // // Allow certain file formats
  // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  // && $imageFileType != "gif" ) {
  //     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  //     $uploadOk = 0;
  // }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["files"]["tmp_name"][0], $target_file)) {
        //  echo "The file ". basename( $_FILES["files"]["name"]). " has been uploaded.";
        //  echo '<img src="'.$target_file.'"/>';

      } else {
          echo "Sorry, there was an error uploading your file.";
      }
  }
}
$sql = "INSERT INTO customer_land (id, title, content, attachment, date) VALUES('".$_SESSION['landLord_id']."', '".$_POST['title']."', '".$_POST['content']."','".$target_file."', now())";

$result = mysqli_query($conn, $sql);
if($result) header('Location: ./customer.php');
else echo "오류가 발생했습니다.";
?>
