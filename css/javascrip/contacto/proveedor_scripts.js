document.addEventListener("DOMContentLoaded", function () {
    // Función para cargar proveedores al cargar la página
    loadProviders();

    // Función para buscar proveedores al presionar Enter en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode == 13) {
            searchProviders();
        }
    });

    // Función para buscar proveedores al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchProviders();
    });

    // Función para eliminar proveedor al hacer clic en el botón de eliminar
    document.getElementById('delete-button').addEventListener('click', function () {
        var selectedRow = document.querySelector('#provider-table-body tr.selected');
        if (selectedRow) {
            var selectedProviderId = selectedRow.getAttribute('data-id');
            var confirmation = confirm('¿Estás seguro de eliminar este proveedor?');
            if (confirmation) {
                deleteProvider(selectedProviderId);
            }
        } else {
            alert('Por favor, selecciona un proveedor para eliminar.');
        }
    });

    // Función para redireccionar a la página de agregar proveedor al hacer clic en el botón de agregar proveedor
    document.getElementById('add-button').addEventListener('click', function () {
        window.location.href = '../../pagina/contacto/agregar_proveedor.php';
    });

    // Función para redireccionar a la página de edición de proveedor al hacer clic en el botón de editar
    document.getElementById('provider-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.edit')) {
            var selectedProviderId = event.target.parentElement.parentElement.getAttribute('data-id');
            window.location.href = '../../pagina/contacto/actualizar_proveedor.php?id=' + selectedProviderId;
        }
    });

    // Función para cargar proveedores desde la base de datos y mostrarlos en la tabla
    function loadProviders() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    displayProviders(data);
                } else {
                    console.error('Error al cargar proveedores:', xhr.status);
                }
            }
        };
        xhr.open('GET', '../../php/contacto/read_proveedor.php', true);
        xhr.send();
    }

    // Función para buscar proveedores por nombre
    function searchProviders() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm != '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayProviders(data);
                    } else {
                        console.error('Error al buscar proveedores:', xhr.status);
                    }
                }
            };
            xhr.open('GET', '../../php/contacto/search_proveedor.php?search=' + encodeURIComponent(searchTerm), true);
            xhr.send();
        } else {
            loadProviders();
        }
    }

    // Función para eliminar un proveedor
    function deleteProvider(providerId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    loadProviders();
                } else {
                    console.error('Error al eliminar proveedor:', xhr.status);
                }
            }
        };
        xhr.open('POST', '../../php/contacto/delete_proveedor.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('idProveedor=' + providerId);
    }

    // Función para mostrar proveedores en la tabla
    function displayProviders(data) {
        var providerTableBody = document.getElementById('provider-table-body');
        providerTableBody.innerHTML = '';
        data.forEach(function (provider) {
            var estadoButton = provider.Estado == 1 ? '<button class="active">Activo</button>' : '<button class="inactive">Inactivo</button>';
            var row = '<tr data-id="' + provider.IdProveedor + '">' +
                '<td>' + provider.IdProveedor + '</td>' +
                '<td>' + provider.Nombre + '</td>' +
                '<td>' + provider.Direccion + '</td>' +
                '<td>' + provider.Correo + '</td>' +
                '<td>' + provider.numeroTelefonico + '</td>' +
                '<td>' + provider.TipoIdentificacion + '</td>' +
                '<td>' + provider.numeroIdentificacion + '</td>' +
                '<td>' + estadoButton + '</td>' +
                '<td>' +
                '<button class="delete" data-id="' + provider.IdProveedor + '"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>' +
                '<button class="edit"><img src="../../img/svg/edit.svg" alt="Editar"></button>' +
                '</td>' +
                '</tr>';
            providerTableBody.innerHTML += row;
        });
    }

    // Evento de clic en el botón de eliminar dentro de la tabla
    document.getElementById('provider-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            var confirmation = confirm('¿Estás seguro de eliminar este proveedor?');
            if (confirmation) {
                var providerId = event.target.getAttribute('data-id');
                deleteProvider(providerId);
            }
        }
    });

    // Evento de clic en una fila de la tabla para seleccionarla
    document.getElementById('provider-table-body').addEventListener('click', function (event) {
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