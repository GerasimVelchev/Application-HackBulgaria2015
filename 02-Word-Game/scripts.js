document.body.onload = function() {
    var rows, cols;
    var tableFile;
    var rectTable;
    var word, wordLength;
    var numberOfOccurences;

    var move = [
        { 'row': +1, 'col':  0 },
        { 'row': -1, 'col':  0 },
        { 'row':  0, 'col': +1 },
        { 'row':  0, 'col': -1 },
        { 'row': +1, 'col': +1 },
        { 'row': +1, 'col': -1 },
        { 'row': -1, 'col': +1 },
        { 'row': -1, 'col': -1 }
    ];

    function fillButton(evt) {
        var word = document.getElementById('word-pattern').value;
        if (word)
            document.getElementById('submit-btn').value = 'Count occurences of word \'' + word + '\' in the table!';
        else
            document.getElementById('submit-btn').value = 'Count occurences!';
    }

    function isInTable(currentRow, currentCol) {
        return ( currentRow >= 0 && currentRow < rows && currentCol >= 0 && currentCol < cols );
    }

    function searchInCell(currentRow, currentCol, direction) {
        var matchedIndex;
        var moveX = move[direction]['row'];
        var moveY = move[direction]['col'];

        matchedIndex = 0;
        while (  isInTable(currentRow, currentCol) && matchedIndex < wordLength && 
                    rectTable[currentRow][currentCol] === word[matchedIndex] ) {
                    currentRow += moveX;
                    currentCol += moveY;
                    matchedIndex ++;
        }

        if (matchedIndex === wordLength)
            numberOfOccurences ++;
    }

    function searchPattern() {

        // Split the string into strings representing the rows of the table
        
        rectTable = rectTable.split('\n');
        numberOfOccurences = 0;

        // Trim the strings
        rows = rectTable.length;
        for (var i = 0; i < rows; i++)
            rectTable[i] = rectTable[i].trim();

        // Ignore empty rows
        rectTable = rectTable.filter(function(el) { return el.length != 0; });

        rows = rectTable.length;
        cols = rectTable[0].length;
        for (var i = 0; i < rows; i++)
            if (rectTable[i].length !== cols) {
                document.getElementById('content').innerHTML = 'File does not contain a rectangular table!';
                return;
            }

        for (var i = 0; i < rows; i++)
            for (var j = 0; j < cols; j++)
                for(var direction = 0; direction < 8; direction++)
                    searchInCell(i, j, direction);
        
        document.getElementById('content').innerHTML = 'Word \'' + word + '\' occurs ' + numberOfOccurences + ' times in the table.';
    }

    function readFile() {
        document.getElementById('content').innerHTML = '';

        word = document.getElementById('word-pattern').value;
        word = word.trim();
        wordLength = word.length;

        if (! word) {
            document.getElementById('content').innerHTML = 'Please, enter word.';
            return;
        }

        tableFile = document.getElementById('file-table').files[0];

        if (! tableFile) {
            document.getElementById('content').innerHTML = 'Please, enter the file with the rectangular table.';
            return;
        }

        var reader = new FileReader();

        reader.onload = function(e) {
            rectTable = e.target.result;
            searchPattern();
        }

        reader.readAsText(tableFile, 'UTF-8');
    }

    document.getElementById('word-pattern').addEventListener('keypress', fillButton, false);
    document.getElementById('word-pattern').addEventListener('keydown', fillButton, false);
    document.getElementById('word-pattern').addEventListener('keyup', fillButton, false);

    document.getElementById('form-table')
        .addEventListener('submit', function(e) {
            e.preventDefault();
        }, false);

    document.getElementById('submit-btn').addEventListener('click', readFile, false);
}