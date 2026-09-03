# Handoff — <identificador>

<!--
Segurança: use somente nomes, referências e dados sanitizados. Nunca inclua
secrets, tokens, passwords, API keys, cookies, chaves privadas, credenciais,
dumps, dados pessoais ou sensíveis reais e payloads confidenciais não sanitizados.
-->

> O HANDOFF registra fatos confirmados da entrega, não o plano. Não declare
> implementação, validação, deploy, versão, publicação ou readiness sem evidência.

## Identificação

- Task:
- Unidade de implementação:
- Projeto:
- Perfil:
- Frente de origem:
- Frente de destino:
- SPEC:
- IMPLEMENTATION:
- PR:
- Branch:
- Commit/SHA/versão:
- Ambiente/canal/consumidor:

<!--
Use ambiente de staging, consumidor, ambiente integrado ou N/A conforme o perfil.
Assignee, status operacional, prioridade, datas e timer permanecem no ClickUp.
-->

## Entrega realizada

### Resumo

<!-- O que foi efetivamente implementado e por quê. -->

### Componentes realmente alterados

<!-- Liste somente os componentes relevantes; não reproduza todo o diff. -->

### Contratos preservados ou alterados

<!--
Contrato efetivamente entregue, consumidores, compatibilidade, breaking changes,
versões suportadas e caminho de atualização, quando aplicáveis.
-->

### Divergências em relação ao plano

<!-- Diferenças reais em relação à SPEC/IMPLEMENTATION e seus motivos. -->

## Validações executadas

<!--
Registre somente validações realmente executadas. Use N/A com justificativa para
dimensões relevantes que não se aplicam.
-->

| Validação | Comando/abordagem | Escopo | Resultado | Evidência/observação |
| --- | --- | --- | --- | --- |
| Formatter/lint |  |  |  |  |
| Testes focados |  |  |  |  |
| Suíte ampla |  |  |  |  |
| Análise estática |  |  |  |  |
| Segurança |  |  |  |  |
| Benchmark |  |  |  |  |
| Outra |  |  |  |  |

## Falhas preexistentes

<!-- Remova a seção quando nenhuma falha preexistente relevante tiver sido observada. -->

### <Falha>

- Problema:
- Evidência sanitizada:
- Causalidade em relação à demanda:
- Impacto:
- Decisão tomada:
- Follow-up/referência:

## Bypasses e exceções

<!--
Registre somente bypass ou exceção realmente utilizado e especificamente autorizado.
Remova a seção quando não se aplicar. Uma autorização não deve ser generalizada.
-->

### <Operação>

- Operação:
- Motivo:
- Evidência:
- Autorização específica:
- Resultado:

## Divergências entre trilhas

<!--
Registre diferenças reais entre DEV, STG, consumidor ou canal. Remova quando não
houver trilhas distintas nem divergências relevantes.
-->

### <Divergência>

- Trilhas/canais:
- Motivo:
- Equivalência funcional:
- Impacto de segurança:
- Justificativa:

## Impacto operacional

<!--
Ações, ambientes, canais, migrations, configuração, workers, distribuição ou
consumidores aplicáveis, com referências e resultados reais. Nunca inclua valores
secretos.
-->

## Readiness

<!-- Use somente READY | NOT READY | N/A e justifique cada dimensão aplicável. -->

| Dimensão | Estado | Justificativa/evidência |
| --- | --- | --- |
| Implementation Readiness | READY / NOT READY / N/A |  |
| Operational Readiness | READY / NOT READY / N/A |  |
| QA Readiness | READY / NOT READY / N/A |  |
| Production Readiness | READY / NOT READY / N/A |  |

Para packages, versão, tag, publicação e validação em consumidor podem ser
dimensões relevantes; deploy próprio pode ser N/A. Para Laravel com Interface
Integrada, backend, interface, build e assets podem pertencer à mesma entrega.

## Handoff para a próxima frente

- Próxima frente:
- Entrega/PR:
- Ambiente/canal disponível:
- Versão/SHA entregue:
- Blockers conhecidos fora do escopo:
- Pontos de atenção:
- Pode iniciar: Sim | Não — Justificativa:

<!--
O comentário operacional de handoff permanece no ClickUp; não o copie literalmente
para este arquivo.
-->

## Pendências e limitações

<!--
Follow-ups, dívidas, riscos residuais e itens deliberadamente fora do escopo, com
referências quando existentes.
-->

## Evidências e referências

<!-- PRs, releases, logs sanitizados, resultados e documentos verificáveis. -->
