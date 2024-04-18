// administraraccesosscripts.js
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const userList = document.getElementById("userList");
    const deleteUserBtn = document.getElementById("deleteUserBtn");
    let selectedUserId = null; // Variable para almacenar el ID del usuario seleccionado

    // Función para buscar usuarios
    searchInput.addEventListener("keyup", function() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = userList.querySelectorAll("tbody tr");

        rows.forEach(function(row) {
            const rowData = row.textContent.toLowerCase();
            if (rowData.includes(searchTerm)) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Event listener para el botón "Eliminar Usuario"
    deleteUserBtn.addEventListener("click", function() {
        if (selectedUserId) {
            deleteUser(selectedUserId);
        } else {
            alert("Por favor, selecciona un usuario para eliminar.");
        }
    });

    // Función para eliminar un usuario por su ID
    function deleteUser(userId) {
        fetch('../Php/administraraccesos.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ userId: userId })
        }).then(response => {
            // Manejar la respuesta del servidor si es necesario
            loadUserList(); // Recargar la lista de usuarios después de eliminar
            selectedUserId = null; // Limpiar la selección después de eliminar
        }).catch(error => {
            console.error('Error al eliminar el usuario:', error);
        });
    }

    // Event listener para los botones "Editar" y "Eliminar" en cada fila de usuario
    userList.addEventListener("click", function(event) {
        if (event.target.classList.contains("edit-btn")) {
            const userId = event.target.closest("tr").dataset.userId;
            window.location.href = `../Html/editarusuario.html?id=${userId}`;
        } else if (event.target.classList.contains("delete-btn")) {
            const userId = event.target.closest("tr").dataset.userId;
            deleteUser(userId);
        }
    });

    // Función para cargar la lista de usuarios desde el servidor
    function loadUserList() {
        fetch('../Php/administraraccesos.php')
            .then(response => response.json())
            .then(data => {
                // Limpiar la tabla antes de agregar los nuevos usuarios
                userList.querySelector("tbody").innerHTML = "";

                // Iterar sobre los datos recibidos y crear filas de tabla para cada usuario
                data.forEach(user => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${user.IdUsuario}</td>
                        <td>${user.Nombre}</td>
                        <td>${user.Direccion}</td>
                        <td>${user.Correo}</td>
                        <td>${user.numeroTelefonico}</td>
                        <td>${user.TipoIdentificacion}</td>
                        <td>${user.numeroIdentificacion}</td>
                        <td>${user.Usuario}</td>
                        <td>${user.Contrasena}</td>
                        <td>${user.nivelAcceso_IdnivelAcceso}</td>
                        <td><button class="status-btn ${user.Estado ? 'active' : 'inactive'}">${user.Estado ? 'Activo' : 'Inactivo'}</button></td>
                        <td>
                            <button class="edit-btn" data-user-id="ID_DEL_USUARIO_AQUI">Editar</button>
                            <button class="delete-btn">Eliminar</button>
                        </td>
                    `;
                    row.dataset.userId = user.IdUsuario;
                    userList.querySelector("tbody").appendChild(row);

                    // Agregar evento onclick a cada fila para seleccionar el usuario
                    row.addEventListener("click", function() {
                        selectUser(user.IdUsuario);
                    });
                });
            })
            .catch(error => console.error('Error al cargar la lista de usuarios:', error));
    }

    // Función para seleccionar un usuario
    function selectUser(userId) {
        // Limpiar la selección anterior
        const selectedUser = userList.querySelector("tr.selected");
        if (selectedUser) {
            selectedUser.classList.remove("selected");
        }

        // Seleccionar la nueva fila
        const newUser = userList.querySelector(`tr[data-user-id='${userId}']`);
        if (newUser) {
            newUser.classList.add("selected");
            selectedUserId = userId; // Almacenar el ID del usuario seleccionado
        }
    }

    // Cargar la lista de usuarios al cargar la página
    loadUserList();
});