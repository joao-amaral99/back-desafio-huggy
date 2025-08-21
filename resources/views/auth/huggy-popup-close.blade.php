<!DOCTYPE html>
<html>
  <body>
    <script>
      function enviarToken() {
        if (window.opener) {

          window.opener.postMessage(
            { token: "{{ $token ?? '' }}" },
            "*"
          );
        } else {
          console.error('O objeto window.opener não está disponível. O postMessage não pode ser enviado.');
        }

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