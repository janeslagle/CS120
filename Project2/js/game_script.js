window.onload = () => {
    // Add event listener for the guess button
    document.getElementById("guess_button").addEventListener("click", guessing_words);
};

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
let random_word = Math.floor(Math.random() * possible_answers.length);

// Now get the actual word out
let answer_to_use = possible_answers[random_word].toUpperCase();

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

// Store contents of user_guess div because are going to replace it with show_new_game_button function temporarily with the new game button
let user_guess_div_content;
let og_guess_button;

// Function that actually resets the entire game board by clearing all cells and picking a new random word from dict to use as answer
new_game = () => {
    // Reset the board by creating the rows again
    document.getElementById("board").innerHTML = "";
    create_rows();

    // Reset the word guess count because entirely new game now
    num_guessed_words = 0;

    // Start the new game with a new answer
    // Get random word out from dict array of possible answers to use as answer each time play game
    let random_word = Math.floor(Math.random() * possible_answers.length);

    // Now get the actual word out
    answer_to_use = possible_answers[random_word].toUpperCase();

    // Display the answer each time in console of page
    console.log("The new answer for this round is: ", answer_to_use);

    // When actually click the new game button, want the new game button to disappear, so make sure that happens!!!
    document.getElementById("new_game_button").remove();

    // Add the OG user_guess div content back to it
    const user_guess_div = document.getElementById("user_guess");
    user_guess_div.innerHTML = "";
    // Create the input field again
    const userInput = document.createElement("input");
    userInput.type = "text";
    userInput.id = "user_input";
    userInput.placeholder = "Enter your guess";

    // Create the submit button again
    const submitButton = document.createElement("button");
    submitButton.textContent = "Submit Guess";
    submitButton.id = "guess_button";

    // Reattach the event listener for the submit button
    submitButton.addEventListener("click", guessing_words);

    // Append the input and submit button back to the user_guess div
    user_guess_div.appendChild(userInput);
    user_guess_div.appendChild(submitButton);
};

// Function to create the new game button for when the game ends, to show it on page
// Make it so that when the game is finished, the only thing in user_guess div is the new game button, remove everything else
show_new_game_button = () => {
    // Get the user_guess div
    const user_guess_div = document.getElementById("user_guess");

    // Save OG content of user_guess div so that can put it back once hit new game button
    user_guess_div_content = user_guess_div.innerHTML;
    
    // Remove everything from the existing child elements from user_guess div
    user_guess_div.innerHTML = "";
    
    // Create the button element
    const new_game_button = document.createElement("button");
    new_game_button.id = "new_game_button";
    new_game_button.textContent = "New Game";

    // Add it's stylesheet method
    new_game_button.className = "new_game_button"; 

    // Add event listener to reset the game when clicked by calling function here that does so
    new_game_button.addEventListener("click", new_game);

    // Want the new game button in the user_guess div so add it here!
    document.getElementById("user_guess").appendChild(new_game_button);
};

// API call to check if guess inputted by user is a valid word or not
// Use the dictionary API because it's super duper easy to use
check_guess_valid = async (user_guess) => {
    try {
        // Use fetch to make the API call
        const api_response = await fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${user_guess}`);

        // Return if the API call was successful or not
        return api_response.ok;
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
// document.getElementById('guess_button').addEventListener('click', async function() {
guessing_words = async () => {
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
        // guessed_word.split("").forEach((word_letter, i) => {
        //     // Fill in each cell of the row with the guessed letters of the inputted word!
        //     current_row[i].textContent = word_letter.toUpperCase();

        //     // Make each letter bold so it looks better on board
        //     current_row[i].style.fontWeight = "bold";
        // });

        // Array to track the status of each letter in the guessed word
        let letterStatus = ["not_in_word_letter ", "not_in_word_letter ", "not_in_word_letter ", "not_in_word_letter ", "not_in_word_letter "];
        
        // Step 1: Mark correct letters in the correct position
        guessed_word.split("").forEach((word_letter, i) => {
            if (word_letter.toUpperCase() === answer_to_use[i]) {
                letterStatus[i] = "correct_letter ";
                current_row[i].style.backgroundColor = "green"; // Green background for correct letter
                current_row[i].style.color = "white"; // White text for visibility
            }
        });

        // Step 2: Mark correct letters in the wrong position
        guessed_word.split("").forEach((word_letter, i) => {
            // Only check if it’s not already marked as correct
            if (letterStatus[i] !== "correct_letter " && answer_to_use.includes(word_letter.toUpperCase())) {
                letterStatus[i] = "wrong_spot_letter ";
                current_row[i].style.backgroundColor = "gold"; // Gold background for wrong position
                current_row[i].style.color = "black"; // Black text for better contrast
            }
        });

        // Step 3: Mark letters not in the word
        guessed_word.split("").forEach((word_letter, i) => {
            if (letterStatus[i] === "not_in_word_letter") {
                current_row[i].style.backgroundColor = "grey"; // Grey background for wrong letters
                current_row[i].style.color = "white"; // White text for contrast
            }
        });

        // Check if the word just guessed is the answer word
        if (guessed_word.toUpperCase() === answer_to_use) {
            // Then show an alert with the answer word in it
            alert(`Yippee yahoo - you guessed the correct word, "${answer_to_use}"! \n Play a new game to challenge yourself again!`);

            // Want show the new game button now because game over
            show_new_game_button();
            
            // Return because means game is over!
            return; 
        }
        
        // Clear input text box for users next guess
        document.getElementById("user_input").value = "";

        // Increment the row currently on for next time user inputs a guess
        num_guessed_words += 1;
    }

    // After 6th word, display game over in an alert
    if (num_guessed_words === 6) {
        alert(`Game Over! \n You've used all six available guesses without guessing the correct word, which was "${answer_to_use.toLowerCase()}". \n Play a new game to challenge yourself again!`);

        // Show the new game button now because game over
        show_new_game_button();
    }
};
