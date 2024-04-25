document.addEventListener("DOMContentLoaded", function () {
    // Función para cargar usuarios al cargar la página
    loadUsers();

    // Función para buscar usuarios al presionar Enter en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode == 13) {
            searchUsers();
        }
    });

    // Función para buscar usuarios al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchUsers();
    });

    // Función para eliminar usuario al hacer clic en el botón de eliminar
    document.getElementById('delete-button').addEventListener('click', function () {
        var selectedRow = document.querySelector('#user-table-body tr.selected');
        if (selectedRow) {
            var selectedUserId = selectedRow.getAttribute('data-id');
            var confirmation = confirm('¿Estás seguro de eliminar este usuario?');
            if (confirmation) {
                deleteUser(selectedUserId);
            }
        } else {
            alert('Por favor, selecciona un usuario para eliminar.');
        }
    });

    // Función para redireccionar a la página de agregar usuario al hacer clic en el botón de agregar usuario
    document.getElementById('add-button').addEventListener('click', function () {
        window.location.href = '../../pagina/administrar_acceso/agregar_usuario.php';
    });

    // Función para redireccionar a la página de edición de usuario al hacer clic en el botón de editar
    document.getElementById('user-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.edit')) {
            var selectedUserId = event.target.parentElement.parentElement.getAttribute('data-id');
            window.location.href = '../../pagina/administrar_acceso/actualizar_usuario.php?id=' + selectedUserId;
        }
    });

    // Función para cargar usuarios desde la base de datos y mostrarlos en la tabla
    function loadUsers() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    displayUsers(data);
                } else {
                    console.error('Error al cargar usuarios:', xhr.status);
                }
            }
        };
        xhr.open('GET', '../../php/administrar_acceso/read_usuario.php', true);
        xhr.send();
    }

    // Función para buscar usuarios por nombre
    function searchUsers() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm != '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayUsers(data);
                    } else {
                        console.error('Error al buscar usuarios:', xhr.status);
                    }
                }
            };
            xhr.open('GET', '../../php/administrar_acceso/search_usuario.php?search=' + encodeURIComponent(searchTerm), true);
            xhr.send();
        } else {
            loadUsers();
        }
    }

    // Función para eliminar un usuario
    function deleteUser(userId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    loadUsers();
                } else {
                    console.error('Error al eliminar usuario:', xhr.status);
                }
            }
        };
        xhr.open('POST', '../../php/administrar_acceso/delete_usuario.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('idUsuario=' + userId);
    }

    // Función para mostrar usuarios en la tabla
    function displayUsers(data) {
        var userTableBody = document.getElementById('user-table-body');
        userTableBody.innerHTML = '';
        data.forEach(function (user) {
            var estadoButton = user.Estado == 1 ? '<button class="active">Activo</button>' : '<button class="inactive">Inactivo</button>';
            var row = '<tr data-id="' + user.IdUsuario + '">' +
                '<td>' + user.IdUsuario + '</td>' +
                '<td>' + user.Nombre + '</td>' +
                '<td>' + user.Direccion + '</td>' +
                '<td>' + user.Correo + '</td>' +
                '<td>' + user.numeroTelefonico + '</td>' +
                '<td>' + user.TipoIdentificacion + '</td>' +
                '<td>' + user.numeroIdentificacion + '</td>' +
                '<td>' + user.Usuario + '</td>' +
                '<td>' + user.Contrasena + '</td>' + // Agregar el campo de contraseña
                '<td>' + estadoButton + '</td>' +
                '<td>' + user.NivelAcceso + '</td>' +
                '<td>' +
                '<button class="delete" data-id="' + user.IdUsuario + '"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>' +
                '<button class="edit"><img src="../../img/svg/edit.svg" alt="Editar"></button>' +
                '</td>' +
                '</tr>';
            userTableBody.innerHTML += row;
        });
    }

    // Evento de clic en el botón de eliminar dentro de la tabla
    document.getElementById('user-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            var confirmation = confirm('¿Estás seguro de eliminar este usuario?');
            if (confirmation) {
                var userId = event.target.getAttribute('data-id');
                deleteUser(userId);
            }
        }
    });

    // Evento de clic en una fila de la tabla para seleccionarla
    document.getElementById('user-table-body').addEventListener('click', function (event) {
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