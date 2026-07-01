Skill de Identidade Visual - BarberVibe

Esta skill orienta a inteligência artificial sobre a UI/UX, paleta de cores e padronização visual de todas as telas do sistema BarberVibe.

1. Conceito e Estilo Visual

Tema Principal: Dark Mode Moderno e Premium (estilo barbearia clássica americana / barber shop industrial).

Paleta de Cores (Tailwind CSS):

Fundo (Background): Slate Escuro (bg-slate-900) ou Zinc (bg-zinc-950).

Card / Painéis: Gray Escuro (bg-zinc-900) com bordas sutis (border-zinc-800).

Cor de Destaque (Accent): Âmbar / Dourado (text-amber-500, bg-amber-500, hover:bg-amber-600) para botões de ação primária, links ativos e detalhes.

Textos: Branco para títulos (text-zinc-100) e Cinza Claro para descrições (text-zinc-400).

2. Tipografia e Componentes

Tipografia: Sem-serifa moderna, limpa e legível (Font Sans nativa do Tailwind).

Botões:

Arredondados (rounded-lg ou rounded-md).

Efeitos de transição suaves ao passar o mouse (transition-colors duration-200).

Inputs / Formulários:

Fundo escuro (bg-zinc-800), texto claro, bordas discretas e anel de foco âmbar (focus:ring-2 focus:ring-amber-500 focus:border-transparent).

Alertas e Mensagens:

Sucesso: Fundo verde escuro com texto verde claro (bg-emerald-950 text-emerald-300 border-emerald-800).

Erro: Fundo vermelho escuro com texto vermelho claro (bg-rose-950 text-rose-300 border-rose-800).

3. Responsividade e UX

Todas as visualizações de tabelas e listas devem quebrar para formato de "Cards" em telas de celulares para evitar rolagem horizontal.

O menu de navegação deve possuir um design colapsável para dispositivos móveis.