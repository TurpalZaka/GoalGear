$(document).ready(function() {
  loadProducts(); // Laden aller Produkte bei initialem Laden der Seite
  loadCategories(); // Laden der Kategorien bei initialem Laden der Seite

  // Funktion zum Laden und Anzeigen aller Produkte basierend auf der Kategorie
  function loadProducts(categoryId = null) {
      let url = '../../Backend/logic/getProducts.php';
      if (categoryId) {
          url += `?category_id=${categoryId}`;
      }

      $.ajax({
          url: url,
          method: 'GET',
          success: function(products) {
              var productsContainer = $('#products-container');
              productsContainer.empty();
              products.forEach(function(product) {
                  var productCard = `
                      <div class="col-md-4">
                          <div class="card mb-4">
                              <img src="${product.image}" class="card-img-top" alt="${product.name}">
                              <div class="card-body">
                                  <h5 class="card-title">${product.name}</h5>
                                  <p class="card-text">${product.description}</p>
                                  <p class="card-text"><strong>Preis: </strong>${product.price} €</p>
                                  <button class="btn btn-primary edit-product" data-id="${product.id}">Bearbeiten</button>
                                  <button class="btn btn-danger delete-product" data-id="${product.id}">Löschen</button>
                              </div>
                          </div>
                      </div>`;
                  productsContainer.append(productCard);
              });

              // Event-Listener für Bearbeiten-Schaltflächen
              $('.edit-product').on('click', function() {
                  var productId = $(this).data('id');
                  loadProductDetails(productId);
              });

              // Event-Listener für Lösch-Schaltflächen
              $('.delete-product').on('click', function() {
                  var productId = $(this).data('id');
                  deleteProduct(productId, categoryId);
              });
          },
         
      });
  }

  // Funktion zum Laden der Kategorien in die Dropdown-Menüs
  function loadCategories() {
      fetch('../../Backend/logic/getCategories.php')
          .then(response => {
              if (!response.ok) {
                  throw new Error('Netzwerkantwort war nicht in Ordnung');
              }
              return response.json();
          })
          .then(data => {
              let categorySelect = $('#category');
              let editCategorySelect = $('#editCategory');
              categorySelect.empty();
              editCategorySelect.empty();

              data.forEach(category => {
                  let option = `<option value="${category.id}">${category.name}</option>`;
                  categorySelect.append(option);
                  editCategorySelect.append(option);
              });
          })
         
  }

  // Funktion zum Laden der Produktdetails in das Bearbeitungsformular
  function loadProductDetails(productId) {
      $.ajax({
          url: '../../Backend/logic/getProductDetails.php', // URL zum Abrufen der Produktdetails
          method: 'GET',
          data: { id: productId },
          success: function(product) {
              $('#editProductId').val(product.id);
              $('#editName').val(product.name);
              $('#editDescription').val(product.description);
              $('#editPrice').val(product.price);
              $('#editCategory').val(product.category_id); // Setzen Sie den Wert der Kategorie
              $('#editProductModal').modal('show'); // Modal anzeigen
          },
          error: function(error) {
          }
      });
  }

  // Funktion zum Aktualisieren eines Produkts
  $('#editProductForm').on('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData();
      const imageFile = document.getElementById('editImage').files[0];
      const productId = $('#editProductId').val();

      if (imageFile) {
          formData.append('image', imageFile);

          const imageUploadResponse = await fetch('../../Backend/logic/uploadImage.php', {
              method: 'POST',
              body: formData
          });

          const imageUploadText = await imageUploadResponse.text();
          if (imageUploadResponse.ok) {
              const imageData = JSON.parse(imageUploadText);
              const imageUrl = imageData.imageUrl;
              updateProduct(productId, imageUrl);
          } else {
              alert('Fehler beim Hochladen des Bildes');
          }
      } else {
          updateProduct(productId);
      }
  });

  async function updateProduct(productId, imageUrl = null) {
      const productData = {
          id: productId,
          name: $('#editName').val(),
          description: $('#editDescription').val(),
          price: $('#editPrice').val(),
          category: $('#editCategory').val(),
          imageUrl: imageUrl
      };

      $.ajax({
          url: '../../Backend/logic/updateProduct.php', // URL zum Aktualisieren des Produkts
          method: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(productData),
          success: function(response) {
              if (response.error) {
                  alert(response.error);
              } else {
                  $('#editProductModal').modal('hide');
                  loadProducts(); // Produkte neu laden, nachdem eines aktualisiert wurde
              }
          },
          
      });
  }

  // Funktion zum Löschen eines Produkts
  function deleteProduct(productId, categoryId = null) {
      $.ajax({
          url: '../../Backend/logic/deleteProduct.php', // URL zum Löschen des Produkts
          method: 'POST',
          data: { id: productId },
          success: function(response) {
              if (response.error) {
                  alert(response.error);
              } else {
                  loadProducts(categoryId); // Produkte neu laden, nachdem eines gelöscht wurde
              }
          },
         
      });
  }
});
