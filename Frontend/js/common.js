document.addEventListener("DOMContentLoaded", function() {
  // Load the header
  var xhr = new XMLHttpRequest(); // Create XMLHttpRequest object

  xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
          document.getElementById('header-placeholder').innerHTML = xhr.responseText;
      }
  };

  xhr.open("GET", "../components/header.html", true); // Setup the request
  xhr.send(); // Send the request

  
  updateCartCount();

});

function updateCartCount() {
  $.ajax({
      url: '../../Backend/logic/getCartCount.php',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
          if (data.success) {
              let totalItems = data.count;
              $('#cart-count').text(`(${totalItems})`); // Aktualisierung des Warenkorb-Anzeigers
          } else {
          }
      },
      error: function(error) {
      }
  });
}
