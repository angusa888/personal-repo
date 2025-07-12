
function makeNewCheckbox(ingredient) {

    // Create new checkbox node and br
    let newCheckbox = document.createElement('input');
    let newLabel = document.createElement('label');
    let br = document.createElement("br");

    // Set checkbox id, name and value
    newCheckbox.id = ingredient;
    newCheckbox.type = "checkbox";
    newCheckbox.name = "blacklistArray[]";
    newCheckbox.value = ingredient;

    // Label
    newLabel.htmlFor = ingredient;
    newLabel.innerHTML = ingredient + "<br>";

    // Add nodes
    document.getElementById("blacklist").appendChild(newCheckbox);
    document.getElementById("blacklist").appendChild(newLabel);
}

function addToBlacklist(ingredient) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../account/addToBlacklist.php", true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // alert(xhr.response); // Show success message or response
                location.reload(); // Reload the page
            } else {
                alert("Error adding ingredient: " + xhr.responseText); // Show error message
            }
        }
    };

    // Send the ingredient as a URL-encoded string
    xhr.send("myIngredient=" + encodeURIComponent(ingredient));
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('addIngredientForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        const ingredient = document.getElementById('myInput').value; // Get the ingredient value

        if (ingredient.trim() === "") {
            document.getElementById("b-val-text").style.display = "flex";
            document.getElementById("b-val-msg").innerHTML = "Please enter an ingredient.";
            return;
        }

        addToBlacklist(ingredient);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('dietaryList').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        
        xhr.open('POST', '../account/addPresetToBlacklist.php', true);
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    window.location.reload();
                    // alert(xhr.response);
                } else {
                    console.error('Error:', xhr.statusText);
                }
            }
        };
        
        xhr.send(formData);
    });
});
function removeItem(ingredient) {
    const formData = new FormData();
    formData.append('ingredient', ingredient); // Send the ingredient to delete

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../account/removeFromBlacklist.php', true); // Adjust the URL as needed

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                window.location.reload(); // Reload the page to reflect changes
                // alert(xhr.response); // Optional: show response message
            } else {
                console.error('Error:', xhr.statusText);
            }
        }
    };

    xhr.send(formData);
}

document.addEventListener('DOMContentLoaded', () => {
    // Event delegation for dynamically added delete items
    if (document.getElementById('blacklist') != null) {
        document.getElementById('blacklist').addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-item')) {
                const ingredientToDelete = event.target.getAttribute('data-ingredient');
                removeItem(ingredientToDelete); // Call the removeItem function
            }
        });
    }
});