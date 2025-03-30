<?php
// Part 1: db1.php
// First create connection to DB from past assignment
// Get everything out of DB that need to connect to it: the user that have added to DB, everything, etc.!
$server_name = "localhost";
$user_name = "uzjxte4jj5gyg";
$password = "hyfirofzudwo";
$db_name = "dbte2fhuna2wku";

// Now make the connection to the DB
$conn = new mysqli($server_name, $user_name, $password, $db_name);

// Check if the connection to DB worked or not: if didn't, display it on page
if ($conn->connect_error) {
  die("Failed to connect to database dbte2fhuna2wku (Song List): " . $conn->connect_error);
}

// If have gotten here: means that successfully connected to DB so get the genres out now
// Genres are in genre table in genre_name column
$sql_query = "SELECT genre_name FROM genre";
$query_result = $conn->query($sql_query);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song Genre Selection</title>

    <style>
      h1 {
        margin-left: 10px;
      }

      form {
        margin-left: 35px;
        margin-bottom: 20px;
      }

      label {
    display: block;
    margin-bottom: 10px;
    font-size: 14px;
  }

  input[type="checkbox"] {
    margin-right: 10px;
  }

  button {
    background-color: #4CAF50; /* Green background */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }

  button:hover {
    background-color: #45a049; /* Darker green when hovered */
  }
    </style>
  </head>

  <body>
    <h1>Select Song Genres</h1>

    <form action=_datab2__.php" method="get">
      <?php
      // First check if any genres were pulled out of DB before try to loop through them
      if ($query_result->num_rows > 0) {
        // Then means have genres to create form with
        // Loop through all available genres have pulled out
        while($row = $query_result->fetch_assoc()) {
          // Have checkbox that has a label that is the song genre, put line between each checkbox in form
          echo '<label><input type="checkbox" name="genres[]" value="' . $row['genre_name']. '">' .$row['genre_name'] . '</label><br>';
        }
      } else {
        echo "No genres found in database dbte2fhuna2wku (Song List)";
      }
      ?>

      <button type="submit">Submit</button>
    </form>
  </body>
</html>

<?php
// Close connection to DB now that are done
$conn->close();
?>
