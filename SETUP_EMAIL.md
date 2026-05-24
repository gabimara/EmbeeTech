# Configuração de Email - Embee Tech

## Problema Resolvido
O erro "Não foi possível enviar o email. Verifique a configuração do servidor." foi corrigido implementando **PHPMailer** com suporte a SMTP.

## Como Configurar

### 1. Escolher um Provedor de Email

**Opção A: Gmail (Recomendado para testes)**
- Acesse: https://myaccount.google.com/apppasswords
- Crie uma "senha de app" para o Gmail
- Use suas credenciais do Gmail

**Opção B: Mailtrap (Ideal para desenvolvimento)**
- Acesse: https://mailtrap.io
- Crie uma conta gratuita
- Copie as credenciais SMTP fornecidas

**Opção C: Outro servidor SMTP (SendGrid, AWS SES, etc.)**
- Obtenha as credenciais do seu provedor

### 2. Configurar Variáveis de Ambiente

Edite o arquivo `.env` na raiz do projeto:

```env
# Para Gmail
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com

# Para Mailtrap
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario_mailtrap
MAIL_PASSWORD=sua_senha_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@embeetech.com
```

### 3. Testar a Configuração

Acesse a página de login e clique em "Recuperar Senha". Se tudo estiver configurado corretamente, você receberá um email com o link de recuperação.

## Arquivos Criados/Modificados

- ✅ `config/mail.php` - Configuração de email
- ✅ `app/services/MailService.php` - Serviço de envio de emails
- ✅ `.env` - Variáveis de ambiente (configure com suas credenciais)
- ✅ `.env.example` - Exemplo de configuração
- ✅ `app/controllers/AuthController.php` - Atualizado para usar PHPMailer
- ✅ `index.php` - Adicionado carregamento de .env e autoloader do Composer

## Troubleshooting

**Erro: "Composer.lock not found"**
- Execute: `composer install`

**Erro: "Falha ao conectar ao servidor SMTP"**
- Verifique as credenciais em `.env`
- Verifique se a porta SMTP está correta (geralmente 587 para TLS ou 465 para SSL)
- Teste as credenciais em https://www.mail-tester.com

**Email não é recebido**
- Verifique a pasta de spam
- Para Gmail: ative "Acesso de apps menos seguros" ou use senha de app
- Verifique se o endereço de email está correto

## Próximos Passos

Você pode melhorar ainda mais:
- Adicionar templates HTML para emails mais profissionais
- Implementar filas de email para envios assíncronos
- Adicionar logs de envio de email
