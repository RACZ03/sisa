<!DOCTYPE html>
<html>
<head>
    <title>Actualización de Contraseña</title>
</head>
<body>
    <p>Estimado(a) {{ $username }},</p>
    <p>Esperamos que te encuentres bien. Te informamos que tu contraseña ha sido actualizada satisfactoriamente en SISA.</p>
    <p>A continuación, te proporcionamos tus nuevos datos de acceso:</p>
    <p><strong>Nombre de usuario:</strong> {{ $username }}</p>
    <p><strong>Nueva Contraseña:</strong> {{ $newPassword }}</p>
    <!-- add link url config in .env -->
    <p>Para ingresar a SISA, haz clic en el siguiente enlace:</p>
    <p><a href="{{ env('APP_URL') }}">SISA</a></p>
    <p>Si no has realizado esta actualización o tienes alguna pregunta, por favor contáctanos de inmediato a través de nuestro equipo de soporte al cliente.</p>
    <p>Agradecemos tu confianza y continuamos trabajando para brindarte el mejor servicio posible.</p>
    <p>Atentamente,</p>
    <p>El Equipo de SISA</p>
</body>
</html>
