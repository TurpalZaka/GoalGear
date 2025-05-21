document.getElementById('voucherForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const value = document.getElementById('value').value;
    const expiryDate = document.getElementById('expiryDate').value;

    const voucherCode = generateVoucherCode();
    // fetch request um Gutschein zu erstellen
    fetch('../../Backend/logic/create_voucher.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        // Gutscheincode, Wert und Ablaufdatum an den Server senden
        body: JSON.stringify({
            code: voucherCode,
            value: value,
            expiryDate: expiryDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Gutschein erstellt: ' + voucherCode);
        } else {
            alert('Fehler beim Erstellen des Gutscheins.');
        }
    });
});

// generiert zufälligen 5 stelligen Code
function generateVoucherCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 5; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return code;
}
