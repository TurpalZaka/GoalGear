document.addEventListener('DOMContentLoaded', function() {
    fetchCategories(); // Laden der Kategorien beim Start

    // Produktsuche bei Eingabe im Suchfeld
    $('#search-input').on('input', function() {
        let searchQuery = $(this).val().trim();
        if (searchQuery !== '') {
            searchProducts(searchQuery);
        } else {
            // Wenn das Suchfeld leer ist, zeige alle Produkte an
            fetchProducts(1); // Hier die ID der Standardkategorie einfügen
        }
    });
});

// Funktion zum Laden der Kategorien
function fetchCategories() {
    fetch('../../Backend/logic/getCategories.php')
    .then(response => response.json())
    .then(data => {
        let categoryList = document.getElementById('categories');
        categoryList.innerHTML = '';
        data.forEach((category, index) => {
            let li = document.createElement('li');
            li.classList.add('list-group-item');
            li.textContent = category.name;
            li.onclick = function() {
                fetchProducts(category.id);
            };
            categoryList.appendChild(li);

            // Zeige Produkte der ersten Kategorie an
            if (index === 0) {
                fetchProducts(category.id);
            }
        });
    })
    .catch(error => {
    });
}

// Funktion zum Laden der Produkte einer Kategorie
function fetchProducts(categoryId) {
    fetch(`../../Backend/logic/getProducts.php?category_id=${categoryId}`)
    .then(response => response.json())
    .then(data => {
        let productList = document.getElementById('product-list');
        productList.innerHTML = '';
        data.forEach(product => {
            let productCard = `
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="${product.image}" class="card-img-top" alt="${product.name}">
                    <div class="card-body">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text">${product.description}</p>
                        <p class="card-text">Preis: €${product.price}</p>
                        <p class="card-text">Bewertung: ${product.rating}</p>
                        <a href="#" class="btn btn-primary add-to-cart" data-product-id="${product.id}">In den Warenkorb legen</a>
                    </div>
                </div>
            </div>
            `;
            productList.innerHTML += productCard;
        });

        // Event-Listener für Klicks auf "In den Warenkorb legen" Links/Buttons
        document.querySelectorAll('.add-to-cart').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Verhindere Standardaktion des Links/Button
                let productId = this.dataset.productId;
                addToCart(productId); // Produkt zum Warenkorb hinzufügen
            });
        });
    })
    .catch(error => {
    });
}

// Funktion zum Hinzufügen eines Produkts zum Warenkorb
function addToCart(productId) {
    fetch('../../Backend/logic/addToCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ productId: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(); // Aktualisiere die Anzeige der Warenkorbanzahl
        } else {
        }
    })
    
}

// Funktion zum Suchen eines Produkts
function searchProducts(query) {
    fetch(`../../Backend/logic/searchProducts.php?query=${query}`)
    .then(response => response.json())
    .then(data => {
        let productList = document.getElementById('product-list');
        productList.innerHTML = '';
        data.forEach(product => {
            let productCard = `
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="${product.image}" class="card-img-top" alt="${product.name}">
                    <div class="card-body">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text">Preis: €${product.price}</p>
                        <p class="card-text">Bewertung: ${product.rating}</p>
                        <p class="card-text">${product.description}</p>
                    </div>
                </div>
            </div>
            `;
            productList.innerHTML += productCard;
        });
    })
    
}
