<?php
// Connect to DB same way did in db1.php
$server_name = "localhost";
$user_name = "uzjxte4jj5gyg";
$password = "hyfirofzudwo";
$db_name = "dbte2fhuna2wku";

// Create the connection
$conn = new mysqli($server_name, $user_name, $password, $db_name);

// Make sure connection didn't fail
if ($conn->connect_error) {
  die("Failed to connect to database dbte2fhuna2wku (SongList) " . $conn->connect_error);
}

// Get the selected genres from database1 out of the form


// Get selected genres from the form
if (isset($_GET['genres'])) {
    $selected_genres = $_GET['genres']; // This will be an array of selected genres
    
    // Create SQL query to fetch songs and their artists that match the selected genres
    $genre_list = "'" . implode("','", $selected_genres) . "'"; // Convert the array to a comma-separated string for SQL query
$sql = "
    SELECT s.song_title, s.artist_id AS artist_name
    FROM song s
    JOIN song_genre sg ON s.song_title = sg.song_title
    WHERE sg.genre_name IN ('" . implode("','", $selected_genres) . "')
";

    
    $result = $conn->query($sql);
    
    // Display the results
    if ($result->num_rows > 0) {
        echo "<div class='song-list'>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='song-item'>";
            echo "<h3>" . $row['song_title'] . "</h3>";
            echo "<p>Artist: " . $row['artist_name'] . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No songs found for the selected genres.</p>";
    }
} else {
    echo "<p>No genres selected.</p>";
}

// Close the connection
$conn->close();
?>

<!-- Back Button -->
<button onclick="window.location.href='db1.php';">Back to Genre Selection</button>

