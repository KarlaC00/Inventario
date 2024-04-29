document.addEventListener("DOMContentLoaded", function () {
    // Cargar proveedores al cargar la página
    loadProviders();
    var rolUsuario = document.querySelector('.user-info').getAttribute('data-rol-usuario'); // Obtener el rol del usuario

    // Buscar proveedores al hacer clic en el botón de búsqueda
    document.getElementById("search-button").addEventListener("click", function () {
        searchProviders();
    });

    // Buscar proveedores al presionar "Enter" en el campo de búsqueda
    document.getElementById("search-input").addEventListener("keypress", function (event) {
        if (event.keyCode === 13) {
            searchProviders();
        }
    });

    // Buscar proveedores en tiempo real al escribir en el campo de búsqueda
    document.getElementById("search-input").addEventListener("input", function () {
        searchProviders();
    });

    // Función para cargar proveedores y mostrarlos en la tabla
    function loadProviders() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    displayProviders(data);
                } else {
                    console.error("Error al cargar proveedores:", xhr.status);
                }
            }
        };

        xhr.open("GET", "../../php/contacto/read_proveedor.php", true);
        xhr.send();
    }

    // Función para buscar proveedores
    function searchProviders() {
        var searchTerm = document.getElementById("search-input").value.trim();
        if (searchTerm !== "") {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayProviders(data);
                    } else {
                        console.error("Error al buscar proveedores:", xhr.status);
                    }
                }
            };

            xhr.open("GET", `../../php/contacto/search_proveedor.php?search=${encodeURIComponent(searchTerm)}`, true);
            xhr.send();
        } else {
            loadProviders();
        }
    }

    // Función para mostrar proveedores en la tabla
    function displayProviders(data) {
        var providerTableBody = document.getElementById("provider-table-body");
        providerTableBody.innerHTML = "";

        data.forEach(function (provider) {
            var estadoButton = provider.Estado == 1
                ? '<button class="active toggle-status">Activo</button>'
                : '<button class="inactive toggle-status">Inactivo</button>';

            var row = `
            <tr data-id="${provider.IdProveedor}">
                <td>${provider.IdProveedor}</td>
                <td>${provider.Nombre}</td>
                <td>${provider.Direccion}</td>
                <td>${provider.Correo}</td>
                <td>${provider.numeroTelefonico}</td>
                <td>${provider.TipoIdentificacion}</td>
                <td>${provider.numeroIdentificacion}</td>
                <td>${estadoButton}</td>
                <td>
                    <button class="delete" data-id="${provider.IdProveedor}"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>
                    <button class="edit"><img src="../../img/svg/edit.svg" alt="Editar"></button>
                </td>
            </tr>`;
            providerTableBody.innerHTML += row;
        });
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
                    console.error("Error al eliminar proveedor:", xhr.status);
                }
            }
        };

        xhr.open("POST", "../../php/contacto/delete_proveedor.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(`idProveedor=${encodeURIComponent(providerId)}`);
    }

    // Redirigir a la página para agregar un nuevo proveedor
    document.getElementById("add-button").addEventListener("click", function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = "../../pagina/contacto/agregar_proveedor.php";
    });

    // Redirigir a la página para editar un proveedor
    document.getElementById("provider-table-body").addEventListener("click", function (event) {
        if (event.target && event.target.matches(".edit")) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var selectedProviderId = event.target.closest("tr").getAttribute("data-id");
            window.location.href = `../../pagina/contacto/actualizar_proveedor.php?id=${selectedProviderId}`;
        }
    });

    // Evento para eliminar un proveedor al hacer clic en el botón de eliminar proveedor
    document.getElementById("delete-button").addEventListener("click", function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        var selectedRow = document.querySelector("#provider-table-body tr.selected");
        if (selectedRow) {
            var selectedProviderId = selectedRow.getAttribute("data-id");
            var confirmation = confirm("¿Estás seguro de eliminar este proveedor?");
            if (confirmation) {
                deleteProvider(selectedProviderId);
            }
        } else {
            alert("Por favor, selecciona un proveedor para eliminar.");
        }
    });

    // Evento para eliminar un proveedor al hacer clic en el botón de eliminar en la tabla
    document.getElementById("provider-table-body").addEventListener("click", function (event) {
        if (event.target && event.target.matches(".delete")) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var confirmation = confirm("¿Estás seguro de eliminar este proveedor?");
            if (confirmation) {
                var providerId = event.target.closest("tr").getAttribute("data-id");
                deleteProvider(providerId);
            }
        }
    });

    // Evento para seleccionar una fila de la tabla
    document.getElementById("provider-table-body").addEventListener("click", function (event) {
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