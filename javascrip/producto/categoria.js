$(document).ready(function() {
    // Función para cargar las categorías en el select
    function cargarCategorias() {
        $.ajax({
            type: 'GET',
            url: '../../php/producto/categorias.php',
            success: function(response) {
                var categorias = JSON.parse(response);
                var options = '';
                for (var i = 0; i < categorias.length; i++) {
                    options += '<option value="' + categorias[i].IdCategoria + '">' + categorias[i].Nombre + '</option>';
                }
                $('#categoriaPadre').html(options);
            },
            error: function(xhr, status, error) {
                alert('Error al obtener las categorías: ' + error);
            }
        });
    }

    // Cargar las categorías al cargar la página
    cargarCategorias();

    $('#formulario_categoria').submit(function(event) {
        event.preventDefault();
        
        var nombreCategoria = $('#nombreCategoria').val();
        
        // Enviar los datos al servidor mediante AJAX para crear una nueva categoría
        $.ajax({
            type: 'POST',
            url: '../../php/producto/categorias.php',
            data: {
                nombreCategoria: nombreCategoria
            },
            success: function(response) {
                alert('Categoría creada exitosamente.');
                // Limpiar el formulario después de enviar los datos
                $('#nombreCategoria').val('');
                // Recargar las categorías después de crear una nueva categoría
                cargarCategorias();
            },
            error: function(xhr, status, error) {
                alert('Error al crear la categoría: ' + error);
            }
        });
    });

    $('#formulario_subcategoria').submit(function(event) {
        event.preventDefault();
        
        var nombreSubcategoria = $('#nombreSubcategoria').val();
        var categoriaPadre = $('#categoriaPadre').val();
        
        // Enviar los datos al servidor mediante AJAX para crear una nueva subcategoría
        $.ajax({
            type: 'POST',
            url: '../../php/producto/categorias.php',
            data: {
                nombreSubcategoria: nombreSubcategoria,
                categoriaPadre: categoriaPadre
            },
            success: function(response) {
                alert('Subcategoría creada exitosamente.');
                // Limpiar el formulario después de enviar los datos
                $('#nombreSubcategoria').val('');
            },
            error: function(xhr, status, error) {
                alert('Error al crear la subcategoría: ' + error);
            }
        });
    });

    // Evento click para redirigir a la página de categorías
    $('#verCategorias').click(function() {
        window.location.href = '../../pagina/producto/producto.php'; // Reemplaza 'categorias.php' con la ruta correcta si es necesario
    });
});
