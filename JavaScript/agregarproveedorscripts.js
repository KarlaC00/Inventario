document.getElementById("agregarProveedorForm").addEventListener("submit", function(event) {
    // Validar aquí si todos los campos del formulario están llenos antes de enviar el formulario
    var campos = document.querySelectorAll("input[type='text'], input[type='email']");
    for (var i = 0; i < campos.length; i++) {
        if (!campos[i].value) {
            alert("Por favor, complete todos los campos.");
            event.preventDefault(); // Prevenir el envío del formulario
            return;
        }
    }
    // Mostrar una alerta cuando se envíe el formulario con éxito
    alert("El formulario se ha enviado correctamente.");
});