<!DOCTYPE html>
<html>
  <body>
    <script>
      function enviarToken() {
        window.opener && window.opener.postMessage(
          { token: "{{ $token ?? '' }}" },
          "*"
        );
        setTimeout(() => window.close(), 300);
      }

      @if(isset($token) && $token)
        enviarToken();
      @endif
    </script>
    <p>Login realizado! Você pode fechar esta janela.</p>
    <button onclick="enviarToken()">Clique aqui se não for redirecionado automaticamente</button>
  </body>
</html>