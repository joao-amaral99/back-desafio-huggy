<!DOCTYPE html>
<html>
  <body>
    <script>
      window.opener.postMessage(
        { token: "{{ $token }}" },
        "*"
      );
      window.close();
    </script>
    <p>Login realizado! Agoravocê pode fechar esta janela.</p>
  </body>
</html>
