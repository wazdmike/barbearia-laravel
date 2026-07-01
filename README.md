# BarberVibe é Premium Barber Shop Booking & Management

## Visão Geral
BarberVibe é um sistema de agendamento e gestão para barbearias, criado como projeto acadêmico.

- **Cliente**: realiza autocadastro, agenda seus serviços e pode cancelar suas próprias reservas.
- **Barbeiro**: visualiza apenas sua agenda pessoal e atualiza o status dos atendimentos para **Confirmar**, **Recusar** ou **Concluir**.
- **Administrador**: gerencia a equipe de barbeiros (CRUD), administra o catálogo de serviços (CRUD) e acompanha a agenda global sem restrições.

## Tecnologias
- Laravel 12 (PHP 8.2+)
- Blade Templates
- Vite
- Tailwind CSS (Tema Dark Premium)
- MySQL (produção)
- SQLite (memória para testes)
- PHPUnit
- Git / GitHub

## Modelação da Base de Dados
A seguir, um diagrama textual simples das tabelas principais:

```
users
  - id (PK)
  - name
  - email
  - password
  - role

services
  - id (PK)
  - name
  - duration
  - price
  - description

appointments
  - id (PK)
  - user_id (FK -> users.id)
  - service_id (FK -> services.id)
  - barber_id (FK -> users.id)
  - scheduled_at
  - status
  - created_at
  - updated_at
```

## Passo a Passo de Instalação
1. Instale as dependências do Laravel e do frontend:
   - `composer install`
   - `npm install`
2. Configure o arquivo `.env` com o banco de dados e outras credenciais.
3. Gere a chave da aplicação:
   - `php artisan key:generate`
4. Execute as migrações e os seeders:
   - `php artisan migrate --seed`
5. Inicie o Vite em modo de desenvolvimento:
   - `npm run dev`
6. Execute o servidor local:
   - `php artisan serve`

## Contas de Teste
| Perfil | E-mail | Senha |
|---|---|---|
| Administrador | admin@barbervibe.com.br | senha123 |
| Barbeiro | barbeiro@barbervibe.com.br | senha123 |
| Cliente | cliente@barbervibe.com.br | senha123 |

## Testes
O sistema inclui testes automatizados com **10 testes funcionais aprovados** e **31 asserções**, cobrindo:
- proteção de rotas
- redirecionamento dinâmico
- validações de conflito de horário
- validações de horário comercial
