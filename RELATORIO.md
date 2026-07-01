# Relatório Técnico de Desenvolvimento - BarberVibe

Este documento apresenta o relatório técnico de desenvolvimento da aplicação BarberVibe, um sistema moderno de agendamento e gestão para barbearias, concebido como projeto académico para a disciplina de desenvolvimento de sistemas do IFSP.

## 1. Introdução

### 1.1 Contexto e Justificação

No mercado de estética e bem-estar masculino, a otimização do tempo e a facilidade de agendamento são fatores cruciais para a fidelização de clientes. O controlo manual de marcações, por via de agendas de papel ou aplicações de mensagens descentralizadas, frequentemente resulta em conflitos de horários, perda de histórico e falta de previsibilidade de faturação para os gestores.

O BarberVibe resolve estes problemas ao centralizar num único ecossistema seguro o autocadastro de clientes, a gestão de serviços e profissionais por parte dos administradores, e o controlo diário de horários individuais para os barbeiros.

### 1.2 Objetivos do Sistema

Para o Cliente: Permitir o registo autónomo, visualização do catálogo de serviços com preços reais e marcação rápida em tempo real.

Para o Barbeiro: Disponibilizar uma visão clara e exclusiva da sua escala de atendimento do dia, permitindo a atualização de status do serviço (Confirmado, Concluído, Cancelado).

Para o Administrador: Fornecer controlo total sobre o catálogo de serviços (CRUD), gestão ativa da equipa de barbeiros (CRUD) e controlo irrestrito sobre todos os horários e registos.

## 2. Tecnologias Utilizadas

A aplicação foi estruturada sobre pilhas de tecnologias modernas, estáveis e amplamente adotadas no mercado de trabalho:

Framework Back-end: Laravel 12 (PHP 8.2+) utilizando o padrão arquitetural MVC (Model-View-Controller).

Base de Dados: MySQL (ambiente de produção/desenvolvimento local) e SQLite em memória (para a execução ágil de testes automatizados).

Visualização (Front-end): Blade Templates com compilação dinâmica de estilos através do Vite.

Framework de Estilização: Tailwind CSS (com paleta de cores Dark Premium e design totalmente responsivo).

Testes Automatizados: PHPUnit integrado no ecossistema nativo do Laravel.

Controlo de Versão: Git (com histórico detalhado de commits para monitorização do desenvolvimento).

## 3. Modelação da Base de Dados (Relacionamentos)

A estrutura de dados foi projetada no paradigma relacional, otimizando as tabelas para evitar redundância. Para simplificar e tornar as permissões mais seguras, unificámos clientes, barbeiros e administradores na mesma tabela de utilizadores.

### 3.1 Tabela users (Utilizadores)

Armazena todos os intervenientes do sistema diferenciados pelo atributo role.

``id`` (PK)

``name`` (String) - Nome do utilizador.

``email`` (String, Unique) - E-mail para login.

``password`` (String) - Hash segura da senha do utilizador.

``role`` (String) - Nível de acesso do utilizador: ``'client'``, ``'barber'`` ou ``'admin'``.

``timestamps`` (created_at e updated_at).

### 3.2 Tabela services (Serviços)

Contém os serviços disponibilizados pela barbearia.

``id`` (PK)

``name`` (String) - Nome do serviço (ex: "Corte de Cabelo (Degradê Moderno)").

``price`` (Decimal, 8,2) - Preço em reais.

``duration_minutes`` (Integer) - Tempo estimado para realização.

timestamps.

### 3.3 Tabela appointments (Agendamentos / Marcações)

Entidade central de relacionamento "Muitos-para-Muitos" que conecta clientes, profissionais e serviços.

``id`` (PK)

``client_id`` (FK) - Aponta para users.id (utilizador com perfil 'client').

``barber_id`` (FK) - Aponta para users.id (utilizador com perfil 'barber').

``service_id`` (FK) - Aponta para services.id.

``date_time`` (DateTime) - Data e hora exata da marcação.

``status`` (String) - Estado atual da reserva: ``'pending'``, ``'confirmed'``, ``'completed'`` ou ``'canceled'``.

timestamps.

## 4. Funcionalidades e Regras de Negócio Implementadas

O projeto destaca-se pela aplicação rigorosa de validações e regras de segurança de nível profissional:

### 4.1 Autenticação e Autorização Seguro (Middleware)

Proteção de Rotas: Utilização do middleware auth para bloquear acessos anónimos a áreas privadas do sistema.

Diferenciação por Função (RBAC): Os controladores e rotas bloqueiam acessos com base no perfil do utilizador:

Clientes comuns recebem erro HTTP 403 (Forbidden) ao tentar aceder a recursos de gestão de funcionários ou serviços.

Barbeiros apenas gerem os agendamentos nos quais estão nominalmente escalados.

### 4.2 Regras de Negócio do Agendamento

Bloqueio do Passado: O sistema impede agendamentos em datas ou horários anteriores ao momento atual.

Horário de Funcionamento: Restrição lógica que apenas aceita marcações dentro do horário comercial da barbearia (Segunda a Sábado, das 09:00 às 19:00).

Prevenção de Choque de Horários: Validação no banco de dados que impede a gravação de um agendamento para um barbeiro que já possua um compromisso ativo no mesmo dia e horário.

### 4.3 Integridade Referencial

Ao tentar eliminar um serviço, o sistema valida se existem agendamentos históricos ou ativos associados. Se sim, a exclusão física é bloqueada temporariamente para evitar falhas de integridade na base de dados.

Regra análoga é aplicada ao remover barbeiros do quadro da empresa.

## 5. Testes Automatizados

Para garantir a robustez e estabilidade do BarberVibe contra falhas de regressão, foi desenvolvida uma suíte completa de testes automatizados de funcionalidade (Feature Tests):

### 5.1 Testes de Autenticação (LoginTest.php)

Redirecionamento Pós-Login do Cliente: Valida que utilizadores com o papel 'client' são enviados diretamente para o ecrã de marcações pessoal.

Redirecionamento Pós-Login de Gestão: Garante que administradores e barbeiros entram diretamente no painel de administração geral (/management).

### 5.2 Testes de Fluxo de Agendamento (AppointmentTest.php)

Bloqueio de Visitantes: Verifica o redirecionamento correto para login se um utilizador não autenticado tentar aceder às marcações.

Agendamento Válido: Valida o fluxo de preenchimento do formulário e inserção bem-sucedida de dados válidos.

Bloqueio de Horário Passado: Garante a rejeição automática de agendamentos no passado.

Bloqueio Fora de Horário: Valida a rejeição de marcações fora do expediente (ex: 22h).

Bloqueio de Agenda Duplicada: Confirma o bom funcionamento da regra de prevenção de conflitos de horário.

### 5.3 Resultados obtidos no Terminal

O projeto obteve aprovação total na execução da suíte de testes:

```test
PASS  Tests\Unit\ExampleTest
PASS  Tests\Feature\AppointmentTest
PASS  Tests\Feature\Auth\LoginTest
PASS  Tests\Feature\ExampleTest

Tests:    10 passed (31 assertions)
Duration: 1.59s
```

## 6. Manual de Instalação e Execução

Para rodar a aplicação localmente de forma simples, siga os passos abaixo:

### 6.1 Pré-requisitos

PHP 8.2 ou superior instalado.

Composer.

Node.js e NPM para compilação dos estilos Tailwind CSS.

### 6.2 Passos para Execução

Clonar o Repositório e Instalar Dependências:

```
composer install
npm install
```

Configurar as Variáveis de Ambiente:
Duplique o ficheiro .env.example para .env e configure a sua ligação à base de dados MySQL ou SQLite local:

````
cp .env.example .env
php artisan key:generate
````


Executar as Migrações e Alimentar o Banco (Seed):
Crie as tabelas e insira os utilizadores de teste iniciais (``admin@barbervibe.com.br``, ``barbeiro@barbervibe.com.br`` e ``cliente@barbervibe.com.br`` com a senha ``senha123``):

```
php artisan migrate --seed
```


Compilar os Assets de Estilo e Iniciar o Servidor:
```
npm run dev
```
### Em outro terminal, inicie o servidor local:
```
php artisan serve
```


Executar a Suíte de Testes:
```
php artisan test
```