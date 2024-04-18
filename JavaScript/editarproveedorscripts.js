document.addEventListener("DOMContentLoaded", function() {
    const editProviderForm = document.getElementById("editProviderForm");

    // Obtener el ID del proveedor de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const providerId = urlParams.get('id');

    // Cargar los datos del proveedor al cargar la página
    loadProviderData(providerId);

    // Función para cargar los datos del proveedor
    function loadProviderData(providerId) {
        // Realizar una solicitud al servidor para obtener los datos del proveedor con el ID proporcionado
        fetch(`../Php/obtenerproveedor.php?id=${providerId}`)
            .then(response => response.json())
            .then(data => {
                // Rellenar el formulario con los datos del proveedor
                document.getElementById("nombre").value = data.Nombre;
                document.getElementById("direccion").value = data.Direccion;
                document.getElementById("correo").value = data.Correo;
                document.getElementById("numeroTelefonico").value = data.numeroTelefonico;
                document.getElementById("tipoIdentificacion").value = data.TipoIdentificacion;
                document.getElementById("numeroIdentificacion").value = data.numeroIdentificacion;
                document.getElementById("estado").value = data.Estado;
            })
            .catch(error => console.error('Error al cargar los datos del proveedor:', error));
    }

    // Event listener para enviar el formulario al servidor al hacer clic en "Guardar Cambios"
    editProviderForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

        // Obtener los datos del formulario
        const formData = new FormData(editProviderForm);

        // Convertir los datos del formulario en un objeto JSON
        const providerData = {};
        formData.forEach((value, key) => {
            providerData[key] = value;
        });

        // Agregar el ID del proveedor al objeto providerData
        providerData["IdProveedor"] = providerId;

        // Realizar una solicitud al servidor para actualizar los datos del proveedor
        fetch('../Php/editarproveedor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(providerData)
        })
        .then(response => {
            // Manejar la respuesta del servidor
            if (response.ok) {
                alert("Los cambios se han guardado correctamente.");
                // Redirigir a la página de administrar proveedores después de guardar los cambios
                window.location.href = "administrarproveedores.html";
            } else {
                throw new Error('Error al guardar los cambios.');
            }
        })
        .catch(error => {
            console.error('Error al guardar los cambios:', error);
            alert("Ha ocurrido un error al guardar los cambios. Por favor, inténtalo de nuevo.");
        });
    });
});