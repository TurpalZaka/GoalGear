// Zweck: Anzeigen aller Kunden für Admins
window.onload = function() {
    fetch('../../Backend/logic/admin_get_customers.php')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById('customerTableBody');
        data.forEach(customer => {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${customer.id}</td>
                             <td>${customer.first_name}</td>
                             <td>${customer.last_name}</td>
                             <td>${customer.username}</td>
                             <td>${customer.email}</td>
                             <td><button onclick="location.href='admin_orders.html?customer_id=${customer.id}'">Bestellungen ansehen</button></td>
                             <td>${customer.active}</td>
                             <td><button onclick="toggleStatus(${customer.id}, ${customer.active})">Status ändern</button></td>`;
            tableBody.appendChild(row);
        });
    });
};

// Funktion um Status zu ändern
function toggleStatus(customerId, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    fetch(`../../Backend/logic/admin_toggle_status.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: customerId, status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Status erfolgreich geändert`);
            location.reload();
        } else {
            alert(`Fehler beim Ändern des Status`);
        }
    });
}