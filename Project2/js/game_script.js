// Create answer dict = 30 words
const possible_answers = [
    "abide", "avail", "budge", "begot", "beset", 
    "cargo", "craze", "deity", "ebony", "elate",
    "farce", "fudge", "glean", "grasp", "guide",
    "habit", "irate", "juvie", "kiosk", "lucid",
    "lymph", "mafia", "mince", "oasis", "ousts",
    "paint", "pulse", "rabid", "reign", "ruble"
];

// Get random word out from dict array of possible answers to use as answer each time play game
const random_word = Math.floor(Math.random() * possible_answers.length);

// Now get the actual word out
const answer_to_use = possible_answers[random_word].toUpperCase();

// Display the answer each time in console of page
console.log("The answer is: ", answer_to_use);

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

// API call to check if guess inputted by user is a valid word or not
// Use the dictionary API because it's super duper easy to use
check_guess_valid = async (user_guess) => {
    try {
        // Use fetch to make the API call
        const api_response = await fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${user_guess}`);

        // Return if the API call was successful or not
        return response.ok;
    } catch (error) {
        console.error("Could not validate inputted guess due to: ", error);

        // If have error, then something wrong with API request so return false
        return false;
    }
};

// Define variable to keep track of the number of words the user has guessed
let num_guessed_words = 0;

// Follow hint 2 from spec
// If user inputs a guess that is less than 5 letters long then display an alert with a message saying that it's an error
// Also reset the text box to be empty when close the alert so that the user can try guessing again
// Use an event handler to do this as told in hints from spec!
// So add an event listener to the guess button when the user clicks on the button
document.getElementById('guess_button').addEventListener('click', async function() {
    // Get the inputted guess out from the div storing it
    const guessed_word = document.getElementById("user_input").value;

    // Check if the guessed word is less than 5 letters long 
    if (guessed_word.length < 5) {
        alert(`Invalid guess entered: "${guessed_word}" \n Guessed a word less than 5 letters long, please try again by entering a 5 letter guess!`);
        document.getElementById("user_input").value = "";

        // If this happens, then return bc otherwise, want to place the guessed word in the latest row of the board
        return;
    }

    // Check if the guessed word is even valid using API
    const is_valid_guess = await check_guess_valid(guessed_word.toLowerCase());

    // If word is not valid, then display alert that says so (just like the invalid words less than 5 letters so the game has uniformity)
    if (!is_valid_guess) {
        alert(`Invalid guess entered: "${guessed_word}" \n Guessed a word not found in the English dictionary, please try again by entering a valid 5 letter guess!`);

        // Reset the text input box to be empty so user can guess again
        document.getElementById("user_input").value = "";
        return;
    }

    // Add words to board as user still has guesses left (so if the word count is less than 6)
    if (num_guessed_words < 6) {
        // Get the row out from board for current word guess are on
        const current_row = document.getElementsByClassName("each_row")[num_guessed_words].children;

        // Convert the guessed word into an array so that can use .forEach here + use the .forEach to fill in all of the cells of row currently on
        guessed_word.split("").forEach((word_letter, i) => {
            // Fill in each cell of the row with the guessed letters of the inputted word!
            current_row[i].textContent = word_letter.toUpperCase();

            // Make each letter bold so it looks better on board
            current_row[i].style.fontWeight = "bold";
        });

        // Check if the guessed word is correct
        if (guessed_word.toUpperCase() === answer_to_use) {
            // Show a success message and add the reset button
            alert("Congratulations! You guessed the correct word.");
            return;
        }

        // Clear input text box for users next guess
        document.getElementById("user_input").value = "";

        // Increment the row currently on for next time user inputs a guess
        num_guessed_words += 1;
    }

    // After 6th word, display game over in an alert
    if (num_guessed_words === 6) {
        alert("Game Over! \n You've used all available six guesses without guessing the correct word. Reset the game to play again!");
    }
});
