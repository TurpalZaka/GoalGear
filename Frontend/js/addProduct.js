document.getElementById('addProductForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    // FormData Objekt erstellen
    const formData = new FormData();
    const imageFile = document.getElementById('image').files[0];

    if (imageFile) {
        formData.append('image', imageFile);
// fetch request um Bild hochzuladen
        const imageUploadResponse = await fetch('../../Backend/logic/uploadImage.php', {
            method: 'POST',
            body: formData
        });
// Antwort des Servers als Text parsen
        const imageUploadText = await imageUploadResponse.text();
        // Antwort des Servers als JSON parsen
        if (imageUploadResponse.ok) {
            const imageData = JSON.parse(imageUploadText);
            const imageUrl = imageData.imageUrl;
            // Produkt Daten aus Formular holen
            const productData = {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value,
                category: document.getElementById('category').value,
                imageUrl: imageUrl
            };
            // fetch request um Produkt hinzuzufügen
            const productResponse = await fetch('../../Backend/logic/addProduct.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productData)
            });
            
            const productResponseText = await productResponse.text();
            const responseData = JSON.parse(productResponseText);
            // Antwort des Servers auswerten
            if (responseData.error) {
                alert(responseData.error);
            } else {
                alert(responseData.message);
                document.getElementById('addProductForm').reset();
            }
        } else {
            alert('Fehler beim Hochladen des Bildes');
        }
    } else {
        alert('Bitte ein Bild hochladen');
    }
});
