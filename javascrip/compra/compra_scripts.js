document.addEventListener("DOMContentLoaded", function () {
    // Cargar todas las entradas al cargar la página
    loadCompras();

    // Buscar entradas al presionar Enter en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            searchCompras();
        }
    });

    // Buscar entradas al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchCompras();
    });

    // Redirigir a la página para agregar una nueva entrada
    document.getElementById('add-button').addEventListener('click', function () {
        window.location.href = '../../pagina/compra/agregar_compra.php';
    });

    // Función para redireccionar a la página de detalles de entrada al hacer clic en el botón de detalles
    document.getElementById('compra-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.details')) {
            var selectedEntryId = event.target.parentElement.parentElement.getAttribute('data-id');
            window.location.href = '../../pagina/compra/detalles_compra.php?id=' + selectedEntryId;
        }
    });    

    // Cargar las entradas desde la base de datos y mostrarlas en la tabla
    function loadCompras() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    displayCompras(data);
                } else {
                    console.error('Error al cargar entradas:', xhr.status);
                }
            }
        };
        xhr.open('GET', '../../php/compra/read_compra.php', true);
        xhr.send();
    }

    // Buscar entradas por nombre u otros atributos
    function searchCompras() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm != '') {
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
            xhr.open('GET', '../../php/compra/search_compra.php?search=' + encodeURIComponent(searchTerm), true);
            xhr.send();
        } else {
            loadCompras(); // Si el campo de búsqueda está vacío, cargar todas las entradas
        }
    }

    // Mostrar las entradas en la tabla
    function displayCompras(data) {
        var compraTableBody = document.getElementById('compra-table-body');
        compraTableBody.innerHTML = '';
        data.forEach(function (entry) {
            var row = '<tr data-id="' + entry.IdEntrada + '">' +
                '<td>' + entry.IdEntrada + '</td>' +
                '<td>' + entry.FechaEntrada + '</td>' +
                '<td>' + entry.ProveedorNombre + '</td>' +
                '<td>' + entry.UsuarioNombre + '</td>' +
                '<td>' + entry.Productos + '</td>' +
                '<td>' + entry.PrecioTotal + '</td>' +
                '<td>' +
                '<button class="details">Detalles</button>' +
                '</td>' +
                '</tr>';
            compraTableBody.innerHTML += row;
        });
    }
});