function removeItem(ingredient) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "./resources/php/remove_ingredient.php", true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // alert(xhr.response);
                location.reload();
            } else {
                alert("Error removing ingredient: " + xhr.responseText);
            }
        }
    };
    xhr.send("ingredient=" + encodeURIComponent(ingredient));
}

document.getElementById("addIngredientForm").addEventListener("submit", function(e) {
    e.preventDefault(); 

    var form = this;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", form.action, true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    var formData = new URLSearchParams(new FormData(form)).toString(); 

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // alert(xhr.responseText);
                location.reload();

            } else {
                console.error("Error adding ingredient:", xhr.statusText);
                alert("Error: " + xhr.statusText);
            }
        }
    };

    xhr.send(formData);
});

/* Displays ingredients from the user's pantry */
function displayRecipes(response) {
    document.getElementById("ulModal").innerHTML = "";
    for (let i = 0, len = response.length; i < len; i++) {
        var name = response[i]["name"];
        // Make all capital letters
        var words = name.split(" ");
        for (let i = 0; i < words.length; i++) {
            words[i] = words[i][0].toUpperCase() + words[i].substr(1);
        }
        name = words.join(" ");

        var url = response[i]["url"];
        var ingredients = response[i]["ingredients"];

        // Handle case if ingredients is not an array
        if (typeof ingredients === 'string') {
            ingredients = ingredients.split(',');  // Split string into an array
        }

        if (Array.isArray(ingredients)) {
            // Create new elements
            let boxLi = document.createElement("li"); 
            let svg = document.createElement("svg");
            let div = document.createElement("div");
            let div2 = document.createElement("div");
            let titleLink = document.createElement("a");
            let ul = document.createElement("ul");

            // Add style and id to new elements
            boxLi.id = "box" + name;
            boxLi.className = "d-flex gap-4 recipe-container";

            svg.className = "bi text-body-secondary flex-shrink-0";
            svg.width = "48";
            svg.height = "48";
            svg.innerHTML = '<use xlink:href="#grid-fill"></use>';

            titleLink.id = name;
            titleLink.innerHTML = name;
            titleLink.href = url;
            titleLink.className = "title-link";
            titleLink.target = "_blank";

            ul.id = name + "ingredientsList";

            ingredients.forEach(ingredient => {
                ingredient = ingredient.replace(/"/g, "");
                ingredient = ingredient.replace(/\[/g, "");
                ingredient = ingredient.replace(/\]/g, "");
                let newIngredient = document.createElement("li");
                newIngredient.innerHTML = ingredient;
                ul.appendChild(newIngredient);
            });

            div2.appendChild(ul);
            div.appendChild(titleLink);
            div.appendChild(div2);
            boxLi.appendChild(svg);
            boxLi.appendChild(div);

            document.getElementById("ulModal").appendChild(boxLi);
            //document.getElementById("recipesGenerated").appendChild(ul);
        } else {
            console.error("Ingredients is not an array for recipe:", name);
        }
    }
}
