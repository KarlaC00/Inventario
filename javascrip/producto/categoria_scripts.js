document.addEventListener("DOMContentLoaded", function () {
    // Esta función se ejecuta cuando el documento HTML ha sido completamente cargado

    // Cargar categorías al cargar la página inicialmente
    loadCategories();

    // Escuchar evento de presionar Enter en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode == 13) {
            // Si la tecla presionada es Enter, buscar categorías
            searchCategories();
        }
    });

    // Escuchar evento de clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        // Al hacer clic en el botón de búsqueda, buscar categorías
        searchCategories();
    });

    // Escuchar evento de clic en el botón de eliminar categoría
    document.getElementById('delete-button').addEventListener('click', function () {
        // Obtener la fila seleccionada
        var selectedRow = document.querySelector('#category-table-body tr.selected');
        if (selectedRow) {
            // Si hay una fila seleccionada, obtener el ID de la categoría
            var selectedCategoryId = selectedRow.getAttribute('data-id');
            var confirmation = confirm('¿Estás seguro de eliminar esta categoría?');
            if (confirmation) {
                // Si el usuario confirma la eliminación, proceder a eliminar la categoría
                deleteCategory(selectedCategoryId);
            }
        } else {
            // Si no hay una fila seleccionada, mostrar una alerta
            alert('Por favor, selecciona una categoría para eliminar.');
        }
    });

    // Escuchar evento de clic en el botón de agregar categoría
    document.getElementById('add-button').addEventListener('click', function () {
        // Redireccionar a la página para agregar una nueva categoría
        window.location.href = '../../pagina/producto/agregar_categoria.php';
    });

    // Función para manejar la acción de editar la categoría
    function editCategory(categoryId) {
        // Redirigir a la página de edición de categoría con el ID proporcionado
        window.location.href = '../../pagina/producto/actualizar_categoria.php?id=' + categoryId;
    }

    // Función para cargar categorías desde el servidor y mostrarlas en la tabla
    function loadCategories() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Si la solicitud fue exitosa, mostrar las categorías
                    var data = JSON.parse(xhr.responseText);
                    displayCategories(data);
                } else {
                    console.error('Error al cargar categorías:', xhr.status);
                }
            }
        };
        xhr.open('GET', '../../php/producto/read_categoria.php', true);
        xhr.send();
    }

    // Función para buscar categorías por nombre
    function searchCategories() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm != '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Si la búsqueda fue exitosa, mostrar los resultados
                        var data = JSON.parse(xhr.responseText);
                        displayCategories(data);
                    } else {
                        console.error('Error al buscar categorías:', xhr.status);
                    }
                }
            };
            xhr.open('GET', '../../php/producto/search_categoria.php?search=' + encodeURIComponent(searchTerm), true);
            xhr.send();
        } else {
            loadCategories();
        }
    }
    

    // Función para eliminar una categoría
    function deleteCategory(categoryId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Si la eliminación fue exitosa, mostrar un mensaje y recargar las categorías
                    alert(xhr.responseText);
                    loadCategories();
                } else {
                    console.error('Error al eliminar categoría:', xhr.status);
                }
            }
        };
        xhr.open('POST', '../../php/producto/delete_categoria.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('idCategoria=' + categoryId);
    }

    // Función para mostrar las categorías en la tabla
    function displayCategories(data) {
        var categoryTableBody = document.getElementById('category-table-body');
        categoryTableBody.innerHTML = '';

        // Recorrer los datos de las categorías y agregar filas a la tabla
        data.forEach(function (category) {
            var estadoLabel = category.Estado == 1 ? '<button class="active">Activo</button>' : '<button class="inactive">Inactivo</button>'; // Determinar el estado como Activo o Inactivo
            var row = '<tr data-id="' + category.IdCategoria + '">' +
                '<td>' + category.IdCategoria + '</td>' +
                '<td>' + category.Nombre + '</td>' +
                '<td>' + estadoLabel + '</td>' +
                '<td>' +
                    '<button class="delete" data-id="' + category.IdCategoria + '"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>' +
                    '<button class="edit" data-id="' + category.IdCategoria + '"><img src="../../img/svg/edit.svg" alt="Editar"></button>' +
                '</td>' +
                '</tr>';
            categoryTableBody.innerHTML += row;
        });

        // Agregar eventos a los botones de "Editar"
        var editButtons = categoryTableBody.querySelectorAll('.edit');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var categoryId = button.getAttribute('data-id');
                // Lógica para manejar la acción de editar la categoría (redireccionar a la página de edición, etc.)
                editCategory(categoryId);
            });
        });
    }

    // Escuchar evento de clic en el botón de eliminar dentro de la tabla
    document.getElementById('category-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            var confirmation = confirm('¿Estás seguro de eliminar esta categoría?');
            if (confirmation) {
                // Obtener el ID de la categoría al hacer clic en el botón de eliminar
                var categoryId = event.target.getAttribute('data-id');
                deleteCategory(categoryId);
            }
        }
    });

    // Escuchar evento de clic en una fila de la tabla para seleccionarla
    document.getElementById('category-table-body').addEventListener('click', function (event) {
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
