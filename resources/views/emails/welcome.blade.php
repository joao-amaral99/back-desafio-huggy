<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bem-vindo!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #321bde;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Bem-vindo à nossa plataforma!</h1>
    </div>
    
    <div class="content">
        <h2>Olá, {{ $contact->name ?? 'Cliente' }}!</h2>
        
        <p>É com grande prazer que damos as boas-vindas à nossa plataforma!</p>
        
        <p>Seus dados foram registrados com sucesso:</p>
        <ul>
            <li><strong>Nome:</strong> {{ $contact->name }}</li>
            <li><strong>Email:</strong> {{ $contact->email }}</li>
            @if($contact->phone)
            <li><strong>Telefone:</strong> {{ $contact->phone }}</li>
            @endif
            @if($contact->mobile)
            <li><strong>Celular:</strong> {{ $contact->mobile }}</li>
            @endif
        </ul>
        
        <p>Estamos aqui para oferecer o melhor atendimento e suporte. Nossa equipe está sempre pronta para ajudá-lo!</p>
        
        <p>Se você tiver alguma dúvida ou precisar de assistência, não hesite em entrar em contato conosco.</p>
        
        <p>Mais uma vez, seja bem-vindo!</p>
        
        <p>Atenciosamente,<br>
        <strong>Equipe de Atendimento</strong></p>
    </div>
    
    <div class="footer">
        <p>Este email foi enviado automaticamente. Não é necessário respondê-lo.</p>
        <p>© {{ date('Y') }} - Todos os direitos reservados</p>
    </div>
</body>
</html>
