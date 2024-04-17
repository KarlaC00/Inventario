document.addEventListener("DOMContentLoaded", function () {
    // Función para cargar productos al cargar la página
    loadProducts();

    // Función para buscar productos al presionar Enter en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode == 13) {
            searchProducts();
        }
    });

    // Función para buscar productos al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchProducts();
    });

    // Función para eliminar producto al hacer clic en el botón de eliminar
    document.getElementById('delete-button').addEventListener('click', function () {
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

    // Función para redireccionar a la página de agregar producto al hacer clic en el botón de agregar producto
    document.getElementById('add-button').addEventListener('click', function () {
        window.location.href = '../../pagina/producto/agregar_producto.php';
    });

    // Función para redireccionar a la página de edición de producto al hacer clic en el botón de editar
    document.getElementById('product-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.edit')) {
            var selectedProductId = event.target.parentElement.parentElement.getAttribute('data-id');
            window.location.href = '../../pagina/producto/actualizar_producto.php?id=' + selectedProductId;
        }
    });

    // Función para redireccionar a la página de agregar categoria al hacer clic en el botón de agregar categoria
    document.getElementById('add-categoria').addEventListener('click', function () {
        window.location.href = '../../pagina/producto/categoria.php';
    });

    // Función para redireccionar a la página de agregar subcategoria al hacer clic en el botón de agregar subcategoria
    document.getElementById('add-subcategoria').addEventListener('click', function () {
        window.location.href = '../../pagina/producto/subcategoria.php';
    });

    // Función para cargar productos desde la base de datos y mostrarlos en la tabla
    function loadProducts() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    displayProducts(data);
                } else {
                    console.error('Error al cargar productos:', xhr.status);
                }
            }
        };
        xhr.open('GET', '../../php/producto/read_producto.php', true);
        xhr.send();
    }

    // Función para buscar productos por nombre
    function searchProducts() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm != '') {
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
            xhr.open('GET', '../../php/producto/search_producto.php?search=' + encodeURIComponent(searchTerm), true);
            xhr.send();
        } else {
            loadProducts();
        }
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
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('idProducto=' + productId);
    }

    // Función para mostrar productos en la tabla
    function displayProducts(data) {
        var productTableBody = document.getElementById('product-table-body');
        productTableBody.innerHTML = '';

        data.forEach(function (product) {
            var estadoButton = product.Estado == 1 ? '<button class="active">Activo</button>' : '<button class="inactive">Inactivo</button>';
            var row = '<tr data-id="' + product.IdProducto + '">' +
                '<td>' + product.IdProducto + '</td>' +
                '<td>' + product.Nombre + '</td>' +
                '<td>' + (product.Descripcion || '') + '</td>' +
                '<td><img src="data:image/jpeg;base64,' + product.Imagen + '" alt="Imagen de Producto" style="width: 100px;"></td>' +
                '<td>' + product.Precio + '</td>' +
                '<td>' + product.CantidadDisponible + '</td>' +
                '<td>' + estadoButton + '</td>' +
                '<td>' + product.Categoria + '</td>' +
                '<td>' + product.Subcategoria + '</td>' +
                '<td>' +
                '<button class="delete" data-id="' + product.IdProducto + '"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>' +
                '<button class="edit"><img src="../../img/svg/edit.svg" alt="Editar"></button>' +
                '</td>' +
                '</tr>';
            productTableBody.innerHTML += row;
        });
    }

    // Evento de clic en el botón de eliminar dentro de la tabla
    document.getElementById('product-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            var confirmation = confirm('¿Estás seguro de eliminar este producto?');
            if (confirmation) {
                var productId = event.target.getAttribute('data-id');
                deleteProduct(productId);
            }
        }
    });

    // Evento de clic en una fila de la tabla para seleccionarla
    document.getElementById('product-table-body').addEventListener('click', function (event) {
        var target = event.target.closest('tr');
        if (target) {
            target.classList.add('selected');
            var siblings = getSiblings(target);
            siblings.forEach(function (sibling) {
                sibling.classList.remove('selected');
            });
        }
    });

    // Función para obtener los elementos hermanos de un elemento dado
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