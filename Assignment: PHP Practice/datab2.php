<?php 
// Connect to the DB
$server_name = "localhost";
$user_name = "uzjxte4jj5gyg";
$password = "hyfirofzudwo";
$db_name = "dbte2fhuna2wku";

// Create connection
$conn = new mysqli($server_name, $user_name, $password, $db_name);

// Make sure connection didn't fail
if ($conn->connect_error) {
  die("Failed to connect to database dbte2fhuna2wku (SongList) " . $conn->connect_error);
}

// Get the selected genres from database1 out of the form
if (isset($_GET['genres'])) {
  $selected_genres = $_GET['genres'];

  // SQL query to get the songs and artists for selected genres out
  $sql_query = "
    SELECT sg.genre_name, s.song_title, s.artist_id AS artist_name
    FROM song s
    JOIN song_genre sg ON s.song_title = sg.song_title
    WHERE sg.genre_name IN ('" . implode("','", $selected_genres) . "')
  ";

  // Get the result out from doing sql query
  $query_result = $conn->query($sql_query);

  // Want display all songs + artists by genre
  $songs_by_genre = [];

  // If get result out from query, group the songs + artists by genre for when display them
  if ($query_result->num_rows > 0) {
    while($row = $query_result->fetch_assoc()) {
      $genre_name = $row['genre_name'];

      // Put the song and artist under the genre just got out
      $songs_by_genre[$genre_name][] = [
          'song_title' => $row['song_title'],
          'artist_name' => $row['artist_name']
      ];
    }

    // Display songs + artist grouped by genre
    foreach ($songs_by_genre as $genre => $songs) {
      echo "<div class='genre_section'>";
      echo "<h2>" . htmlspecialchars($genre) . "</h2>"; 
      echo "<div class='song_list'>";
      
      // Display all songs + artist for genre on
      foreach ($songs as $song) {
        echo "<div class='song_item'>";
        echo "<h3>" . htmlspecialchars($song['song_title']) . "</h3>";
        echo "<p>Artist: " . htmlspecialchars($song['artist_name']) . "</p>";
        echo "</div>";
      }

      // Done with this genre + song/artist
      echo "</div>"; 
      echo "</div>"; 
    }
  } else {
    echo "<p>No songs found from database for the selected genres.</p>";
  }
} else {
  echo "<p>No genres selected from form.</p>";
}

// Close connection at end
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song Genre Selection</title>
    <style>
      body {
        margin: 0;
        padding: 20px;
      }

      h1 {
        margin-bottom: 20px;
      }

      .genre_section {
        margin-bottom: 30px;
        padding: 15px;
        background-color: #FCEEF5;
        border-radius: 5px;
      }

      .genre_section h2 {
        font-size: 18px;
        margin-bottom: 10px;
      }

      .song_list {
        margin-left: 20px;
      }

      .song_item {
        margin-bottom: 15px;
      }

      .song_item h3 {
        font-size: 16;
        margin: 0;
      }

      .song_item p {
        font-size: 14px;
      }

      button {
        background-color: #FFC8E2;
        color: #000000;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        margin-top: 20px;
      }

      button:hover {
        background-color: #CF3D82;
        color: #FFFFFF;
      }
    </style>
  </head>

  <body>
    <!-- Back button to take user back to genre selection -->
    <button onclick="window.location.href='datab1.php';">Reselect song genres</button>
  </body>
</html>
