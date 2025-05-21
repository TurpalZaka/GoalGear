document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');

    if (!orderId) {
        alert("Keine Bestell-ID angegeben.");
        return;
    }
    // fetch request um Rechnung zu holen
    fetch(`../../Backend/logic/printInvoice.php?order_id=${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Füllt die HTML-Elemente mit den Rechnungsdaten
            document.getElementById('invoice_number').innerText = data.invoice_number;
            document.getElementById('order_date').innerText = data.order_date;
            document.getElementById('customer_name').innerText = data.customer_name;
            document.getElementById('customer_address').innerText = data.customer_address;
            document.getElementById('total_amount').innerText = data.total_amount;
            // Fügt die Produkte zur Rechnung hinzu
            const productList = document.getElementById('product_list');
            data.products.forEach(product => {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.innerHTML = `
                    <strong>${product.product_name}</strong>
                    <span class="badge bg-primary rounded-pill">| <strong>Anzahl:</strong> ${product.quantity} |</span>
                    <span class="badge bg-secondary rounded-pill"><strong>Preis:</strong> ${product.price * product.quantity} €</span>
                `;
                productList.appendChild(listItem);
            });
        })
        .catch(error => {
            alert('Es gab einen Fehler beim Abrufen der Rechnung.');
        });
});
