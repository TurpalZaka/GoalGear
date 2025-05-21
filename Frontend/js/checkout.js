function initiateCheckout() {
    fetch('../../Backend/logic/checkLogin.php')
    .then(response => response.json())
    .then(data => {
        if(data.loggedin) {
            // Benutzer ist eingeloggt, leite zur Bestellungsseite weiter
            window.location.href = '../sites/bestellung.html'; 
        } else {
            // Benutzer ist nicht eingeloggt, leite zur Anmeldeseite weiter
            window.location.href = '../sites/login.html'; 
        }
    })
    .catch(error => {
    });
}
function placeOrder() {
    var paymentMethod = document.getElementById('paymentMethod').value;
    var voucherCode = ''; // Gutscheincode initialisieren
    
    // Prüfen, ob die Zahlungsmethode "Gutschein" ist und das Eingabefeld sichtbar ist
    if (paymentMethod === "voucher") {
        voucherCode = document.getElementById('voucherCode').value; // Gutscheincode aus dem Eingabefeld lesen
    }
    // Daten für die Bestellung
    var orderData = {
        paymentMethod: paymentMethod,
        voucherCode: voucherCode // Gutscheincode hinzufügen
        // Weitere Daten wie Artikel im Warenkorb, etc. können hier hinzugefügt werden
    };

    fetch('../../Backend/logic/placeOrder.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Erfolgreich bestellt, leite zur Bestätigungsseite weiter
            window.location.href = '../sites/bestellungsbestaetigung.html';
        } else {
            // Fehler beim Bestellen
            alert('Fehler beim Bestellen: ' + data.message);
        }
    })
    .catch(error => {
        alert('Ein Fehler ist aufgetreten: ' + error.message);
    });
}
