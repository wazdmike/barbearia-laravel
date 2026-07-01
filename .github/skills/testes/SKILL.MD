Skill - Testes Automatizados (Feature e Unit Tests)

Esta skill orienta a inteligência artificial na geração de testes automatizados para garantir a robustez e alcançar a nota bônus de até 1,0 ponto descrita nos critérios de avaliação do IFSP.

1. Padrão de Escrita de Testes

Utilizar o framework padrão de testes do Laravel (PHPUnit).

Utilizar traits de banco de dados limpo para cada rodada de teste (use RefreshDatabase).

Separar os testes lógicos em arquivos na pasta tests/Feature/.

2. Casos de Teste Essenciais para a Barbearia

Teste de Autenticação:

Garantir que usuários não autenticados sejam redirecionados para a tela de login ao tentar acessar a dashboard de agendamentos.

Teste de Criação de Agendamento:

Garantir que um cliente logado consiga preencher o formulário e criar um agendamento com sucesso.

Garantir que falhe se o cliente tentar agendar em um horário que já esteja reservado para aquele barbeiro.

Teste de Acesso Indevido (Segurança):

Validar que um cliente comum receba o erro HTTP 403 (Forbidden) ao tentar editar ou excluir um agendamento que pertence a outro usuário cliente.