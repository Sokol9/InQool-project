<div id="sitecontent-home">
<?php
  include "connection.php";
  $conn = new mysqli($servername, $username, $password, $my_db);

  if($conn->connect_error){
    $message = $conn->connect_error;
    $conn->close();
  }else{
    $sql = " select * from articles order by time desc";
    $result = $conn->query($sql);

    $cnt = 0;
    echo "<div class='articles'>";
    while (($row = mysqli_fetch_row($result)) && $cnt < 3) {
        echo "<div class='article-item'>";
        echo  "<h2>" . $row[2] . "</h2>";
        echo  "<p>" . $row[3] . "</p>";
        $sql2 = "select * from categories where ID_article = " . $row[0];

        $cats = $conn->query($sql2);
        $actual_cat = 0;
        while ($cat = mysqli_fetch_row($cats)){
          if ($actual_cat > 0){
            echo ", ";
          } elseif ($actual_cat == 0){
            echo "<p class='category'> Category: ";
          }
          echo "<span>" . $cat[1] . "</span>";
          $actual_cat += 1;
        }

        if ($actual_cat != 0){
          echo "</p>";
        } else {
          echo "<p class='category'>without category</p>";
        }
        $cnt += 1;
        if ($cnt < 3){
          echo "<hr>";
        }
        echo  "</div>";
      }
    echo  "</div>";
  }
  $sql = "select count(ID) from articles";
  $result = $conn->query($sql);
  $article_cnt = mysqli_fetch_row($result)[0];

  $sql = "select count(*) from (select distinct name from categories) as a";
  $result = $conn->query($sql);
  $categories_cnt = mysqli_fetch_row($result)[0];

  echo "<div class = stats>";
  echo  "<h3>Stats</h3>";
  echo "<p>articles: " . $article_cnt . "</p>";
  echo "<p>categories: " . $categories_cnt . "</p>";
  echo "</div>";
  echo "<div id='piechart'></div>";
  echo  "</div>";
?>
<script>
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Category', 'Number of articles'],
      <?php
      $sql = "select name, count(name) from categories group by name";
      $result = $conn->query($sql);
      while ($row = mysqli_fetch_row($result))
      echo "['" . $row[0] . "'," . $row[1] ."],";
      ?>
    ]);

    var options = {
      title: 'Number of articles in each category'
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
  }
</script>
<?php
  $conn->close();
?>
