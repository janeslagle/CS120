// Follow hint 1 from spec file
// Create function to create all words (rows) of board at once --> one word = one row so create all 6 words (all 6 rows)
create_rows = () => {
    // Get board out by it's div id so that can populate it here
    const board = document.getElementById("board");

    // Loop through 6 times + create all 6 rows of board
    for (let i = 0; i < 6; i++) {
        // Store each row as a div
        const row = document.createElement("div");

        // Step (d) in hints of spec says to add CSS class to div to be able style each row so do so
        row.className = "each_row";

        // Loop through 5 times and create all 5 cells in each row
        for (let j = 0; j < 5; j++) {
            // Create div for each cell = (b) in hints of specs
            const cell = document.createElement("div");

            // Add style class to each cell
            cell.className = "each_cell";

            // Actually add each cell into the row div
            row.appendChild(cell);
        }
        
        // Once out of that loop, then are done with all of the cells in each row so add the now completed row to the board
        board.appendChild(row);
    }
}

// Now actually call the function so that the board is created on page
create_rows();

// Follow hint 2 from spec
// If user inputs a guess that is less than 5 letters long then display an alert with a message saying that it's an error
// Also reset the text box to be empty when close the alert so that the user can try guessing again
// Use an event handler to do this as told in hints from spec!
// So add an event listener to the guess button when the user clicks on the button
document.getElementById('guess_button').addEventListener('click', function() {
    // Get the inputted guess out from the div storing it
    const guessed_word = document.getElementById("user_input").value;

    // Check if the guessed word is less than 5 letters long 
    if (guessed_word.length < 5) {
        alert(`Invalid guess entered: "${guessed_word}" \n Guessed a word less than 5 letters long. Please try again by entering a 5 letter guess!`);
        document.getElementById("user_input").value = "";
    }
});
