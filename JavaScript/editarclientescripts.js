document.addEventListener("DOMContentLoaded", function() {
    const editClientForm = document.getElementById("editClientForm");

    // Obtener el ID del cliente de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const clientId = urlParams.get('id');

    // Cargar los datos del cliente al cargar la página
    loadClientData(clientId);

    // Función para cargar los datos del cliente
    function loadClientData(clientId) {
        // Realizar una solicitud al servidor para obtener los datos del cliente con el ID proporcionado
        fetch(`../Php/obtenercliente.php?id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                // Rellenar el formulario con los datos del cliente
                document.getElementById("nombre").value = data.Nombre;
                document.getElementById("direccion").value = data.Direccion;
                document.getElementById("correo").value = data.Correo;
                document.getElementById("numeroTelefonico").value = data.numeroTelefonico;
                document.getElementById("tipoIdentificacion").value = data.TipoIdentificacion;
                document.getElementById("numeroIdentificacion").value = data.numeroIdentificacion;
                document.getElementById("estado").value = data.Estado;
            })
            .catch(error => console.error('Error al cargar los datos del cliente:', error));
    }

    // Event listener para enviar el formulario al servidor al hacer clic en "Guardar Cambios"
    editClientForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

        // Obtener los datos del formulario
        const formData = new FormData(editClientForm);

        // Convertir los datos del formulario en un objeto JSON
        const clientData = {};
        formData.forEach((value, key) => {
            clientData[key] = value;
        });

        // Agregar el ID del cliente al objeto clientData
        clientData["IdCliente"] = clientId;

        // Realizar una solicitud al servidor para actualizar los datos del cliente
        fetch('../Php/editarcliente.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(clientData)
        })
        .then(response => {
            // Manejar la respuesta del servidor
            if (response.ok) {
                alert("Los cambios se han guardado correctamente.");
                // Redirigir a la página de administrar clientes después de guardar los cambios
                window.location.href = "administrarclientes.html";
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
