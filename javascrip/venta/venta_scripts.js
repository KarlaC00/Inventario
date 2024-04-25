document.addEventListener("DOMContentLoaded", function () {
    // Cargar todas las salidas al cargar la página
    loadVentas();

    // Buscar salidas al presionar Enter en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            searchVentas();
        }
    });

    // Buscar salidas al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchVentas();
    });

    // Redirigir a la página para agregar una nueva salida
    document.getElementById('add-button').addEventListener('click', function () {
        window.location.href = '../../pagina/venta/agregar_venta.php';
    });

    // Función para redireccionar a la página de detalles de salida al hacer clic en el botón de detalles
    document.getElementById('venta-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.details')) {
            var selectedDepartureId = event.target.parentElement.parentElement.getAttribute('data-id');
            window.location.href = '../../pagina/venta/detalles_venta.php?id=' + selectedDepartureId;
        }
    });

    // Cargar las salidas desde la base de datos y mostrarlas en la tabla
    function loadVentas() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    displayVentas(data);
                } else {
                    console.error('Error al cargar ventas:', xhr.status);
                }
            }
        };
        xhr.open('GET', '../../php/venta/read_venta.php', true);
        xhr.send();
    }

    // Buscar salidas por nombre u otros atributos
    function searchVentas() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm != '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayVentas(data);
                    } else {
                        console.error('Error al buscar salidas:', xhr.status);
                    }
                }
            };
            xhr.open('GET', '../../php/venta/search_venta.php?search=' + encodeURIComponent(searchTerm), true);
            xhr.send();
        } else {
            loadVentas(); // Si el campo de búsqueda está vacío, cargar todas las salidas
        }
    }

    // Mostrar las salidas en la tabla
    function displayVentas(data) {
        var ventaTableBody = document.getElementById('venta-table-body');
        ventaTableBody.innerHTML = '';
        data.forEach(function (departure) {
            var row = '<tr data-id="' + departure.IdSalida + '">' +
                '<td>' + departure.IdSalida + '</td>' +
                '<td>' + departure.FechaSalida + '</td>' +
                '<td>' + departure.ClienteNombre + '</td>' +
                '<td>' + departure.UsuarioNombre + '</td>' +
                '<td>' + departure.Productos + '</td>' +
                '<td>' + departure.PrecioTotal + '</td>' +
                '<td>' +
                '<button class="details">Detalles</button>' +
                '</td>' +
                '</tr>';
            ventaTableBody.innerHTML += row;
        });
    }
});