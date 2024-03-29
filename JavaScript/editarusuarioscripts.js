// editarusuarioscripts.js
document.addEventListener("DOMContentLoaded", function() {
    const editUserForm = document.getElementById("editUserForm");

    // Obtener el ID del usuario de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');

    // Cargar los datos del usuario al cargar la página
    loadUserData(userId);

    // Función para cargar los datos del usuario
    function loadUserData(userId) {
        // Realizar una solicitud al servidor para obtener los datos del usuario con el ID proporcionado
        fetch(`../Php/obtenerusuario.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                // Rellenar el formulario con los datos del usuario
                document.getElementById("nombre").value = data.Nombre;
                document.getElementById("direccion").value = data.Direccion;
                document.getElementById("correo").value = data.Correo;
                document.getElementById("numeroTelefonico").value = data.numeroTelefonico;
                document.getElementById("tipoIdentificacion").value = data.TipoIdentificacion;
                document.getElementById("numeroIdentificacion").value = data.numeroIdentificacion;
                document.getElementById("usuario").value = data.Usuario;
                document.getElementById("contrasena").value = data.Contrasena;
                document.getElementById("estado").value = data.Estado;
                document.getElementById("nivelAcceso").value = data.nivelAcceso_IdnivelAcceso;
            })
            .catch(error => console.error('Error al cargar los datos del usuario:', error));
    }

    // Event listener para enviar el formulario al servidor al hacer clic en "Guardar Cambios"
    editUserForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

        // Obtener los datos del formulario
        const formData = new FormData(editUserForm);

        // Convertir los datos del formulario en un objeto JSON
        const userData = {};
        formData.forEach((value, key) => {
            userData[key] = value;
        });

        // Agregar el ID del usuario al objeto userData
        userData["IdUsuario"] = userId;

        // Realizar una solicitud al servidor para actualizar los datos del usuario
        fetch('../Php/editarusuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        })
        .then(response => {
            // Manejar la respuesta del servidor
            if (response.ok) {
                alert("Los cambios se han guardado correctamente.");
                // Redirigir a la página de administrar usuarios después de guardar los cambios
                window.location.href = "administraraccesos.html";
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