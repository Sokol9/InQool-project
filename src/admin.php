<?php
  if(isset($_POST['submit'])){
    $name =  str_replace(["'", '"'], ["''", '""'], $_POST['txtname']);
    $content = str_replace(["'", '"'], ["''", '""'], $_POST['txtcontent']);
    $categories = explode(",", str_replace(["'", '"'], ["''", '""'], $_POST['txtcategories']));
    if (!empty($name) && !empty($content)){
      include "connection.php";

      $conn = new mysqli($servername, $username, $password, $my_db);

      if($conn->connect_error){
        $bad_message = $conn->connect_error;
      }else{

        $sql = ("insert into articles(name, content)
          values('$name', '$content')");
        if ($conn->query($sql) === TRUE){
          $id = $conn->insert_id;

          foreach ($categories as $cat){
            if (strlen($cat) > 0){
              $sql = ("insert into categories(name, ID_article)
                values('" . strtolower($cat) . "', '$id')");
              if($conn->query($sql) !== TRUE){
                $bad_message = $conn->error;

                $sql = ("delete from articles where ID = $id");
                $conn->query($sql);

                $sql = ("delete from categories where ID_article = $id");
                $conn->query($sql);
              }
            }
          }
          $good_message = "insert successfully";
        }else{
          $bad_message = $conn->error;
        }
      }
      $conn->close();
    }else {
      $bad_message = "name and content can not be empty";
    }
  }
?>

<div id="sitecontent">
  <div id="form">
    <form action="#" method="post">
      <div class="felem">
        <label for="fname">Article name:</label>
        <input type="text" id="fname" name="txtname">
      </div>
      <div class="felem">
        <label for="fcontent">Article content:</label>
        <textarea rows = "5" id="fcontent" name="txtcontent"></textarea>
      </div>
      <div class="felem">
        <label for="fcategories">Article categories:</label>
        <span>(separete by comma)</span>
        <textarea rows = "5" id="fcategories" name="txtcategories"></textarea>
      </div>


      <div class="submit">
        <input class="submit-btn" type="submit" name="submit" value="Submit">
        <span class="form-msg-good form-msg felem">
          <?php
          echo $good_message;
          ?>
        </span>
        <span class="form-msg-bad form-msg felem">
          <?php
          echo $bad_message;
          ?>
        </span>
      </div>
    </form>
  </div>
  <div id="side-img">
    <img alt="aley of trees" src="img/img1.jpg">
  </div>
</div>
