document.addEventListener("DOMContentLoaded", function () {
    // Cargar categorías al cargar la página
    loadCategories();
    var rolUsuario = document.querySelector('.user-info').getAttribute('data-rol-usuario'); // Obtener el rol del usuario

    // Buscar categorías al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchCategories();
    });

    // Buscar categorías al presionar "Enter" en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            searchCategories();
        }
    });

    // Buscar categorías en tiempo real al escribir en el campo de búsqueda
    document.getElementById('search-input').addEventListener('input', function () {
        searchCategories();
    });

    // Evento para agregar una nueva categoría
    document.getElementById('add-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = '../../pagina/producto/agregar_categoria.php';
    });

    // Evento para eliminar una categoría al hacer clic en el botón de eliminar categoría
    document.getElementById('delete-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        var selectedRow = document.querySelector('#category-table-body tr.selected');
        if (selectedRow) {
            var selectedCategoryId = selectedRow.getAttribute('data-id');
            var confirmation = confirm('¿Estás seguro de eliminar esta categoría?');
            if (confirmation) {
                deleteCategory(selectedCategoryId);
            }
        } else {
            alert('Por favor, selecciona una categoría para eliminar.');
        }
    });

    // Evento para eliminar una categoría al hacer clic en el botón de eliminar
    document.getElementById('category-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var confirmation = confirm('¿Estás seguro de eliminar esta categoría?');
            if (confirmation) {
                var categoryId = event.target.closest('tr').getAttribute('data-id');
                deleteCategory(categoryId);
            }
        }
    });

    // Evento para seleccionar una fila de la tabla
    document.getElementById('category-table-body').addEventListener('click', function (event) {
        var target = event.target.closest('tr');
        if (target) {
            target.classList.add('selected');
            var siblings = getSiblings(target);
            siblings.forEach(function (sibling) {
                sibling.classList.remove('selected');
            });
        }
    });

    // Función para cargar categorías y mostrarlas en la tabla
    function loadCategories() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
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

    // Función para buscar categorías
    function searchCategories() {
        var searchTerm = document.getElementById('search-input').value.trim();
        if (searchTerm !== '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        displayCategories(data);
                    } else {
                        console.error('Error al buscar categorías:', xhr.status);
                    }
                }
            };

            xhr.open('GET', `../../php/producto/search_categoria.php?search=${encodeURIComponent(searchTerm)}`, true); // Solicitud GET con el término de búsqueda
            xhr.send();
        } else {
            loadCategories();
        }
    }

    // Función para mostrar categorías en la tabla
    function displayCategories(data) {
        var categoryTableBody = document.getElementById('category-table-body');
        categoryTableBody.innerHTML = '';

        data.forEach(function (category) {
            var estadoButton = category.Estado == 1 ?
                '<button class="active">Activo</button>' :
                '<button class="inactive">Inactivo</button>'; // Determinar estado como Activo o Inactivo
            var row = `
            <tr data-id="${category.IdCategoria}">
                <td>${category.IdCategoria}</td>
                <td>${category.Nombre}</td>
                <td>${estadoButton}</td>
                <td>
                    <button class="delete" data-id="${category.IdCategoria}"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>
                    <button class="edit" data-id="${category.IdCategoria}"><img src="../../img/svg/edit.svg" alt="Editar"></button>
                </td>
            </tr>`;
            categoryTableBody.innerHTML += row;
        });

        // Agregar eventos a los botones de "Editar"
        var editButtons = categoryTableBody.querySelectorAll('.edit');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var categoryId = button.getAttribute('data-id');
                editCategory(categoryId);
            });
        });
    }

    // Función para eliminar una categoría
    function deleteCategory(categoryId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    loadCategories();
                } else {
                    console.error('Error al eliminar categoría:', xhr.status);
                }
            }
        };

        xhr.open('POST', '../../php/producto/delete_categoria.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(`idCategoria=${encodeURIComponent(categoryId)}`);
    }

    // Redirigir para editar categoría
    function editCategory(categoryId) {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = `../../pagina/producto/actualizar_categoria.php?id=${categoryId}`;
    }

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