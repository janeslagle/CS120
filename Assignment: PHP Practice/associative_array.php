<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Hours</title>
    <style>
      h1 {
        margin-bottom: 20px;
        margin-left: 5px;
      }
      
      .office_hours {
        font-family: monospace;
        white-space: pre-wrap;
        margin-left: 35px;
      }
    </style>
  </head>

  <body>
  <h1>Office Hours</h1>

  <pre class="office_hours"><?php 
      // Create an associative arr that has OH for business where keys = days of week, values = hours like 9am-4pm, etc.
      $OH = [
        'Monday' => '9am - 5pm',
        'Tuesday' => '9am - 5pm',
        'Wednesday' => '9am - 5pm',
        'Thursday' => '9am - 5pm',
        'Friday' => '9am - 5pm',
        'Saturday' => '10am - 2pm',
        'Sunday' => '11am - 1pm',
      ];
      
      // Function to loop through arr, display keys adjacent to hours, have function return string
      function display_OH($oh_array) {
        $hours_output = "";
      
        // Figure out which day is the longest so that can align the days + hours all up with each other
        // The days are stored as the keys of the arr
        $longest_day = max(array_map('strlen', array_keys($oh_array))); 
      
        // Now loop through each key, value pair (all days + their hours) and format each line 
        foreach($oh_array as $day => $hours) {
          // Pad each day name with spaces so that everything is lined up with eachother
          $hours_output .= str_pad($day, $longest_day + 2) . $hours . "\n";
        }
      
        return $hours_output;
      }
      
      // Call function to display hours on page
      echo display_OH($OH);
      ?>
  </pre>
  </body>
</html>
