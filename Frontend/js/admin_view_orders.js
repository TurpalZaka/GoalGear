// Zweck: Anzeigen der Bestellungen eines Kunden für den Admin
document.addEventListener("DOMContentLoaded", function() {
    const params = new URLSearchParams(window.location.search);
    const customerId = params.get('customer_id');
    document.getElementById('customerId').innerText = customerId;
// fetch request um Bestellungen des Kunden zu holen
    fetch(`../../Backend/logic/admin_get_orders.php?customer_id=${customerId}`)
        .then(response => response.json())
        .then(data => {
            let orders = '<div class="alert alert-info">Keine Bestellungen vorhanden</div>';
            if (data.length > 0) {
                // Gruppiere die Produkte nach Bestell-ID
                const ordersGroupedById = data.reduce((acc, order) => {
                    if (!acc[order.id]) {
                        acc[order.id] = {
                            order_date: order.order_date,
                            total_amount: order.total_amount,
                            products: []
                        };
                    }
                    acc[order.id].products.push({
                        product_id: order.product_id,
                        product_name: order.product_name,
                        quantity: order.quantity,
                        price: order.price
                    });
                    return acc;
                }, {});

                // erstellt für jede Bestellung einen Block
                orders = '';
                for (const [orderId, orderDetails] of Object.entries(ordersGroupedById)) {
                    orders += `<div class="card mb-3">
                        <div class="card-header">
                            <strong>Bestell-ID:</strong> ${orderId} | <strong>Datum:</strong> ${orderDetails.order_date} | <strong>Gesamtbetrag:</strong> ${orderDetails.total_amount} €
                        </div>
                        <div class="card-body">
                            <ul class="list-group">`;
                    orderDetails.products.forEach(product => {
                        orders += `<li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Produktname:</strong> ${product.product_name} | <strong>Menge:</strong> ${product.quantity} | <strong>Preis:</strong> ${product.price * product.quantity} €
                            </div>
                            <button class="btn btn-danger btn-sm" onclick="deleteProduct(${orderId}, ${product.product_id})">Löschen</button>
                        </li>`;
                    });
                    orders += `</ul>
                        </div>
                    </div>`;
                }
            }
            document.getElementById('ordersContainer').innerHTML = orders;
        })
});

// Funktion um einzelne Produkte zu löschen
function deleteProduct(orderId, productId) {
    fetch(`../../Backend/logic/admin_delete_product.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ order_id: orderId, product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produkt erfolgreich gelöscht');
            location.reload();
        } else {
            alert('Fehler beim Löschen des Produkts: ' + data.error);
        }
    })
}
