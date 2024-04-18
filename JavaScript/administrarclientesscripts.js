document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const clientList = document.getElementById("clientList");
    const deleteClientBtn = document.getElementById("deleteClientBtn");
    let selectedClientId = null; // Variable para almacenar el ID del cliente seleccionado

    // Función para buscar clientes
    searchInput.addEventListener("keyup", function() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = clientList.querySelectorAll("tbody tr");

        rows.forEach(function(row) {
            const rowData = row.textContent.toLowerCase();
            if (rowData.includes(searchTerm)) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Event listener para el botón "Eliminar Cliente"
    deleteClientBtn.addEventListener("click", function() {
        if (selectedClientId) {
            deleteClient(selectedClientId);
        } else {
            alert("Por favor, selecciona un cliente para eliminar.");
        }
    });

    // Función para eliminar un cliente por su ID
    function deleteClient(clientId) {
        fetch('../Php/administrarclientes.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ clientId: clientId })
        }).then(response => {
            // Manejar la respuesta del servidor si es necesario
            loadClientList(); // Recargar la lista de clientes después de eliminar
            selectedClientId = null; // Limpiar la selección después de eliminar
        }).catch(error => {
            console.error('Error al eliminar el cliente:', error);
        });
    }

    // Event listener para los botones "Editar" y "Eliminar" en cada fila de cliente
    clientList.addEventListener("click", function(event) {
        if (event.target.classList.contains("edit-btn")) {
            const clientId = event.target.closest("tr").dataset.clientId;
            window.location.href = `../Html/editarcliente.html?id=${clientId}`;
        } else if (event.target.classList.contains("delete-btn")) {
            const clientId = event.target.closest("tr").dataset.clientId;
            deleteClient(clientId);
        }
    });

    // Función para cargar la lista de clientes desde el servidor
    function loadClientList() {
        fetch('../Php/administrarclientes.php')
            .then(response => response.json())
            .then(data => {
                // Limpiar la tabla antes de agregar los nuevos clientes
                clientList.querySelector("tbody").innerHTML = "";

                // Iterar sobre los datos recibidos y crear filas de tabla para cada cliente
                data.forEach(client => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${client.IdCliente}</td>
                        <td>${client.Nombre}</td>
                        <td>${client.Direccion}</td>
                        <td>${client.Correo}</td>
                        <td>${client.numeroTelefonico}</td>
                        <td>${client.TipoIdentificacion}</td>
                        <td>${client.numeroIdentificacion}</td>
                        <td><button class="status-btn ${client.Estado ? 'active' : 'inactive'}">${client.Estado ? 'Activo' : 'Inactivo'}</button></td>
                        <td>
                            <button class="edit-btn" data-client-id="ID_DEL_CLIENTE_AQUI">Editar</button>
                            <button class="delete-btn">Eliminar</button>
                        </td>
                    `;
                    row.dataset.clientId = client.IdCliente;
                    clientList.querySelector("tbody").appendChild(row);

                    // Agregar evento onclick a cada fila para seleccionar el cliente
                    row.addEventListener("click", function() {
                        selectClient(client.IdCliente);
                    });
                });
            })
            .catch(error => console.error('Error al cargar la lista de clientes:', error));
    }

    // Función para seleccionar un cliente
    function selectClient(clientId) {
        // Limpiar la selección anterior
        const selectedClient = clientList.querySelector("tr.selected");
        if (selectedClient) {
            selectedClient.classList.remove("selected");
        }

        // Seleccionar la nueva fila
        const newClient = clientList.querySelector(`tr[data-client-id='${clientId}']`);
        if (newClient) {
            newClient.classList.add("selected");
            selectedClientId = clientId; // Almacenar el ID del cliente seleccionado
        }
    }

    // Cargar la lista de clientes al cargar la página
    loadClientList();
});