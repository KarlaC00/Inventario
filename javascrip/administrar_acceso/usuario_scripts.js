document.addEventListener("DOMContentLoaded", function () {
    loadUsers(); // Cargar usuarios al cargar la página
    var rolUsuario = document.querySelector('.user-info').getAttribute('data-rol-usuario'); // Obtener el rol del usuario

    // Buscar usuarios al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchUsers();
    });

    // Buscar usuarios al presionar "Enter" en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            searchUsers();
        }
    });

    // Buscar usuarios en tiempo real al escribir en el campo de búsqueda
    document.getElementById('search-input').addEventListener('input', function () {
        searchUsers();
    });

    // Función para cargar usuarios y mostrarlos en la tabla
    function loadUsers() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText); // Analizar la respuesta JSON
                    displayUsers(data);
                } else {
                    console.error('Error al cargar usuarios:', xhr.status);
                }
            }
        };

        xhr.open('GET', '../../php/administrar_acceso/read_usuario.php', true);
        xhr.send();
    }

    // Función para buscar usuarios
    function searchUsers() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm !== '') {
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

            xhr.open('GET', `../../php/administrar_acceso/search_usuario.php?search=${encodeURIComponent(searchTerm)}`, true); // Solicitud GET con el término de búsqueda
            xhr.send();
        } else {
            loadUsers();
        }
    }

    // Función para mostrar usuarios en la tabla
    function displayUsers(data) {
        var userTableBody = document.getElementById('user-table-body');
        userTableBody.innerHTML = '';

        data.forEach(function (user) {
            var estadoButton = user.Estado == 1 ?
                '<button class="active toggle-status">Activo</button>' :
                '<button class="inactive toggle-status">Inactivo</button>';
            var row = `
            <tr data-id="${user.IdUsuario}">
                <td>${user.IdUsuario}</td>
                <td>${user.Nombre}</td>
                <td>${user.Direccion}</td>
                <td>${user.Correo}</td>
                <td>${user.numeroTelefonico}</td>
                <td>${user.TipoIdentificacion}</td>
                <td>${user.numeroIdentificacion}</td>
                <td>${user.Usuario}</td>
                <td>${user.Contrasena}</td> 
                <td>${estadoButton}</td> 
                <td>${user.NivelAcceso}</td> 
                <td>
                    <button class="delete"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>
                    <button class="edit"><img src="../../img/svg/edit.svg" alt="Editar"></button>
                </td>
            </tr>`;
            userTableBody.innerHTML += row;
        });
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
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(`idUsuario=${encodeURIComponent(userId)}`);
    }

    // Redirigir a la página para agregar un nuevo usuario
    document.getElementById('add-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = '../../pagina/administrar_acceso/agregar_usuario.php';
    });

    // Redirigir a la página para editar un usuario
    document.getElementById('user-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.edit')) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var selectedUserId = event.target.closest('tr').getAttribute('data-id');
            window.location.href = '../../pagina/administrar_acceso/actualizar_usuario.php?id=' + selectedUserId;
        }
    });

    // Evento para eliminar un usuario al hacer clic en el botón de eliminar usuario
    document.getElementById('delete-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
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

    // Evento para eliminar un usuario al hacer clic en el botón de eliminar
    document.getElementById('user-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var confirmation = confirm('¿Estás seguro de eliminar este usuario?');
            if (confirmation) {
                var userId = event.target.closest('tr').getAttribute('data-id');
                deleteUser(userId);
            }
        }
    });

    // Evento para seleccionar una fila de la tabla
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