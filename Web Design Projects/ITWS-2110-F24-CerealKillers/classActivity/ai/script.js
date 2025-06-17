let randomColor;
let playerRed = 128;
let playerGreen = 128;
let playerBlue = 128;
let timerInterval;
let bestScores = [];

// Add event listeners for buttons and sliders
document.getElementById("startButton").addEventListener("click", startGame);
document.getElementById("red").addEventListener("input", updatePlayerColor);
document.getElementById("green").addEventListener("input", updatePlayerColor);
document.getElementById("blue").addEventListener("input", updatePlayerColor);
document.getElementById("replayButton").addEventListener("click", resetGame);
document.getElementById("instructionsButton").addEventListener("click", function() {
    const instructions = document.getElementById("instructions");
    instructions.style.display = instructions.style.display === "none" ? "block" : "none";
});

// Load best scores from local storage when the page loads
window.onload = () => {
    loadBestScores();
};

function startGame() {
    randomColor = generateRandomColor();
    document.getElementById("randomColor").style.backgroundColor = randomColor;
    document.getElementById("playerColor").style.backgroundColor = `rgb(${playerRed}, ${playerGreen}, ${playerBlue})`;

    document.getElementById("startButton").style.display = "none";
    document.getElementById("replayButton").style.display = "none";
    document.getElementById("timer").textContent = "Time left: 10 seconds";
    document.getElementById("score").textContent = "";

    startTimer(10);
    drawScoreChart(0);  // Initialize the chart to 0% filled
}

function generateRandomColor() {
    const red = Math.floor(Math.random() * 256);
    const green = Math.floor(Math.random() * 256);
    const blue = Math.floor(Math.random() * 256);
    return `rgb(${red}, ${green}, ${blue})`;
}

function updatePlayerColor() {
    playerRed = document.getElementById("red").value;
    playerGreen = document.getElementById("green").value;
    playerBlue = document.getElementById("blue").value;

    // Update the player's color preview
    document.getElementById("playerColor").style.backgroundColor = `rgb(${playerRed}, ${playerGreen}, ${playerBlue})`;

    // Display the current RGB values next to each slider
    document.getElementById("redValue").textContent = playerRed;
    document.getElementById("greenValue").textContent = playerGreen;
    document.getElementById("blueValue").textContent = playerBlue;

    // Update the score and pie chart in real-time
    const score = calculateRealTimeScore();
    drawScoreChart(score);
}

function startTimer(seconds) {
    let timeLeft = seconds;
    timerInterval = setInterval(() => {
        if (timeLeft > 0) {
            timeLeft--;
            document.getElementById("timer").textContent = `Time left: ${timeLeft} seconds`;
        } else {
            clearInterval(timerInterval);
            scorePlayer();
        }
    }, 1000);
}

function scorePlayer() {
    const randomRGB = getRGBValues(randomColor);
    const playerRGB = [playerRed, playerGreen, playerBlue];

    const score = calculateColorDifference(randomRGB, playerRGB);
    const normalizedScore = calculateRealTimeScore();

    document.getElementById("score").textContent = `Your score: ${normalizedScore.toFixed(2)}%`;

    // Save the normalized score to local storage
    saveScore(normalizedScore);

    // Show top scores after scoring
    displayTopScores();

    // Display the replay button for resetting the game
    document.getElementById("replayButton").style.display = "block";
}


function getRGBValues(rgbString) {
    return rgbString.match(/\d+/g).map(Number);
}

function calculateColorDifference(color1, color2) {
    const redDiff = Math.pow(color1[0] - color2[0], 2);
    const greenDiff = Math.pow(color1[1] - color2[1], 2);
    const blueDiff = Math.pow(color1[2] - color2[2], 2);
    return Math.sqrt(redDiff + greenDiff + blueDiff);
}

// Real-time score calculation based on player's current color
function calculateRealTimeScore() {
    const randomRGB = getRGBValues(randomColor);
    const playerRGB = [playerRed, playerGreen, playerBlue];
    const score = calculateColorDifference(randomRGB, playerRGB);
    const maxDifference = 441.67;  // Maximum color difference (worst score)
    const normalizedScore = ((maxDifference - score) / maxDifference) * 100;
    return Math.max(Math.min(normalizedScore, 100), 0);  // Return a percentage between 0 and 100
}

// Draw the pie chart based on the current score
function drawScoreChart(percentage) {
    const canvas = document.getElementById('scoreChart');
    const ctx = canvas.getContext('2d');
    const radius = canvas.width / 2;

    // Clear the canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Get the target color to fill the chart
    const targetColor = randomColor;

    // Draw the pie chart (filled part with the target color)
    ctx.beginPath();
    ctx.moveTo(radius, radius);
    ctx.arc(radius, radius, radius, 0, (Math.PI * 2 * percentage) / 100, false);
    ctx.closePath();
    ctx.fillStyle = targetColor;  // Use the random target color for the score
    ctx.fill();

    // Draw the remaining (unfilled) part of the circle
    ctx.beginPath();
    ctx.moveTo(radius, radius);
    ctx.arc(radius, radius, radius, (Math.PI * 2 * percentage) / 100, Math.PI * 2, false);
    ctx.closePath();
    ctx.fillStyle = '#ccc';  // Light gray for unfilled
    ctx.fill();
}

// Function to reset the game
function resetGame() {
    document.getElementById("startButton").style.display = "block";
    document.getElementById("replayButton").style.display = "none";
    document.getElementById("randomColor").style.backgroundColor = "#ffffff";
    document.getElementById("playerColor").style.backgroundColor = "#808080";
    document.getElementById("timer").textContent = "";
    document.getElementById("score").textContent = "";

    playerRed = 128;
    playerGreen = 128;
    playerBlue = 128;
    document.getElementById("red").value = 128;
    document.getElementById("green").value = 128;
    document.getElementById("blue").value = 128;
    document.getElementById("redValue").textContent = 128;
    document.getElementById("greenValue").textContent = 128;
    document.getElementById("blueValue").textContent = 128;
    
    // Reset pie chart
    drawScoreChart(0);
}

// Save the score in local storage and update the top scores
function saveScore(normalizedScore) {
    let storedScores = JSON.parse(localStorage.getItem("bestScores")) || [];
    
    // Save the normalized score, formatted to two decimal places
    storedScores.push(Number(normalizedScore.toFixed(2)));
    storedScores.sort((a, b) => b - a);  // Sort scores in descending order (higher score is better)

    if (storedScores.length > 3) {
        storedScores = storedScores.slice(0, 3);  // Keep only the top 3 scores
    }

    localStorage.setItem("bestScores", JSON.stringify(storedScores));
}


// Load the top scores from local storage
function loadBestScores() {
    const storedScores = JSON.parse(localStorage.getItem("bestScores")) || [];
    displayTopScores(storedScores);
}

// Display the top scores in the table
function displayTopScores() {
    const storedScores = JSON.parse(localStorage.getItem("bestScores")) || [];
    const tableBody = document.getElementById("topScoresBody");

    tableBody.innerHTML = "";  // Clear existing rows

    if (storedScores.length === 0) {
        tableBody.innerHTML = "<tr><td colspan='2'>No scores yet</td></tr>";
    } else {
        storedScores.forEach((score, index) => {
            const row = `<tr><td>${index + 1}</td><td>${score.toFixed(2)}</td></tr>`;
            tableBody.innerHTML += row;
        });
    }
}
