Plano de Implementação - BarberVibe

1. Contexto

Tema

Sistema de Gerenciamento e Agendamento para Barbearia.

Objetivo da Aplicação

Desenvolver uma aplicação web robusta, intuitiva e moderna para facilitar o gerenciamento de clientes, barbeiros, serviços e agendamentos, permitindo maior controle, agilidade e organização na rotina diária da barbearia.

Problema que Resolve

Muitas barbearias realizam o controle de seus agendamentos manualmente ou por aplicativos de mensagens de forma descentralizada. Isso gera problemas como:

Duplicidade e conflito de horários para o mesmo barbeiro.

Dificuldade do cliente em visualizar os horários disponíveis em tempo real.

Falta de histórico de serviços e relatórios para o administrador.

Esquecimento de consultas por falta de confirmação clara.

Público-alvo

Administrador/Proprietário: Gerencia os serviços, visualiza todos os agendamentos e gerencia a equipe de barbeiros.

Barbeiros: Consultam sua própria agenda diária e atualizam o status dos atendimentos.

Clientes: Realizam o próprio cadastro, visualizam horários disponíveis e agendam serviços online de forma autônoma.

2. Escopo

Funcionalidades

Autenticação e Autorização (Controle de Acesso)

Login/Cadastro: Sistema único de cadastro de usuários com diferenciação de papéis (roles).

Níveis de Permissão:

Cliente: Pode gerenciar apenas seus próprios agendamentos.

Barbeiro: Pode visualizar sua própria agenda de trabalho.

Admin: Acesso total a todas as funções, relatórios, gestão de barbeiros e CRUD de serviços.

Clientes (CRUD)

Cadastrar cliente (autocadastro ou via administrador).

Listar clientes com busca e paginação.

Visualizar detalhes e histórico de agendamentos do cliente.

Editar dados cadastrais.

Excluir/Desativar conta do cliente.

Barbeiros (CRUD)

Cadastrar barbeiros (apenas administradores).

Listar barbeiros e especialidades.

Editar informações do profissional.

Remover profissional do quadro ativo.

Serviços (CRUD)

Cadastrar serviços (ex: Corte de Cabelo, Barba, Combo).

Definir valor (decimal) de cada serviço.

Definir duração estimada (em minutos).

Listar, editar e excluir serviços disponíveis.

Agendamentos (CRUD)

Criar agendamento (Cliente escolhe serviço, barbeiro, data e hora disponível).

Consultar agenda em formato de lista ou painel diário.

Cancelar agendamento (dentro das regras de antecedência).

Reagendar (alterar data, hora ou profissional).

Atualizar status do agendamento (Pendente, Confirmado, Concluído, Cancelado).

3. Entidades do Banco de Dados (Modelagem)

Para manter o banco limpo, unificaremos clientes e funcionários na tabela users diferenciados pelo campo role.

1. User (Tabela users)

id (Primary Key)

name (string)

email (string, unique)

password (string)

role (enum: 'client', 'barber', 'admin')

created_at & updated_at (timestamps)

2. Service (Tabela services)

id (Primary Key)

name (string)

price (decimal, 8, 2)

duration_minutes (integer)

created_at & updated_at (timestamps)

3. Appointment (Tabela appointments)

id (Primary Key)

client_id (foreign key referenciando users.id)

barber_id (foreign key referenciando users.id)

service_id (foreign key referenciando services.id)

date_time (datetime)

status (enum: 'pending', 'confirmed', 'completed', 'canceled')

created_at & updated_at (timestamps)

4. Telas da Aplicação

Página Inicial (Landing Page): Apresentação da barbearia, portfólio de cortes e link para login/cadastro.

Login / Cadastro: Formulários limpos com validação em tempo real.

Dashboard Geral: Painel dinâmico que muda dependendo do papel do usuário logado (Cliente vê suas próximas consultas, Admin vê resumo do dia).

Clientes (Gestão): Listagem e ações de gerenciamento de clientes.

Barbeiros (Gestão): Listagem e ações de gerenciamento de barbeiros.

Serviços (Gestão): CRUD de serviços oferecidos pela barbearia.

Agendamentos: Interface de marcação e listagem da agenda com filtros.

Perfil do Usuário: Edição de dados cadastrais e senha.

5. Ordem de Implementação

Setup Inicial: Configuração do Laravel e instalação do Laravel Boost.

Base de Dados: Configuração do SQLite no .env para facilitar o ambiente de testes do professor.

Estrutura de Dados: Criação das Migrations, Models, Relacionamentos e Seeders com usuários de teste.

Autenticação: Login, Logout e registro utilizando a identidade visual do projeto.

Políticas de Acesso (Middlewares): Proteção de rotas para clientes e administradores.

CRUD de Serviços: Implementação da gestão de serviços para o administrador.

CRUD de Agendamentos: Criação do fluxo de agendamento e listagem para o cliente.

Painel do Administrador: Visualização global de todas as marcações.

Polimento Visual: Aplicação consistente da identidade Dark Premium usando Tailwind CSS.

Testes Automatizados: Criação de testes básicos para garantir estabilidade e obter nota bônus.

Documentação Final: Elaboração do README e do RELATORIO.md.

6. Tecnologias Utilizadas

Framework Principal: Laravel 12 (PHP 8.2+)

Banco de Dados: SQLite (pela portabilidade e simplicidade na entrega acadêmica)

Frontend: Blade Templates com estilização Tailwind CSS (Tema Dark)

Camada de Dados: Eloquent ORM com Migrations e Seeders

Controle de Versão: Git & GitHub (Mínimo de 10 commits estruturados)

Auxiliares: Laravel Boost, GitHub Copilot e Context7 MCP

7. Riscos e Mitigações

Risco de Conflito de Horário: Dois clientes agendarem com o mesmo barbeiro no mesmo horário.

Mitigação: Regra de validação customizada no Laravel que impede a inserção de agendamentos duplicados para o mesmo barber_id na mesma data/hora.

Invasão de Privacidade: Um cliente conseguir ver ou cancelar o agendamento de outro cliente.

Mitigação: Uso rigoroso de Laravel Policies e Route Model Binding para validar o dono do registro.

Erros de Formulário: Dados inseridos incorretamente gerando quebras no banco de dados.

Mitigação: Uso obrigatório de FormRequests para validação robusta de todos os inputs antes de salvar.

8. Critérios de Aceite

Todo o sistema deve ser protegido por autenticação padrão do Laravel.

A base de dados deve ser criada do zero rodando php artisan migrate --seed com sucesso.

O cadastro e edição de agendamentos devem passar por validações de regras de negócio (sem datas passadas, sem duplicidade).

O design deve ser totalmente responsivo (Mobile-First) para que clientes possam agendar usando o celular de forma confortável.

Código limpo e validado de acordo com as Skills do Laravel Boost.