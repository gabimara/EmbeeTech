# Embee Tech

Site em PHP com estrutura MVC para gerenciamento de chamados e painel administrativo.

## Estrutura
- `index.php` — front controller
- `config/database.php` — configuração de conexão MySQL
- `app/core/` — núcleo MVC
- `app/controllers/` — controladores de rota
- `app/models/` — modelos de acesso a dados
- `app/views/` — páginas e layout
- `database.sql` — script de criação do banco

## Requisitos
- PHP 8+ com PDO MySQL
- MySQL / MariaDB
- phpMyAdmin opcional para importar o banco

## Configuração
1. Importe `database.sql` no phpMyAdmin ou via terminal MySQL.
2. Se necessário, ajuste `config/database.php` com o usuário e senha do MySQL.
3. Execute o projeto em um servidor PHP:
   - `php -S localhost:8000` no diretório do projeto
4. Acesse `http://localhost:8000`.

## Contas de acesso
- Admin: `admin@embee.com` / `admin123`
- Usuário: `user@embee.com` / `user123`

## Observações
- O projeto gera os usuários padrão automaticamente quando a tabela estiver vazia.
- Categorias e tipos de serviço também são criados automaticamente.
