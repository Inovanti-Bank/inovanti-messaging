# Validação técnica / QA — <identificador>

<!--
Segurança: use somente nomes, referências e dados sanitizados. Nunca inclua
secrets, tokens, passwords, API keys, cookies, chaves privadas, credenciais,
dumps, dados pessoais ou sensíveis reais e payloads confidenciais não sanitizados.
-->

> A subtask de QA coordena o trabalho operacional. Este arquivo mantém a estratégia,
> a execução e as evidências técnicas versionadas, sem copiar literalmente a
> descrição da subtask nem substituir o ClickUp.

## Identificação

- Task ClickUp:
- Subtask QA:
- Unidade de implementação:
- SPEC:
- IMPLEMENTATION:
- Projeto:
- Perfil:
- Frente validada:
- Ambiente/canal/consumidor:
- PR:
- Commit/SHA/versão implantada:

<!--
Use ambiente de staging, consumidor, ambiente integrado ou N/A conforme o perfil.
Assignee, status operacional, prioridade, datas e timer permanecem no ClickUp.
-->

## Objetivo e escopo

<!-- Comportamentos, contratos e riscos cobertos por esta validação. -->

## Entry gate

<!-- Marque somente itens aplicáveis e justifique N/A quando necessário. -->

- PR de STG mergeada: Sim | Não | N/A — Justificativa:
- Deploy/distribuição concluído: Sim | Não | N/A — Justificativa:
- Versão disponível para validação: Sim | Não | N/A — Evidência:
- Handoff recebido: Sim | Não | N/A — Referência:
- Blockers conhecidos:
- Gate atendido: Sim | Não

## Dados e pré-condições

<!--
Permissões, configuração, dependências e dados sintéticos ou sanitizados necessários.
Nunca inclua credenciais, secrets ou payloads sensíveis reais.
-->

## Critérios da validação

- PASS:
- FAIL:
- Regressões que invalidam a entrega:
- Requisitos de segurança/permissões:
- Contratos que precisam ser preservados:

## Cenários

<!--
Use QA-01, QA-02 etc. e relacione aos critérios de aceite. Inclua somente os
cenários aplicáveis ao perfil e ao risco.
-->

### QA-01 — <Cenário>

- Critérios relacionados: CA-01
- Objetivo:
- Pré-condições:
- Entrada:
- Execução:
- Resultado esperado:
- Resultado obtido:
- Status: PASS | FAIL | BLOCKED
- Evidência:

## Preservação e regressão

<!--
O que deve continuar funcionando, aparecendo ou permanecendo compatível após a
mudança. Inclua fluxos, contratos, dados, interface, instalação ou consumidores
relevantes para evitar over-correction.
-->

## Segurança e contratos

<!--
Resultados de autenticação, autorização, isolamento, exposição de dados, validação
de entrada e preservação de contratos públicos ou externos.
-->

## Evidências

<!--
Referencie logs sanitizados, request/correlation IDs, screenshots, links, respostas,
resultados técnicos, relatórios ou validações em consumidor. Não exponha dados
sensíveis reais.
-->

## Divergências conhecidas

<!--
Diferenças intencionais entre DEV, STG, consumidor ou canal; limitações e
comportamentos aceitos, com justificativa e impacto de segurança.
-->

## Fora do escopo e problemas preexistentes

<!--
CI externa, configuração antiga, dívida técnica, falhas preexistentes e itens não
causados pela demanda. Registre evidência e causalidade sem absorvê-los
automaticamente no escopo atual.
-->

## Resultado da validação

- Resultado: PASS | FAIL | BLOCKED
- Evidências principais:
- Observações:
- Recomendação para a próxima etapa:

### Findings, quando FAIL

<!-- Findings e referência à nova subtask de correção, quando criada. -->

### Blocker, quando BLOCKED

- Blocker:
- Causa:
- Pertence à implementação atual: Sim | Não | Não determinado
- Evidência/referência:

<!--
O resultado documenta a validação; não automatiza status, transições, merges ou
qualquer outra etapa do lifecycle descrito em specs/README.md.
-->
