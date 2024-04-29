document.addEventListener("DOMContentLoaded", function () {
    loadCompras(); // Cargar todas las entradas al cargar la página
    var rolUsuario = document.querySelector('.user-info').getAttribute('data-rol-usuario'); // Obtener el rol del usuario

    // Buscar entradas al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchCompras();
    });

    // Buscar entradas al presionar "Enter" en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            searchCompras();
        }
    });

    // Buscar entradas en tiempo real al escribir en el campo de búsqueda
    document.getElementById('search-input').addEventListener('input', function () {
        searchCompras();
    });

    // Redirigir a la página para agregar una nueva entrada
    document.getElementById('add-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = '../../pagina/compra/agregar_compra.php';
    });

    // Redirigir a la página de detalles de entrada al hacer clic en el botón de detalles
    document.getElementById('compra-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.details')) {
            var selectedEntryId = event.target.closest('tr').getAttribute('data-id');
            window.location.href = '../../pagina/compra/detalles_compra.php?id=' + selectedEntryId;
        }
    });

    // Función para cargar las entradas y mostrarlas en la tabla
    function loadCompras() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText); // Analizar la respuesta JSON
                    displayCompras(data);
                } else {
                    console.error('Error al cargar entradas:', xhr.status);
                }
            }
        };

        xhr.open('GET', '../../php/compra/read_compra.php', true);
        xhr.send();
    }

    // Función para buscar entradas
    function searchCompras() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm !== '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayCompras(data);
                    } else {
                        console.error('Error al buscar entradas:', xhr.status);
                    }
                }
            };

            xhr.open('GET', `../../php/compra/search_compra.php?search=${encodeURIComponent(searchTerm)}`, true);
            xhr.send();
        } else {
            loadCompras();
        }
    }

    // Función para mostrar las entradas en la tabla
    function displayCompras(data) {
        var compraTableBody = document.getElementById('compra-table-body'); 
        compraTableBody.innerHTML = '';

        data.forEach(function (entry) {
            var row = `
            <tr data-id="${entry.IdEntrada}">
                <td>${entry.IdEntrada}</td>
                <td>${entry.FechaEntrada}</td>
                <td>${entry.ProveedorNombre}</td>
                <td>${entry.UsuarioNombre}</td>
                <td>${entry.Productos}</td>
                <td>${entry.PrecioTotal}</td>
                <td>
                    <button class="details">Detalles</button>
                </td>
            </tr>`;
            compraTableBody.innerHTML += row;
        });
    }
});