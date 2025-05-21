document.addEventListener('DOMContentLoaded', function() {
    checkLoginStatus();
});

// Funktion zum Senden des Anmeldeformulars
function submitLoginForm() {
    var usernameEmail = document.getElementById('username_email').value;
    var password = document.getElementById('password').value;
    var rememberMe = document.getElementById('remember_me').checked;

    var loginData = {
        username_email: usernameEmail,
        password: password,
        remember_me: rememberMe
    }

    fetch('../../Backend/logic/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(loginData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Fehler beim Senden der Anmeldeinformationen');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            window.location.href = '../sites/index.html'; 
        } else {
            alert(data.message); // Popup mit der Fehlermeldung
        }
    })
    .catch(error => {
        alert('Ein Fehler ist aufgetreten: ' + error.message);
    });
}

//NAVBAR LOGIN CHECK hier wird navbar angepasst
function checkLoginStatus() {
    fetch('../../Backend/logic/checkLogin.php')
    .then(response => response.json())
    .then(data => {

        if (data.loggedin) {
            document.querySelector('.login').style.display = 'none';
            document.querySelector('.register').style.display = 'none';
            document.querySelector('.profil').style.display = 'block';
            document.querySelector('.logout').style.display = 'block';
            document.querySelector('.warenkorb').style.display = 'block';
            document.querySelector('.orderHistory').style.display = 'block';
            if (data.admin) {
                document.querySelector('.admin').style.display = 'block';
            } else {
                document.querySelector('.admin').style.display = 'none';
            }
        } else {
            document.querySelector('.login').style.display = 'block';
            document.querySelector('.register').style.display = 'block';
            document.querySelector('.profil').style.display = 'none';
            document.querySelector('.logout').style.display = 'none';
            document.querySelector('.warenkorb').style.display = 'block';
            document.querySelector('.admin').style.display = 'none';
            document.querySelector('.orderHistory').style.display = 'none';
        }
    })
    .catch(error => {
    });
}

function logout() {
    fetch('../../Backend/logic/logout.php')
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.href = '../sites/index.html'; // Redirect to index.html after logout
    })
    .catch(error => {
    });
}
