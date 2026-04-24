# Instruções para o assistente (Copilot/Chat)

Objetivo
--
Este arquivo descreve como você, assistente inteligente, deve ajudar no desenvolvimento do site "Loja Esportes" — um projeto escolar em PHP/MySQL rodando em XAMPP (Windows). O prazo final é o fim do ano; preserve a estabilidade do projeto e prefira mudanças pequenas e revisáveis.

Como ajudar
--
- Peça sempre contexto antes de grandes mudanças (qual página, qual comportamento esperado, prints ou exemplos).
- Use `apply_patch` para quaisquer alterações em arquivos do repositório. Faça commits pequenos e focados.
- Atualize a lista de tarefas (`manage_todo_list`) no início de atividades multi‑etapa e marque progresso.
- Teste localmente sempre que possível (instruções de execução abaixo) e reporte resultados.

Visão curta do projeto
--
- PHP + MySQL (phpMyAdmin). Executar em XAMPP/Apache no Windows.
- Estrutura principal: arquivos PHP na raiz, `css/`, `cache/`.
- Principais páginas: index.php, produto.php, carrinho.php, checkout.php, login.php.

Convenções e limites
--
- Mantenha mudanças mínimas e reversíveis (evite refactors amplos sem aprovação).
- Siga estilo PHP procedural já presente; não converta para frameworks sem pedir.
- Não exponha credenciais sensíveis no código. Se precisar de dados de configuração, peça-os ao dono do projeto e sugira usar um arquivo `.env` local ignorado pelo git.

Banco de dados
--
- O banco está em phpMyAdmin. Pergunte pelo nome do DB, usuário e senha quando precisar operar diretamente.
- Para alterações de esquema, proponha um script SQL separado (`migrations/`) e explique o impacto antes de aplicar.

Operações comuns (fluxo sugerido)
--
1. Peça detalhes do problema (página, entrada, comportamento esperado).
2. Navegue nos arquivos relevantes e proponha a mudança (mostre trechos de código).
3. Aplique mudanças com `apply_patch` em passos pequenos.
4. Teste localmente (passos na seção abaixo) e informe resultados.

Testes e execução local
--
- Instruções rápidas para o autor executar localmente:
	- Colocar o projeto em `C:\xampp\htdocs\loja_esportes`.
	- Iniciar Apache e MySQL no painel do XAMPP.
	- Abrir phpMyAdmin, restaurar/importar o dump se necessário.
	- Acessar `http://localhost/loja_esportes/`.

Segurança e dados sensíveis
--
- Nunca coloque senhas em commits públicos. Recomende `.env` e `config.php` local carregado via `.gitignore`.
- Valide e higienize todos os inputs (especialmente dados de checkout e login).

Formato de respostas do assistente
--
- Seja conciso e direto. Ao propor mudanças: 1) resumo, 2) arquivos afetados, 3) patch aplicado (ou solicitação para aplicar), 4) instruções de teste.
- Use links de arquivo relativos quando referenciar arquivos do repositório.

Prioridades do projeto
--
1. Checkout e fluxo de pagamento funcionando e seguro.
2. Cadastro, login e gerenciamento de pedidos.
3. Catálogo de produtos e página de produto.
4. Layout responsivo e usabilidade.

Checklist de entrega para cada tarefa
--
- Código implementado com `apply_patch`.
- Testes básicos manuais executados e descritos.
- Nenhuma credencial no repositório.
- Documentação curta das alterações (comentário de commit / nota no PR).

Quando pedir ao usuário
--
- Sempre peça: descrição do bug/feature, passos para reproduzir, prints/URLs, e qual ambiente (local/servidor).

Observação final
--
Siga estas diretrizes para trabalhar como assistente parceiro: seja cuidadoso, peça permissão para mudanças grandes e prefira progressos incrementais.

