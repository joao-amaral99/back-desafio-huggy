# Backend Desafio Huggy

API REST desenvolvida em Laravel 12 para gerenciamento de contatos com integração com a plataforma Huggy e funcionalidades de VOIP via Twilio.

## 🚀 Funcionalidades

- **CRUD de Contatos**: Gerenciamento completo de contatos com validação
- **Integração Huggy**: Webhook para sincronização de contatos e autenticação OAuth
- **VOIP com Twilio**: Sistema de chamadas telefônicas
- **Relatórios**: Geração de relatórios por cidade e estado
- **Testes**: Cobertura completa com testes unitários e de integração
- **Arquitetura SOLID**: Implementação seguindo princípios SOLID

## 🛠️ Tecnologias

- **Laravel 12** - Framework PHP
- **PostgreSQL** - Banco de dados
- **Docker** - Containerização
- **Twilio SDK** - Integração VOIP
- **Huggy** - Autenticação OAuth e WebHook para receber dados
- **PHPUnit** - Testes automatizados

## 📋 Pré-requisitos

- PHP 8.2+
- Composer
- Docker e Docker Compose

## 🔧 Instalação

### 1. Clone o repositório
```bash
git clone <repository-url>
cd back-desafio-huggy
```

### 2. Configure o ambiente
```bash
cp .env.example .env
```

### 3. Configure as variáveis de ambiente
```env
# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=projeto-huggy
DB_USERNAME=userhuggy
DB_PASSWORD=userhuggy

# Huggy Integration
HUGGY_CLIENT_ID=your_huggy_client_id
HUGGY_CLIENT_SECRET=your_huggy_client_secret
HUGGY_REDIRECT_URI=http://localhost:8000/api/oauth/huggy/callback
HUGGY_WEBHOOK_TOKEN=your_webhook_token

# Twilio VOIP
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=

# External Webhook
EXTERNAL_WEBHOOK_URL=your_external_webhook_url
```

### 4. Inicie o banco de dados
```bash
docker-compose up -d
```

### 5. Instale as dependências
```bash
composer install
```

### 6. Execute as migrations
```bash
php artisan migrate
```

### 7. Gere a chave da aplicação
```bash
php artisan key:generate
```

### 8. Inicie o servidor
```bash
php artisan serve
```

## 📚 Estrutura da API

### Autenticação e OAuth
- `GET /api/oauth/huggy/redirect` - Redireciona para autenticação Huggy

### Contatos
- `GET /api/contacts` - Lista todos os contatos (com filtros e ordenação)
- `POST /api/contacts` - Cria um novo contato
- `GET /api/contacts/{id}` - Busca um contato específico
- `PUT /api/contacts/{id}` - Atualiza um contato
- `DELETE /api/contacts/{id}` - Remove um contato
- `POST /api/contacts/{id}/call` - Realiza chamada para o contato

### Webhook Huggy
- `POST /api/huggy/webhook` - Recebe eventos do Huggy (criação/atualização de contatos)

### Relatórios
- `GET /api/reports/contacts-by-city` - Relatório de contatos por cidade
- `GET /api/reports/contacts-by-state` - Relatório de contatos por estado

## 🗄️ Modelo de Dados

### Contact
```php
{
    "id": 1,
    "name": "João Silva",
    "email": "joao@email.com",
    "phone": "11987654321",
    "mobile": "11987654321",
    "address": "Rua das Flores, 123",
    "district": "Centro",
    "state": "SP",
    "city": "São Paulo",
    "photo": "https://example.com/photo.jpg",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

## 🔄 Integração Huggy

### Configuração do Ambiente

Para testar a integração com Huggy, é necessário configurar o ngrok para expor o servidor local:

#### 1. Instalação e Configuração do ngrok
```bash
# Instale o ngrok (se ainda não tiver)
# https://ngrok.com/download

# Com o servidor rodando na porta 8000, execute:
ngrok http 8000
```

#### 2. Configuração OAuth
Após executar o ngrok, você receberá uma URL HTTPS (ex: `https://b20f017eb4e5.ngrok-free.app`). Configure:

**No arquivo .env:**
```env
HUGGY_REDIRECT_URI=https://b20f017eb4e5.ngrok-free.app/api/oauth/huggy/callback
```

**No painel da Huggy:**
- Acesse as configurações OAuth
- Configure a URL de redirecionamento com a URL gerada pelo ngrok
- Mantenha a estrutura: `https://[ngrok-url]/api/oauth/huggy/callback`

#### 3. Configuração Webhook
**No arquivo .env (ANTES de configurar no painel):**
```env
HUGGY_WEBHOOK_TOKEN=token_gerado_pela_huggy
```

**No painel da Huggy:**
1. Acesse **Configurações > Webhook**
2. Configure a URL: `https://[ngrok-url]/api/huggy/webhook`
3. Configure os eventos:
   - ✅ **Contato criado**
   - ✅ **Atualização de contato**

### Webhook Recebido
A aplicação recebe webhooks do Huggy para sincronização automática de contatos:
- Criação de contatos (`createdCustomer`)
- Atualização de contatos (`updatedCustomer`)

### Webhook Disparado
A aplicação dispara webhooks para sistemas externos quando:
- Um contato é criado (`contact.created`)
- Um contato é atualizado (`contact.updated`)

#### Configuração para Teste
Para testar os webhooks disparados, você pode usar o [Webhook.site](https://webhook.site):

1. Acesse [webhook.site](https://webhook.site)
2. O site irá gerar uma URL única para receber webhooks (ex: `https://webhook.site/a3ec697c-0ff0-4d22-8efc-4d1a8001b572`)

3. Configure essa URL no arquivo `.env`:
```env
EXTERNAL_WEBHOOK_URL=https://webhook.site/a3ec697c-0ff0-4d22-8efc-4d1a8001b572
```

#### Payload Enviado
O payload enviado inclui:
```json
{
    "event": "contact.created|contact.updated",
    "contact": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@email.com",
        // ... outros campos do contato
    },
    "timestamp": "2024-01-01T00:00:00.000000Z"
}
```

### OAuth
Sistema de autenticação OAuth2 com Huggy.

## 📞 Funcionalidade VOIP

Integração com Twilio para realização de chamadas telefônicas:
- Chamadas automáticas para contatos
- Validação de números de telefone
- Tratamento de erros de chamada

### Configuração Twilio

#### 1. Criar Conta Trial
1. Acesse [twilio.com](https://www.twilio.com) e crie uma conta trial
2. Após a criação, você receberá:
   - **Account SID**
   - **Auth Token**

#### 2. Comprar Número de Telefone
1. No painel da Twilio, vá para **Phone Numbers > Manage > Buy a number**
2. Compre um número de telefone para realizar as chamadas
3. Anote o número comprado

#### 3. Configurar Variáveis de Ambiente
Configure as seguintes variáveis no arquivo `.env`:
```env
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=
```

## 📧 Sistema de Emails

### Jobs em Background
- **SendWelcomeEmail**: Envia email de boas-vindas 30 minutos após a criação do contato
- Processamento assíncrono via Laravel Queue
- Logs detalhados de sucesso e erro

### Configuração Gmail

#### 1. Gerar Senha de App
1. Acesse sua conta Google
2. Vá em **Segurança > Verificação em duas etapas** (ative se não estiver)
3. Vá em **Senhas de app**
4. Gere uma nova senha para "Email"
5. Copie a senha gerada (16 caracteres)

#### 2. Configurar Variáveis de Ambiente
Configure as seguintes variáveis no arquivo `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_de_app_gerada
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com
MAIL_FROM_NAME="Huggy Desafio"
```

### Processamento de Filas
Para processar as filas de email:
```bash
php artisan queue:work
```

## 🧪 Testes

### Executar todos os testes
```bash
php artisan test
```

### Testes unitários
```bash
php artisan test --testsuite=Unit
```

### Testes de integração
```bash
php artisan test --testsuite=Feature
```

