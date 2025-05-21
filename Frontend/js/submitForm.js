//Funktion zum Absenden des Registrierungsformulars
function submitForm() {
    if (validateForm()) { // Validierung aufrufen
        var form = document.getElementById('registrationForm');
        var formData = new FormData(form);
        var object = {};
        formData.forEach(function(value, key){
            object[key] = value;
        });
        var json = JSON.stringify(object);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../Backend/logic/register.php", true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (xhr.status == 200) {
                    alert(xhr.responseText);
                } else {
                    alert('Ein Fehler ist aufgetreten: ' + xhr.status);
                }
            }
        };
        xhr.send(json);
    }
}
