document.addEventListener('DOMContentLoaded', function() {
    fetchCategories();// Holt die Kategorien vom Server
    
    function fetchCategories() {// Holt die Kategorien vom Server
        fetch('../../Backend/logic/getCategories.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Netzwerkantwort war nicht in Ordnung');
            }
            return response.json();
        })
        .then(data => {
            let categorySelect = document.getElementById('category');
            categorySelect.innerHTML = '';
            data.forEach(category => {// Erzeugt Optionen für das Kategorien-Auswahlfeld
                let option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
        })
        .catch(error => {
        });
    }
});
