document.addEventListener("DOMContentLoaded", function() {
    loadOrderHistory();
});

// Funktion zum Laden der Bestellhistorie
function loadOrderHistory() {
    fetch('../../Backend/logic/getOrderHistory.php')
    .then(response => response.json())
    .then(data => {
        if (data.order_history) {
            const formattedOrders = formatOrderHistory(data.order_history);
            displayOrderHistory(formattedOrders);
        } else {
        }
    })
    .catch(error => {
    });
}

// Funktion zum Formatieren der Bestellhistorie
function formatOrderHistory(orderHistory) {
    const ordersMap = new Map();
    // Gruppiert Bestellungen nach Bestellnummer
    orderHistory.forEach(item => {
        if (!ordersMap.has(item.id)) {
            ordersMap.set(item.id, {
                id: item.id,
                order_date: item.order_date,
                total_amount: item.total_amount,
                status: item.status,
                products: []
            });
        }

        // Fügt Produkte zur jeweiligen Bestellung hinzu
        ordersMap.get(item.id).products.push({
            product_id: item.product_id,
            product_name: item.product_name,
            quantity: item.quantity,
            price: item.price
        });
    });

    return Array.from(ordersMap.values());
}

function displayOrderHistory(orders) {
    const orderHistoryContainer = document.getElementById('orderHistory');
    orderHistoryContainer.innerHTML = '';
    
    if (orders.length === 0) {
        orderHistoryContainer.textContent = 'Keine Bestellungen gefunden.';
        return;
    }
    // Erzeugt Block für jede Bestellung
    orders.forEach(order => {
        const orderDiv = document.createElement('div');
        orderDiv.classList.add('order');

        const orderId = document.createElement('p');
        orderId.textContent = 'Bestellnummer: ' + order.id;

        const orderDate = document.createElement('p');
        orderDate.textContent = 'Bestelldatum: ' + order.order_date;

        const productList = document.createElement('ul');

        order.products.forEach(product => {
            const productItem = document.createElement('li');
            productItem.textContent = `${product.product_name} - Menge: ${product.quantity} - Preis: ${product.price * product.quantity} €`;
            productList.appendChild(productItem);
        });

        const printInvoiceButton = document.createElement('button');
        printInvoiceButton.textContent = 'Rechnung drucken';
        printInvoiceButton.onclick = () => printInvoice(order.id);

        orderDiv.appendChild(orderId);
        orderDiv.appendChild(orderDate);
        orderDiv.appendChild(productList);
        orderDiv.appendChild(printInvoiceButton);

        orderHistoryContainer.appendChild(orderDiv);
    });
}
// Funktion zum Drucken der Rechnung
function printInvoice(orderId) {
    window.open(`invoice.html?order_id=${orderId}`, '_blank');
}

