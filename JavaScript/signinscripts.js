document.getElementById("registroForm").addEventListener("submit", function(event) {
    // Validar aquí si todos los campos del formulario están llenos antes de enviar el formulario
    // Si no pasan la validación, puedes mostrar un mensaje de error o prevenir el envío del formulario
    // Aquí un ejemplo básico de validación que verifica si todos los campos están llenos:
    var campos = document.querySelectorAll("input[type='text'], input[type='email'], input[type='password']");
    for (var i = 0; i < campos.length; i++) {
        if (!campos[i].value) {
            alert("Por favor, complete todos los campos.");
            event.preventDefault(); // Prevenir el envío del formulario
            return;
        }
    }
});