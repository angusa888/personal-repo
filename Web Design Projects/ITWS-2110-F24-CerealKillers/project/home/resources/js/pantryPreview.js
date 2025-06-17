document.addEventListener('DOMContentLoaded', function() {
    // echo it made it here
    // console.log("attempting to fetch pantry");
    fetch('../resources/php/get_pantry.php')
        .then(response => response.json())
        .then(data => {
            // echo the pantry data
            // console.log("pantry data", data);
            const wrapperText = document.querySelector('.wrappertxt');
            if (wrapperText) {
                const ul = document.createElement('ul');
                data.forEach(ingredient => {
                    const li = document.createElement('li');
                    li.textContent = ingredient;
                    ul.appendChild(li);
                });
                wrapperText.innerHTML = '';
                wrapperText.appendChild(ul);
            }
        })
        .catch(error => console.error('Error fetching ingredients:', error));
});