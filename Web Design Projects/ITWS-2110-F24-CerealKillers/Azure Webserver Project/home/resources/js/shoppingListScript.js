   document.addEventListener('DOMContentLoaded', () => {
       const listContainer = document.getElementById('shopping-list');
       const exportButton = document.getElementById('export-btn');
       const userId = 1; // Replace with dynamic user ID as needed

       // Fetch the shopping list from the database
       const fetchList = async () => {
           try {
               const response = await fetch(`./resources/php/fetchShoppingList.php`, {
                   method: 'GET',
                   headers: {
                       'Content-Type': 'application/json'
                   }
               });
               if (!response.ok) {
                   throw new Error(`HTTP error! status: ${response.status}`);
               }
               const text = await response.text();
               const data = JSON.parse(text.trim());
            //    console.log("retrieved list from db", data); // Echo the fetched list to the console
               data.forEach(item => addItemToDOM(item));
               if (data.length === 0) {
                   addItemToDOM(); // Add an initial empty item if the list is empty
               }
           } catch (error) {
               console.error('Error fetching shopping list:', error);
           }
       };

       // Save the list to the database
       const saveList = async () => {
           const items = Array.from(document.querySelectorAll('.list-item')).map(item => ({
               text: item.querySelector('span').textContent,
               checked: item.querySelector('input[type="checkbox"]').checked
           }));
        //    console.log(items); // Echo the list to the console
        const uncheckedItems = items.filter(item => !item.checked && item.text.trim() !== '').map(item => item.text);  /*console.log("json about to be posted:", uncheckedItems); */// Echo the unchecked items to the console
        try {
            await fetch(`./resources/php/postShoppingList.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': "application/json",
                },
                body: JSON.stringify(uncheckedItems),
            });
            
        } catch (error) {
            console.error('Error saving shopping list:', error);
        }   };
        

       // Add item to the DOM
       const addItemToDOM = (text = '', checked = false) => {
           const listItem = document.createElement('div');
           listItem.className = `list-item ${checked ? 'checked' : ''}`;

           listItem.innerHTML = `
               <input type="checkbox" ${checked ? 'checked' : ''}>
               <span contenteditable="true" class="editable">${text}</span>
           `;

           // Checkbox event listener
           listItem.querySelector('input[type="checkbox"]').addEventListener('change', function () {
               listItem.classList.toggle('checked', this.checked);
               saveList();
           });

           // Event listener for adding new items on Enter key
           listItem.querySelector('.editable').addEventListener('keypress', function (event) {
               if (event.key === 'Enter') {
                   event.preventDefault();
                   if (this.textContent.trim() !== '') {
                       addItemToDOM(); // Add a new empty item for the next entry
                       saveList();
                   }
               }
           });

           listContainer.appendChild(listItem);
           listItem.querySelector('.editable').focus();
       };

       // Export the list to a .txt file
       const exportList = () => {
           const items = Array.from(document.querySelectorAll('.list-item'))
               .map(item => item.querySelector('span').textContent)
               .filter(text => text.trim() !== '');

           const blob = new Blob([items.join('\n')], { type: 'text/plain' });
           const link = document.createElement('a');
           link.href = URL.createObjectURL(blob);
           link.download = 'shopping_list.txt';
           link.click();
       };

       // Fetch the list on page load
       fetchList();

       // Add an initial empty item to start with (commented out to prevent blank item on page load)
       // addItemToDOM();

       // Attach export functionality to the button
       exportButton.addEventListener('click', exportList);
   });