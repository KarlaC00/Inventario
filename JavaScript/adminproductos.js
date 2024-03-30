// administraraccesosscripts.js
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const productList = document.getElementById("productList");
    const deleteProductBtn = document.getElementById("deleteProductBtn");
    let selectedProductId = null; // Variable para almacenar el ID del producto seleccionado

    // Función para buscar productos
    searchInput.addEventListener("keyup", function() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = productList.querySelectorAll("tbody tr");

        rows.forEach(function(row) {
            const rowData = row.textContent.toLowerCase();
            if (rowData.includes(searchTerm)) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Event listener para el botón "Eliminar Producto"
    deleteProductBtn.addEventListener("click", function() {
        if (selectedProductId) {
            deleteProduct(selectedProductId);
        } else {
            alert("Por favor, selecciona un producto para eliminar.");
        }
    });

    // Función para eliminar un producto por su ID
    function deleteProduct(productId) {
        fetch('../Php/administrarproductos.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ productId: productId })
        }).then(response => {
            // Manejar la respuesta del servidor si es necesario
            loadProductList(); // Recargar la lista de productos después de eliminar
            selectedProductId = null; // Limpiar la selección después de eliminar
        }).catch(error => {
            console.error('Error al eliminar el producto:', error);
        });
    }

    // Event listener para los botones "Editar" y "Eliminar" en cada fila de producto
    productList.addEventListener("click", function(event) {
        if (event.target.classList.contains("edit-btn")) {
            const productId = event.target.closest("tr").dataset.productId;
            window.location.href = `../Html/editarproducto.html?id=${productId}`;
        } else if (event.target.classList.contains("delete-btn")) {
            const productId = event.target.closest("tr").dataset.productId;
            deleteProduct(productId);
        }
    });

    // Función para cargar la lista de productos desde el servidor
    function loadProductList() {
        fetch('../Php/adminproductos.php')
            .then(response => response.json())
            .then(data => {
                // Limpiar la tabla antes de agregar los nuevos productos
                productList.querySelector("tbody").innerHTML = "";

                // Iterar sobre los datos recibidos y crear filas de tabla para cada producto
                data.forEach(product => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${product.IdProducto}</td>
                        <td>${product.Nombre}</td>
                        <td>${product.Imagen}</td>
                        <td>${product.Descripcion}</td>
                        <td>${product.Precio}</td>
                        <td>${product.CantidadDisponible}</td>
                        <td><button class="status-btn ${product.Estado ? 'active' : 'inactive'}">${product.Estado ? 'Activo' : 'Inactivo'}</button></td>
                        <td>
                            <button class="edit-btn" data-product-id="${product.IdProducto}">Editar</button>
                            <button class="delete-btn">Eliminar</button>
                        </td>
                    `;
                    row.dataset.productId = product.IdProducto;
                    productList.querySelector("tbody").appendChild(row);

                    // Agregar evento onclick a cada fila para seleccionar el producto
                    row.addEventListener("click", function() {
                        selectProduct(product.IdProducto);
                    });
                });
            })
            .catch(error => console.error('Error al cargar la lista de productos:', error));
    }

    // Función para seleccionar un producto
    function selectProduct(productId) {
        // Limpiar la selección anterior
        const selectedProduct = productList.querySelector("tr.selected");
        if (selectedProduct) {
            selectedProduct.classList.remove("selected");
        }

        // Seleccionar la nueva fila
        const newProduct = productList.querySelector(`tr[data-product-id='${productId}']`);
        if (newProduct) {
            newProduct.classList.add("selected");
            selectedProductId = productId; // Almacenar el ID del producto seleccionado
        }
    }

    // Cargar la lista de productos al cargar la página
    loadProductList();
});
