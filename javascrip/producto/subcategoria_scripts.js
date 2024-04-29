document.addEventListener("DOMContentLoaded", function () {
    // Cargar subcategorías al cargar la página
    loadSubcategories();
    var rolUsuario = document.querySelector('.user-info').getAttribute('data-rol-usuario'); // Obtener el rol del usuario


    // Buscar subcategorías al hacer clic en el botón de búsqueda
    document.getElementById('search-button').addEventListener('click', function () {
        searchSubcategories();
    });

    // Buscar subcategorías al presionar "Enter" en el campo de búsqueda
    document.getElementById('search-input').addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            searchSubcategories();
        }
    });

    // Buscar subcategorías en tiempo real al escribir en el campo de búsqueda
    document.getElementById('search-input').addEventListener('input', function () {
        searchSubcategories();
    });

    // Evento para eliminar una subcategoría al hacer clic en el botón de eliminar subcategoría
    document.getElementById('delete-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        var selectedRow = document.querySelector('#subcategory-table-body tr.selected');
        if (selectedRow) {
            var selectedSubcategoryId = selectedRow.getAttribute('data-id');
            var confirmation = confirm('¿Estás seguro de eliminar esta subcategoría?');
            if (confirmation) {
                deleteSubcategory(selectedSubcategoryId);
            }
        } else {
            alert('Por favor, selecciona una subcategoría para eliminar.');
        }
    });

    // Evento para eliminar una subcategoría al hacer clic en el botón de eliminar
    document.getElementById('subcategory-table-body').addEventListener('click', function (event) {
        if (event.target && event.target.matches('.delete')) {
            if (rolUsuario !== "Escritura") {
                alert("No tienes permiso para eliminar usuarios.");
                return;
            }
            var confirmation = confirm('¿Estás seguro de eliminar esta subcategoría?');
            if (confirmation) {
                var subcategoryId = event.target.closest('tr').getAttribute('data-id');
                deleteSubcategory(subcategoryId);
            }
        }
    });

    // Evento para agregar una nueva subcategoría
    document.getElementById('add-button').addEventListener('click', function () {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = '../../pagina/producto/agregar_subcategoria.php';
    });

    // Evento para seleccionar una fila de la tabla
    document.getElementById('subcategory-table-body').addEventListener('click', function (event) {
        var target = event.target.closest('tr');
        if (target) {
            target.classList.add('selected');
            var siblings = getSiblings(target);
            siblings.forEach(function (sibling) {
                sibling.classList.remove('selected');
            });
        }
    });

    // Función para cargar subcategorías desde el servidor y mostrarlas en la tabla
    function loadSubcategories() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
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
                        var data = JSON.parse(xhr.responseText);
                        displaySubcategories(data);
                    } else {
                        console.error('Error al buscar subcategorías:', xhr.status);
                    }
                }
            };

            xhr.open('GET', `../../php/producto/search_subcategoria.php?search=${encodeURIComponent(searchTerm)}`, true);
            xhr.send();
        } else {
            loadSubcategories();
        }
    }

    // Función para mostrar subcategorías en la tabla
    function displaySubcategories(data) {
        var subcategoryTableBody = document.getElementById('subcategory-table-body');
        subcategoryTableBody.innerHTML = '';

        data.forEach(function (subcategory) {
            var estadoButton = subcategory.Estado == 1 ?
                '<button class="active">Activo</button>' :
                '<button class="inactive">Inactivo</button>';
            var row = `
            <tr data-id="${subcategory.IdSubcategoria}">
                <td>${subcategory.IdSubcategoria}</td>
                <td>${subcategory.SubcategoriaNombre}</td>
                <td>${estadoButton}</td>
                <td>${subcategory.CategoriaNombre}</td>
                <td>
                    <button class="delete" data-id="${subcategory.IdSubcategoria}"><img src="../../img/svg/delete.svg" alt="Eliminar"></button>
                    <button class="edit" data-id="${subcategory.IdSubcategoria}"><img src="../../img/svg/edit.svg" alt="Editar"></button>
                </td>
            </tr>`;
            subcategoryTableBody.innerHTML += row;
        });

        var editButtons = subcategoryTableBody.querySelectorAll('.edit');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var subcategoryId = button.getAttribute('data-id');
                editSubcategory(subcategoryId);
            });
        });
    }

    // Función para eliminar una subcategoría
    function deleteSubcategory(subcategoryId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    loadSubcategories(); 
                } else {
                    console.error('Error al eliminar subcategoría:', xhr.status);
                }
            }
        };

        xhr.open('POST', '../../php/producto/delete_subcategoria.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(`idSubcategoria=${encodeURIComponent(subcategoryId)}`);
    }

    // Función para redirigir para editar subcategoría
    function editSubcategory(subcategoryId) {
        if (rolUsuario !== "Escritura") {
            alert("No tienes permiso para eliminar usuarios.");
            return;
        }
        window.location.href = `../../pagina/producto/actualizar_subcategoria.php?id=${subcategoryId}`;
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

