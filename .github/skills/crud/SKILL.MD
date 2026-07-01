Skill de Desenvolvimento de CRUD - BarberVibe

Esta skill garante que todos os fluxos de criação, leitura, atualização e exclusão (CRUD) sigam rigorosamente as melhores práticas do Laravel e a arquitetura MVC de forma consistente.

1. Estrutura do Código e Camadas

Controllers: Devem ser mantidas o mais enxutas possíveis (Skinny Controllers). Toda a validação deve ser delegada para classes personalizadas de request (Form Requests).

Form Requests: Obrigatórios para criação e atualização de dados. Devem conter regras rígidas de validação e sanitização de dados.

Models: Devem usar Mass Assignment de forma segura, declarando explicitamente a propriedade $fillable ou protegendo chaves sensíveis com $guarded.

Views Blade: Devem estender um layout base comum (layouts.app) para reaproveitamento de componentes estruturais (Header, Sidebar, Footer).

2. Regras e Boas Práticas específicas para o Laravel

Validação de Agendamentos:

Não permitir agendamentos com data e hora no passado.

Verificar se o horário solicitado está dentro do expediente de funcionamento da barbearia (ex: 08:00 às 19:00).

Validar via banco se o barbeiro escolhido não possui outro agendamento pendente/confirmado no mesmo horário.

Feedback ao Usuário:

Toda ação bem-sucedida de escrita (salvar, editar, excluir) deve redirecionar o usuário enviando uma mensagem de sucesso via sessão (with('success', '...')).

Paginação:

Listagens de agendamentos e serviços devem conter paginação ativa (paginate(10) no controller) para garantir performance do sistema.