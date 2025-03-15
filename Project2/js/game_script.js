// If don't have this, then click event handler for "check my guess" button never occurs
// So need this to be able to click the guess button and have everything happen!
window.onload = () => {
    document.getElementById("guess_button").addEventListener("click", play_game);
};

// Create answer dict of 30 possible different words
const possible_answers = [
    "abide", "avail", "budge", "begot", "beset", 
    "cargo", "craze", "deity", "ebony", "elate",
    "farce", "fudge", "glean", "grasp", "guide",
    "habit", "irate", "juvie", "kiosk", "lucid",
    "lymph", "mafia", "mince", "oasis", "ousts",
    "paint", "pulse", "rabid", "reign", "ruble"
];

generate_random_answer = () => {
    // Get random word out from dict array of possible answers to use as answer each time play game
    let random_word = Math.floor(Math.random() * possible_answers.length);

    // Now get the actual word out
    let answer_to_use = possible_answers[random_word].toUpperCase();

    // Display the answer each time in console of page
    console.log("The answer is: ", answer_to_use);
}

// Generate a random answer to be displayed to console each time play game
generate_random_answer();

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

// Call the function so that the board is created on page
create_rows();

// Function that actually resets the entire board by clearning all cells, generating new answer, and resets the user_guess div entirely
new_game = () => {
    // Reset board by creating the rows again
    document.getElementById("board").innerHTML = "";
    create_rows();

    // Reset word guess count because entirely new game now
    num_guessed_words = 0;

    // Start new game with new answer by calling function for it
    generate_random_answer();

    // When click the new game button, want the new game button to disappear so remove it!
    document.getElementById("new_game_button").remove();

    // NOW: because replaced the OG user_guess div content with the new game gutton every time the game ends, need to now replace the new
    // game button with the OG user_guess div content SO need to recreate the original user_guess div content: the label, text input box, and 
    // guess button
    // Before add back, make sure the user_guess div is blank so that don't have any weird stuff
    const user_guess_div = document.getElementById("user_guess");
    user_guess_div.innerHTML = "";

    // Replace the text input box label
    const input_label = document.createElement("label");
    input_label.setAttribute("for", "user_input");
    input_label.innerHTML = "What is your Guess?";
    input_label.style.fontWeight = "bold";

    // Replace input field box 
    const user_input_box = document.createElement("input");
    user_input_box.type = "text";
    user_input_box.id = "user_input";
    user_input_box.maxlength = "5";
    user_input_box.placeholder = "Enter your 5 letter guess";
    user_input_box.className = "user_input";

    // Replace check my guess button
    const guess_button = document.createElement("button");
    guess_button.innerHTML = "Check my Guess";
    guess_button.style.fontWeight = "bold";
    guess_button.id = "guess_button";
    guess_button.className = "guess_button";

    // Attach the guess button to its event handler again
    guess_button.addEventListener("click", play_game);

    // Add all 3 elements to the user_guess_div to make it the exact same as before!
    user_guess_div.appendChild(input_label);
    user_guess_div.appendChild(user_input_box);
    user_guess_div.appendChild(guess_button);
};

// Function to create the new game button for when the game ends, to show it on page
// Make it so that when the game is finished, the only thing in user_guess div is the new game button, remove everything else
show_new_game_button = () => {
    // Get the user_guess div
    const user_guess_div = document.getElementById("user_guess");

    // Remove everything from the existing child elements from user_guess div
    user_guess_div.innerHTML = "";
    
    // Create the button element
    const new_game_button = document.createElement("button");
    new_game_button.id = "new_game_button";
    new_game_button.textContent = "New Game";

    // Make it's text bold to match the check my guess button
    new_game_button.style.fontWeight = "bold";

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

// Everything need to play the game, user guessing, everything!
play_game = async () => {
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
