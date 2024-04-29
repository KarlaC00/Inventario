document.addEventListener("DOMContentLoaded", function () {
    // Cargar productos al cargar la página
    loadProducts();
    var rolUsuario = document.querySelector('.user-info').getAttribute('data-rol-usuario'); // Obtener el rol del usuario

    // Buscar productos al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchProducts();
    });

    // Buscar productos al presionar "Enter" en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            searchProducts();
        }
    });

    // Buscar productos en tiempo real mientras se escribe en el campo de búsqueda
    document.getElementById('search-input').addEventListener('input', function () {
        searchProducts();
    });

    // Función para cargar productos y mostrarlos en la tabla
    function loadProducts() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText); // Analizar la respuesta JSON
                    displayProducts(data);
                } else {
                    console.error('Error al cargar productos:', xhr.status);
                }
            }
        };

        xhr.open('GET', '../../php/producto/read_producto.php', true);
        xhr.send();
    }

    // Función para buscar productos
    function searchProducts() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm !== '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayProducts(data);
                    } else {
                        console.error('Error al buscar productos:', xhr.status);
                    }
                }
            };

            xhr.open('GET', `../../php/producto/search_producto.php?search=${encodeURIComponent(searchTerm)}`, true);
            xhr.send();
        } else {
            loadProducts();
        }
    }

    // Función para mostrar productos en la tabla
    function displayProducts(data) {
        var productTableBody = document.getElementById('product-table-body');
        productTableBody.innerHTML = '';

        data.forEach(function (product) {
            var estadoButton = product.Estado == 1 ?
                '<button class="active toggle-status">Activo</button>' :
                '<button class="inactive toggle-status">Inactivo</button>';
            var row = `
            <tr data-id="${product.IdProducto}">
                <td>${product.IdProducto}</td>
                <td>${product.Nombre}</td>
                <td>${product.Descripcion || ''}</td>
                <td><img src="data:image/jpeg;base64,${product.Imagen}" alt="Imagen de Producto" style="width: 100px;"></td>
                <td>${product.Precio}</td>
                <td>${product.CantidadDisponible}</td>
                <td>${estadoButton}</td> 
                <td>${product.Categoria}</td>
                <td>${product.Subcategoria}</td>
                <td>
                    <button class="delete" data-id="${product.IdProducto}"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>
                    <button class="edit"><img src="../../img/svg/edit.svg" alt="Editar"></button>
                </td>
            </tr>`;
            productTableBody.innerHTML += row;
        });
    }

    // Función para eliminar un producto
    function deleteProduct(productId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    loadProducts();
                } else {
                    console.error('Error al eliminar producto:', xhr.status);
                }
            }
        };

        xhr.open('POST', '../../php/producto/delete_producto.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(`idProducto=${encodeURIComponent(productId)}`);
    }

    // Redirigir a la página para agregar un nuevo producto
    document.getElementById('add-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = '../../pagina/producto/agregar_producto.php';
    });

    // Redirigir a la página para editar un producto al hacer clic en el botón de editar
    document.getElementById('product-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.edit')) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var selectedProductId = event.target.closest('tr').getAttribute('data-id');
            window.location.href = `../../pagina/producto/actualizar_producto.php?id=${selectedProductId}`;
        }
    });

    // Redirigir a la página para ver categorias
    document.getElementById('add-categoria').addEventListener('click', function () {
        window.location.href = '../../pagina/producto/categoria.php';
    });

    // Redirigir a la página para ver subcategorias
    document.getElementById('add-subcategoria').addEventListener('click', function () {
        window.location.href = '../../pagina/producto/subcategoria.php';
    });

    // Evento para eliminar un producto al hacer clic en el botón de eliminar producto
    document.getElementById('delete-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        var selectedRow = document.querySelector('#product-table-body tr.selected');
        if (selectedRow) {
            var selectedProductId = selectedRow.getAttribute('data-id');
            var confirmation = confirm('¿Estás seguro de eliminar este producto?');
            if (confirmation) {
                deleteProduct(selectedProductId);
            }
        } else {
            alert('Por favor, selecciona un producto para eliminar.');
        }
    });

    // Evento para eliminar un producto al hacer clic en el botón de eliminar
    document.getElementById('product-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var productRow = event.target.closest('tr');
            var productId = productRow.getAttribute('data-id');
            var confirmation = confirm(`¿Estás seguro de eliminar este producto`);

            if (confirmation) {
                deleteProduct(productId);
            }
        }
    });

    // Función para seleccionar una fila de la tabla
    document.getElementById('product-table-body').addEventListener('click', function (event) {
        var target = event.target.closest('tr');
        if (target) {
            target.classList.add('selected');
            var siblings = getSiblings(target); // Quitar selección de otras filas
            siblings.forEach(function (sibling) {
                sibling.classList.remove('selected');
            });
        }
    });

    // Función para obtener hermanos de un elemento
    function getSiblings(element) {
        var siblings = [];
        var sibling = element.parentNode.firstChild;
        while (sibling) {
            if (sibling.nodeType === 1 && sibling !== element) {
                siblings.push(sibling);
            }
            sibling = sibling.nextSibling;
        }
        return siblings;
    }
});