$(document).ready(function() {
    loadCartItems();
});

function loadCartItems() {
    $.ajax({
        url: '../../Backend/logic/getCartItems.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let cartItemsContainer = $('#cart-items');
            cartItemsContainer.empty();
            let totalAmount = 0;
            let totalItems = 0; // Neue Variable hinzugefügt

            data.forEach(item => {
               
                let productCard = `
                    <div class="cart-item row" data-product-id="${item.product_id}">
                        <div class="col-md-2">
                            <img src="${item.image}" class="img-fluid" alt="${item.name}">
                        </div>
                        <div class="col-md-8">
                            <h5>${item.name}</h5>
                            <p>Preis: €${item.price}</p>
                            <p>Menge: 
                                <button class="btn btn-sm btn-outline-secondary decrease-quantity">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="btn btn-sm btn-outline-secondary increase-quantity">+</button>
                            </p>
                            <p>Gesamt: €<span class="total">${(item.price * item.quantity).toFixed(2)}</span></p>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-danger remove-item">Entfernen</button>
                        </div>
                    </div>
                `;
                cartItemsContainer.append(productCard);
                totalAmount += item.price * item.quantity;
                totalItems += item.quantity; // Erhöhung der totalItems Variable
            });

            $('#total-amount').text(totalAmount.toFixed(2));
            $('#cart-count').text(`(${totalItems})`); // Aktualisierung des Warenkorb-Anzeigers

            // Event-Listener für die Buttons hinzufügen
            $('.increase-quantity').click(function() {
                let cartItem = $(this).closest('.cart-item');
               
                let productId = cartItem.attr('data-product-id');
               
                updateQuantity(productId, 1);
            });

            $('.decrease-quantity').click(function() {
                let cartItem = $(this).closest('.cart-item');
               
                let productId = cartItem.attr('data-product-id');
            
                updateQuantity(productId, -1);
            });

            $('.remove-item').click(function() {
                let cartItem = $(this).closest('.cart-item');
               
                let productId = cartItem.attr('data-product-id');
              
                removeItem(productId);
            });
        },
        error: function(error) {
           
        }
    });
}

function updateQuantity(productId, change) {
    // Überprüfen der aktuellen Menge
    let currentQuantityElement = $(`.cart-item[data-product-id="${productId}"] .quantity`);
    let currentQuantity = parseInt(currentQuantityElement.text());

    if (currentQuantity + change < 1) {
        alert('Die Menge kann nicht weniger als 1 sein.');
        return;
    }

    $.ajax({
        url: '../../Backend/logic/updateCartItem.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ productId: productId, change: change }),
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                loadCartItems(); // Warenkorb neu laden
            } else {
                
            }
        },
        error: function(error) {
           
        }
    });
}


function removeItem(productId) {
    $.ajax({
        url: '../../Backend/logic/removeCartItem.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ productId: productId }),
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                loadCartItems(); // Warenkorb neu laden
            } else {
                
            }
        },
        error: function(error) {
           
        }
    });
}

$('#checkout-button').click(function() {
    // Logik für den Checkout-Prozess hier hinzufügen
    alert('Checkout-Prozess starten');
});
