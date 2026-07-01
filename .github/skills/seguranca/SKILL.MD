Skill - Segurança e Autorização de Acesso

Esta skill define as políticas de segurança, proteção contra invasões de privilégio e controle de acesso baseado em papéis (RBAC - Role-Based Access Control) na aplicação.

1. Autenticação e Rotas Protegidas

Nenhuma tela de agendamento ou gestão pode ser acessada sem autenticação prévia (uso do middleware auth).

As rotas administrativas (gestão de serviços, visualização de faturamento e agenda completa de todos os barbeiros) devem ser protegidas por um middleware customizado para verificar se o usuário possui a função admin ou barber.

2. Autorização de Nível de Dados (Laravel Policies)

Um cliente comum nunca deve conseguir visualizar, alterar ou excluir um agendamento que pertença a outro usuário cliente.

Deve-se implementar uma AppointmentPolicy para validar se:

O client_id do agendamento confere com o id do usuário autenticado para as ações de visualização e cancelamento.

O usuário tem papel de admin ou barber para editar qualquer agendamento de forma irrestrita.

3. Segurança contra Ataques Comuns

Proteção CSRF ativa em todos os formulários através da diretiva @csrf.

Escapar dados exibidos nas views utilizando sempre {{ $variavel }} em vez de {!! $variavel !!}, evitando falhas de Cross-Site Scripting (XSS).