# Relatório Técnico de Desenvolvimento - BarberVibe

Este documento apresenta o relatário técnico de desenvolvimento da aplicação BarberVibe, um sistema de agendamento e gestão para barbearias, desenvolvido como trabalho académico.

## 1. Introdução

### 1.1 Contexto e Justificação

No mercado de estética e bem-estar masculino, a otimização do tempo e a facilidade de agendamento são fatores essenciais para a fidelização de clientes. O controlo manual de marcações, por meio de agendas de papel ou mensagens, frequentemente resulta em conflitos de horários, perda de histórico e falta de previsibilidade para a gestão.

O BarberVibe centraliza, num único ecossistema seguro, o autocadastro de clientes, a gestão de serviços e profissionais pelos administradores e o controlo diário de horários individuais pelos barbeiros.

### 1.2 Objetivos do Sistema

- Cliente: permitir o registo autônomo, a visualização do catálogo de serviços com valores reais e a marcação rápida e segura.
- Barbeiro: oferecer uma visão exclusiva da sua agenda e permitir a atualização do status do atendimento (Pendente, Confirmado, Concluído, Cancelado).
- Administrador: fornecer controle completo sobre o catálogo de serviços (CRUD), a equipa de barbeiros (CRUD) e acesso irrestrito à agenda global.

## 2. Tecnologias Utilizadas

A aplicação foi construída com tecnologias modernas e estáveis:

- Backend: Laravel 13 (PHP 8.3+), arquitetura MVC.
- Frontend: Blade Templates e Vite.
- Estilização: Tailwind CSS com tema Dark Premium.
- Banco de Dados: MySQL para produção/desenvolvimento local e SQLite em memória para testes.
- Testes: PHPUnit.
- Controle de versão: Git e GitHub.

## 3. Modelação da Base de Dados (Relacionamentos)

A modelagem relacional foi pensada para garantir integridade e evitar redundância, reunindo clientes, barbeiros e administradores na mesma tabela de utilizadores.

### 3.1 Tabela `users`

- `id` (PK)
- `name` (String)
- `email` (String, unique)
- `password` (String)
- `role` (String) - `client`, `barber` ou `admin`
- `created_at`, `updated_at`

### 3.2 Tabela `services`

- `id` (PK)
- `name` (String)
- `price` (Decimal 8,2)
- `duration_minutes` (Integer)
- `created_at`, `updated_at`

### 3.3 Tabela `appointments`

- `id` (PK)
- `client_id` (FK para `users.id`)
- `barber_id` (FK para `users.id`)
- `service_id` (FK para `services.id`)
- `date_time` (DateTime)
- `status` (String) - `pending`, `confirmed`, `completed` ou `canceled`
- `created_at`, `updated_at`

## 4. Funcionalidades e Regras de Negócio Implementadas

### 4.1 Autenticação e Autorização

- Proteção de rotas com middleware `auth`.
- Controle de acesso por função (RBAC) para impedir que clientes acessem áreas administrativas.
- Barbeiros só podem gerenciar agendamentos associados à sua própria agenda.

### 4.2 Regras de Agendamento

- Bloqueio de agendamentos no passado.
- Restrição de horário de funcionamento entre 09:00 e 19:00.
- Prevenção de conflitos de horário para o mesmo barbeiro.

### 4.3 Integridade Referencial

- Não é possível excluir serviços com agendamentos associados.
- A remoção de barbeiros também é bloqueada quando existem agendamentos pendentes ou confirmados.

## 5. Testes Automatizados

A suíte de testes valida os principais fluxos de autenticação e agendamento.

### 5.1 Testes de Autenticação

- Redirecionamento de clientes para `appointments.index` após login.
- Redirecionamento de administradores e barbeiros para `management` após login.

### 5.2 Testes de Agendamento

- Bloqueio de acesso de visitantes não autenticados.
- Criação válida de agendamento para cliente autenticado.
- Rejeição de agendamento no passado.
- Rejeição de agendamento fora do horário comercial.
- Bloqueio de conflito de horário para o mesmo barbeiro.

### 5.3 Resultado Atual

O projeto possui **10 testes** registrados na suíte automatizada.

## 6. Configuração de Contas de Teste

O `DatabaseSeeder` cria as seguintes contas:

- Administrador: `admin@barbervibe.com.br` / `senha123`
- Barbeiro: `barbeiro@barbervibe.com.br` / `senha123`
- Cliente: `cliente@barbervibe.com.br` / `senha123`

E também cria serviços iniciais no seed.

## 7. Manual de Instalação e Execução

### 7.1 Requisitos

- PHP 8.3 ou superior
- Composer
- Node.js e npm

### 7.2 Passos

1. Instalar dependências:

```
composer install
npm install
```

2. Configurar o `.env`:

```
copy .env.example .env
php artisan key:generate
```

3. Executar migrações e seed:

```
php artisan migrate --seed
```

4. Compilar assets e iniciar o desenvolvimento:

```
npm run dev
```

5. Em outro terminal, iniciar o servidor:

```
php artisan serve
```

6. Executar a suíte de testes:

```
php artisan test
```
