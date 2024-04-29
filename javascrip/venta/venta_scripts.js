document.addEventListener("DOMContentLoaded", function () {
    loadVentas(); // Cargar todas las ventas al cargar la página
    var rolUsuario = document.querySelector('.user-info').getAttribute('data-rol-usuario'); // Obtener el rol del usuario

    // Buscar ventas al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchVentas();
    });

    // Buscar ventas al presionar "Enter" en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            searchVentas();
        }
    });

    // Buscar ventas en tiempo real mientras se escribe en el campo de búsqueda
    document.getElementById('search-input').addEventListener('input', function () {
        searchVentas();
    });

    // Redirigir a la página para agregar una nueva venta
    document.getElementById('add-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = '../../pagina/venta/agregar_venta.php';
    });

    // Redirigir a la página de detalles de venta al hacer clic en el botón de detalles
    document.getElementById('venta-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.details')) {
            var selectedEntryId = event.target.closest('tr').getAttribute('data-id');
            window.location.href = '../../pagina/venta/detalles_venta.php?id=' + selectedEntryId;
        }
    });

    // Función para cargar las ventas y mostrarlas en la tabla
    function loadVentas() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText); // Analizar la respuesta JSON
                    displayVentas(data);
                } else {
                    console.error('Error al cargar ventas:', xhr.status);
                }
            }
        };

        xhr.open('GET', '../../php/venta/read_venta.php', true);
        xhr.send();
    }

    // Función para buscar ventas
    function searchVentas() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm !== '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayVentas(data);
                    } else {
                        console.error('Error al buscar ventas:', xhr.status);
                    }
                }
            };

            xhr.open('GET', `../../php/venta/search_venta.php?search=${encodeURIComponent(searchTerm)}`, true);
            xhr.send();
        } else {
            loadVentas();
        }
    }

    // Función para mostrar las ventas en la tabla
    function displayVentas(data) {
        var ventaTableBody = document.getElementById('venta-table-body');
        ventaTableBody.innerHTML = '';

        data.forEach(function (venta) {
            var row = `
            <tr data-id="${venta.IdSalida}">
                <td>${venta.IdSalida}</td>
                <td>${venta.FechaSalida}</td>
                <td>${venta.ClienteNombre}</td>
                <td>${venta.UsuarioNombre}</td>
                <td>${venta.Productos}</td>
                <td>${venta.PrecioTotal}</td>
                <td>
                    <button class="details">Detalles</button>
                </td>
            </tr>`;
            ventaTableBody.innerHTML += row;
        });
    }
});