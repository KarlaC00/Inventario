document.addEventListener("DOMContentLoaded", function () {
    // Cargar clientes al cargar la página
    loadClientes();
    var rolUsuario = document.querySelector('.user-info').getAttribute('data-rol-usuario'); // Obtener el rol del usuario

    // Buscar clientes al hacer clic en el botón de búsqueda
    document.getElementById("search-button").addEventListener("click", function () {
        searchClientes();
    });

    // Buscar clientes al presionar "Enter" en el campo de búsqueda
    document.getElementById("search-input").addEventListener("keypress", function (event) {
        if (event.keyCode === 13) {
            searchClientes();
        }
    });

    // Buscar clientes en tiempo real al escribir en el campo de búsqueda
    document.getElementById("search-input").addEventListener("input", function () {
        searchClientes();
    });

    // Función para cargar clientes y mostrarlos en la tabla
    function loadClientes() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText); // Analizar la respuesta JSON
                    displayClientes(data);
                } else {
                    console.error("Error al cargar clientes:", xhr.status);
                }
            }
        };

        xhr.open("GET", "../../php/contacto/read_cliente.php", true);
        xhr.send();
    }

    // Función para buscar clientes
    function searchClientes() {
        var searchTerm = document.getElementById("search-input").value.trim();
        if (searchTerm !== "") {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayClientes(data);
                    } else {
                        console.error("Error al buscar clientes:", xhr.status);
                    }
                }
            };

            xhr.open("GET", `../../php/contacto/search_cliente.php?search=${encodeURIComponent(searchTerm)}`, true);
            xhr.send();
        } else {
            loadClientes();
        }
    }

    // Función para mostrar clientes en la tabla
    function displayClientes(data) {
        var clientTableBody = document.getElementById("client-table-body");
        clientTableBody.innerHTML = "";

        data.forEach(function (client) {
            var estadoButton = client.Estado == 1
                ? '<button class="active toggle-status">Activo</button>'
                : '<button class="inactive toggle-status">Inactivo</button>';
            
            var row = `
                <tr data-id="${client.IdCliente}">
                    <td>${client.IdCliente}</td>
                    <td>${client.Nombre}</td>
                    <td>${client.Direccion}</td>
                    <td>${client.Correo}</td>
                    <td>${client.numeroTelefonico}</td>
                    <td>${client.TipoIdentificacion}</td>
                    <td>${client.numeroIdentificacion}</td>
                    <td>${estadoButton}</td>
                    <td>
                        <button class="delete" data-id="${client.IdCliente}"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>
                        <button class="edit"><img src="../../img/svg/edit.svg" alt="Editar"></button>
                    </td>
                </tr>`;
            clientTableBody.innerHTML += row;
        });
    }

    // Función para eliminar un cliente
    function deleteClient(clientId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    loadClientes();
                } else {
                    console.error("Error al eliminar cliente:", xhr.status);
                }
            }
        };

        xhr.open("POST", "../../php/contacto/delete_cliente.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(`idCliente=${encodeURIComponent(clientId)}`);
    }

    // Redirigir a la página para agregar un nuevo cliente
    document.getElementById("add-button").addEventListener("click", function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = "../../pagina/contacto/agregar_cliente.php";
    });

    // Redirigir a la página para editar un cliente
    document.getElementById("client-table-body").addEventListener("click", function (event) {
        if (event.target && event.target.matches(".edit")) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var selectedClientId = event.target.closest("tr").getAttribute("data-id");
            window.location.href = `../../pagina/contacto/actualizar_cliente.php?id=${selectedClientId}`;
        }
    });

    // Evento para eliminar un cliente al hacer clic en el botón de eliminar cliente
    document.getElementById("delete-button").addEventListener("click", function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        var selectedRow = document.querySelector("#client-table-body tr.selected");
        if (selectedRow) {
            var selectedClientId = selectedRow.getAttribute("data-id");
            var confirmation = confirm("¿Estás seguro de eliminar este cliente?");
            if (confirmation) {
                deleteClient(selectedClientId);
            }
        } else {
            alert("Por favor, selecciona un cliente para eliminar.");
        }
    });

    // Evento para eliminar un cliente al hacer clic en el botón de eliminar en la tabla
    document.getElementById("client-table-body").addEventListener("click", function (event) {
        if (event.target && event.target.matches(".delete")) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var confirmation = confirm("¿Estás seguro de eliminar este cliente?");
            if (confirmation) {
                var clientId = event.target.closest("tr").getAttribute("data-id");
                deleteClient(clientId);
            }
        }
    });

    // Evento para seleccionar una fila de la tabla
    document.getElementById("client-table-body").addEventListener("click", function (event) {
        var target = event.target.closest("tr");
        if (target) {
            target.classList.add("selected");
            var siblings = getSiblings(target);
            siblings.forEach(function (sibling) {
                sibling.classList.remove("selected");
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