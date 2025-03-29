<?php 
// Make the php file JSON since want return JSON response
header('Content-Type: application/json');

// Read value from query set with key "n"
// Make sure that n value not null: if it is, make it 0
if (isset($_GET['n'])) {
  // Then pull it out and make it an int so can work with it as want to
  $n = intval($_GET['n']);
} else {
  $n = 0;
}

// Generate the Fiboniacci sequence now using n -> so give n as input
// Store value n as an arr in this function for when put response as json
function fib_sequence($n) {
  // Can only create fib sequence if n is 1 or greater so account for that
  if ($n <= 0) {
    // Just return nothing in this case
    return [];
  }

  // If n is 1, then return 0 as the only thing in sequence
  if ($n == 1) {
    return [0];
  }

  // If n is 2, then return 0,1 as only thing in sequence. Now have everything initialized for when n is > 2
  if ($n == 2) {
    return [0, 1];
  }

  // Every sequence past n=2 starts out with 0,1 so initialize that and then fill rest in with loop below!
  $seq = [0,1];

  // Then loop through according to n and fill in the rest of the sequence using Fibonacci sequence formula: Fn = F_n-1 + F_n-2 for n > 2
  for ($i = 2; $i < $n; $i++) {
    $seq[] = $seq[$i - 1] + $seq[$i - 2];
  }

  return $seq;
}

// Call the function to generate fib seq with the n just got out from query
$fib_seq = fib_sequence($n);

// Create the json response str now: has value n with the arr from function -> make it look like example given in assignment spec
$json_response = [
  'length' => $n,
  'fibSequence' => $fib_seq
];

// Told to echo JSON str so convert the response to JSON str and display it
echo json_encode($json_response);
?>
