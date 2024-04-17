document.addEventListener("DOMContentLoaded", function () {
    // Cargar subcategorías al cargar la página inicialmente
    loadSubcategories();

    // Escuchar evento de presionar Enter en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            // Si la tecla presionada es Enter, buscar subcategorías
            searchSubcategories();
        }
    });

    // Escuchar evento de clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        // Al hacer clic en el botón de búsqueda, buscar subcategorías
        searchSubcategories();
    });

    // Escuchar evento de clic en el botón de eliminar subcategoría
    document.getElementById('delete-button').addEventListener('click', function () {
        // Obtener la fila seleccionada
        var selectedRow = document.querySelector('#subcategory-table-body tr.selected');
        if (selectedRow) {
            // Si hay una fila seleccionada, obtener el ID de la subcategoría
            var selectedSubcategoryId = selectedRow.getAttribute('data-id');
            var confirmation = confirm('¿Estás seguro de eliminar esta subcategoría?');
            if (confirmation) {
                // Si el usuario confirma la eliminación, proceder a eliminar la subcategoría
                deleteSubcategory(selectedSubcategoryId);
            }
        } else {
            // Si no hay una fila seleccionada, mostrar una alerta
            alert('Por favor, selecciona una subcategoría para eliminar.');
        }
    });

    // Escuchar evento de clic en el botón de agregar subcategoría
    document.getElementById('add-button').addEventListener('click', function () {
        // Redireccionar a la página para agregar una nueva subcategoría
        window.location.href = '../../pagina/producto/agregar_subcategoria.php';
    });

    // Función para cargar subcategorías desde el servidor y mostrarlas en la tabla
    function loadSubcategories() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Si la solicitud fue exitosa, mostrar las subcategorías
                    var data = JSON.parse(xhr.responseText);
                    displaySubcategories(data);
                } else {
                    console.error('Error al cargar subcategorías:', xhr.status);
                }
            }
        };
        xhr.open('GET', '../../php/producto/read_subcategoria.php', true);
        xhr.send();
    }

    // Función para buscar subcategorías por nombre
    function searchSubcategories() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm !== '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Si la búsqueda fue exitosa, mostrar los resultados
                        var data = JSON.parse(xhr.responseText);
                        displaySubcategories(data);
                    } else {
                        console.error('Error al buscar subcategorías:', xhr.status);
                    }
                }
            };
            xhr.open('GET', '../../php/producto/search_subcategoria.php?search=' + encodeURIComponent(searchTerm), true);
            xhr.send();
        } else {
            loadSubcategories();
        }
    }

    // Función para eliminar una subcategoría
    function deleteSubcategory(subcategoryId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Si la eliminación fue exitosa, mostrar un mensaje y recargar las subcategorías
                    alert(xhr.responseText);
                    loadSubcategories();
                } else {
                    console.error('Error al eliminar subcategoría:', xhr.status);
                }
            }
        };
        xhr.open('POST', '../../php/producto/delete_subcategoria.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('idSubcategoria=' + subcategoryId);
    }

    // Función para mostrar las subcategorías en la tabla
    function displaySubcategories(data) {
        var subcategoryTableBody = document.getElementById('subcategory-table-body');
        subcategoryTableBody.innerHTML = '';

        // Recorrer los datos de las subcategorías y agregar filas a la tabla
        data.forEach(function (subcategory) {
            var estadoLabel = subcategory.Estado == 1 ? '<button class="active">Activo</button>' : '<button class="inactive">Inactivo</button>'; // Determinar el estado como Activo o Inactivo
            var row = '<tr data-id="' + subcategory.IdSubcategoria + '">' +
                '<td>' + subcategory.IdSubcategoria + '</td>' +
                '<td>' + subcategory.SubcategoriaNombre + '</td>' +
                '<td>' + estadoLabel + '</td>' +
                '<td>' + subcategory.CategoriaNombre + '</td>' +
                '<td>' +
                '<button class="delete" data-id="' + subcategory.IdSubcategoria + '">Eliminar</button>' +
                '<button class="edit" data-id="' + subcategory.IdSubcategoria + '">Editar</button>' +
                '</td>' +
                '</tr>';
            subcategoryTableBody.innerHTML += row;
        });

        // Agregar eventos a los botones de "Editar"
        var editButtons = subcategoryTableBody.querySelectorAll('.edit');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var subcategoryId = button.getAttribute('data-id');
                // Lógica para manejar la acción de editar la subcategoría (redireccionar a la página de edición, etc.)
                editSubcategory(subcategoryId);
            });
        });
    }

    // Función para redirigir a la página de edición de subcategoría
    function editSubcategory(subcategoryId) {
        // Redirigir a la página de edición de subcategoría con el ID proporcionado
        window.location.href = '../../pagina/producto/actualizar_subcategoria.php?id=' + subcategoryId;
    }

    // Escuchar evento de clic en el botón de eliminar dentro de la tabla
    document.getElementById('subcategory-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            var confirmation = confirm('¿Estás seguro de eliminar esta subcategoría?');
            if (confirmation) {
                // Obtener el ID de la subcategoría al hacer clic en el botón de eliminar
                var subcategoryId = event.target.getAttribute('data-id');
                deleteSubcategory(subcategoryId);
            }
        }
    });

    // Escuchar evento de clic en una fila de la tabla para seleccionarla
    document.getElementById('subcategory-table-body').addEventListener('click', function (event) {
        var target = event.target.closest('tr');
        if (target) {
            // Marcar la fila como seleccionada y desmarcar otras filas
            target.classList.add('selected');
            var siblings = getSiblings(target);
            siblings.forEach(function (sibling) {
                sibling.classList.remove('selected');
            });
        }
    });

    // Función para obtener los elementos hermanos de un elemento dado
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
