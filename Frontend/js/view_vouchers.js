window.onload = function() {
    fetch('../../Backend/logic/get_vouchers.php')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById('voucherTableBody');
        data.forEach(voucher => {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${voucher.code}</td>
                             <td>${voucher.value}€</td>
                             <td>${voucher.expiry_date}</td>
                             <td>${voucher.status}</td>`;
            tableBody.appendChild(row);
        });
    });
};
