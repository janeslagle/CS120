<?php
// Part 1: db1.php
// First create connection to DB from past assignment 
// Get everything out of DB that need to connect to it
$servername = "localhost";
$dbname = "dbte2fhuna2wku";

// Now make the connection
$conn = new mysqli($servername, "", "", $dbname);

// Make sure can connect to the DB
if ($conn->connect_error) {
  die("Failed to connect to database dbte2fhuna2wku (Song List): " . $conn->connect_error);
}

// Now get the genres out of the genre table -> have column genre_name where all the genres are
$sql_query = "SELECT genre_name FROM genre"; 
$query_result = $conn->query($sql_query);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale=1.0">
    <title>Select Song Genres</title>    
  </head>
<body>
  <h1>Select Song Genres</h1>
  <form action=db2.php" method="get">
    <?php
      // See if there are any genres fetched from the DB and then create form out of them
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo '<label><input type="checkbox" name="genres[]" value="' . $row['genre_name']. '">' . $row['genre_name'] . '</label><br>';
        }
      } else {
        echo "No genres found in database dbte2fhuna2wku (Song List)";
      }
    ?>
    <button type="submit">Submit></button>
  </form>
</body>
</html>

<?php
// Close the connection to the DB now that are done
$conn->close();
?>
