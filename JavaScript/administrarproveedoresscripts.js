document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const providerList = document.getElementById("providerList");
    const deleteProviderBtn = document.getElementById("deleteProviderBtn");
    let selectedProviderId = null; // Variable para almacenar el ID del proveedor seleccionado

    // Función para buscar proveedores
    searchInput.addEventListener("keyup", function() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = providerList.querySelectorAll("tbody tr");

        rows.forEach(function(row) {
            const rowData = row.textContent.toLowerCase();
            if (rowData.includes(searchTerm)) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Event listener para el botón "Eliminar Proveedor"
    deleteProviderBtn.addEventListener("click", function() {
        if (selectedProviderId) {
            deleteProvider(selectedProviderId);
        } else {
            alert("Por favor, selecciona un proveedor para eliminar.");
        }
    });

    // Función para eliminar un proveedor por su ID
    function deleteProvider(providerId) {
        fetch('../Php/administrarproveedores.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ providerId: providerId })
        }).then(response => {
            // Manejar la respuesta del servidor si es necesario
            loadProviderList(); // Recargar la lista de proveedores después de eliminar
            selectedProviderId = null; // Limpiar la selección después de eliminar
        }).catch(error => {
            console.error('Error al eliminar el proveedor:', error);
        });
    }

    // Event listener para los botones "Editar" y "Eliminar" en cada fila de proveedor
    providerList.addEventListener("click", function(event) {
        if (event.target.classList.contains("edit-btn")) {
            const providerId = event.target.closest("tr").dataset.providerId;
            window.location.href = `../Html/editarproveedor.html?id=${providerId}`;
        } else if (event.target.classList.contains("delete-btn")) {
            const providerId = event.target.closest("tr").dataset.providerId;
            deleteProvider(providerId);
        }
    });

    // Función para cargar la lista de proveedores desde el servidor
    function loadProviderList() {
        fetch('../Php/administrarproveedores.php')
            .then(response => response.json())
            .then(data => {
                // Limpiar la tabla antes de agregar los nuevos proveedores
                providerList.querySelector("tbody").innerHTML = "";

                // Iterar sobre los datos recibidos y crear filas de tabla para cada proveedor
                data.forEach(provider => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${provider.IdProveedor}</td>
                        <td>${provider.Nombre}</td>
                        <td>${provider.Direccion}</td>
                        <td>${provider.Correo}</td>
                        <td>${provider.numeroTelefonico}</td>
                        <td>${provider.TipoIdentificacion}</td>
                        <td>${provider.numeroIdentificacion}</td>
                        <td><button class="status-btn ${provider.Estado ? 'active' : 'inactive'}">${provider.Estado ? 'Activo' : 'Inactivo'}</button></td>
                        <td>
                            <button class="edit-btn" data-provider-id="ID_DEL_PROVEEDOR_AQUI">Editar</button>
                            <button class="delete-btn">Eliminar</button>
                        </td>
                    `;
                    row.dataset.providerId = provider.IdProveedor;
                    providerList.querySelector("tbody").appendChild(row);

                    // Agregar evento onclick a cada fila para seleccionar el proveedor
                    row.addEventListener("click", function() {
                        selectProvider(provider.IdProveedor);
                    });
                });
            })
            .catch(error => console.error('Error al cargar la lista de proveedores:', error));
    }

    // Función para seleccionar un proveedor
    function selectProvider(providerId) {
        // Limpiar la selección anterior
        const selectedProvider = providerList.querySelector("tr.selected");
        if (selectedProvider) {
            selectedProvider.classList.remove("selected");
        }

        // Seleccionar la nueva fila
        const newProvider = providerList.querySelector(`tr[data-provider-id='${providerId}']`);
        if (newProvider) {
            newProvider.classList.add("selected");
            selectedProviderId = providerId; // Almacenar el ID del proveedor seleccionado
        }
    }

    // Cargar la lista de proveedores al cargar la página
    loadProviderList();
});