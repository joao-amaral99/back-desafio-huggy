<!DOCTYPE html>
<html>
<head>
    <title>Google Login</title>
</head>
<body>
<script>
    window.opener.postMessage(@json($data), "*");
    window.close();
</script>
<p>Login realizado, agora você pode fechar esta janela.</p>
</body>
</html>