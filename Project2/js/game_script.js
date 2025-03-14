    // Create the board
    // Get the board out by it's div id so that can populate it here
    board = document.getElementById('board');

    // Create a function to create all words (rows) of board at once --> one word is one row so create all 6 words (all 6 rows)
    // curr_row input param represents the row that we are creating (have 6 total rows so this is a num from 0 to 5)
    create_rows = () => {
        // Loop through 6 times and create all 6 rows of the board using function just defined for creating one single row
        for (let i=0; i<6; i++) {
            // Store each row as a div
            const row = document.createElement("div");

            // Step (d) in hints of spec says to add CSS class to div to be able style each row so do so
            row.classList.add("each_row");

            // Loop through 5 times and create all 5 cells in each row
            for (let j=0; j<5; j++) {
                // Create div for each cell = (b) in hints of specs
                const cell = document.createElement("div");

                // Add 2 CSS classes to each cell: (1) style each cell + (2) identify position of each cell
                // Position of each cell = given by row, col so put that as the position of each
                cell.classList.add("each_cell", `position_${i}_${j}`);

                 // Actually add each cell into the row div
                row.appendChild(cell);
            }
            
            // Once out of that loop, then are done with all of the cells in each row so add the now completed row to the board
            board.appendChild(row);
        }
    };

    create_rows();
