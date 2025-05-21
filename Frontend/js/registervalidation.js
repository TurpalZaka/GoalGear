// Function: Validate the registration form
function validateForm() {
    var passwort = document.getElementById("password").value;
    var passwortWiederholen = document.getElementById("password_repeat").value;

    if (passwort !== passwortWiederholen) {
        alert("Die Passwörter stimmen nicht überein");
        return false;
    }
    return true;
}
