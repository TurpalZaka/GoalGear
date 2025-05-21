document.addEventListener('DOMContentLoaded', fetchUserProfile);

// Funktion zum Laden der Benutzerdaten
function fetchUserProfile() {
  fetch('../../Backend/logic/userProfile.php')
  .then(response => response.json())
  .then(data => {
      if (data.error) {
          alert(data.error);
      } else {
          document.getElementById('salutation').value = data.salutation || '';
          document.getElementById('first_name').value = data.first_name || '';
          document.getElementById('last_name').value = data.last_name || '';
          document.getElementById('email').value = data.email || '';
          document.getElementById('address').value = data.address || '';
          document.getElementById('city').value = data.city || '';
          document.getElementById('postal_code').value = data.postal_code || '';
      }
  })
  .catch(error => {
      alert('Ein Fehler ist aufgetreten beim Laden der Benutzerdaten.');
  });
}

// Funktion zum Absenden des Profilformulars
function submitProfileForm() {
  var formData = new FormData(document.getElementById('profileForm'));

  fetch('../../Backend/logic/updateProfile.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.status === 'success') {
          alert('Profil erfolgreich aktualisiert.');
          fetchUserProfile();
      } else {
          alert('Fehler beim Aktualisieren des Profils: ' + data.message);
      }
  })
  .catch(error => {
      alert('Ein Fehler ist aufgetreten.');
  });
}
