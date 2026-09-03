# SDD — Spec-Driven Development

<!-- sdd-kit:identity:start -->
SDD Kit: humu-backend-sdd-kit
Version: 0.2.0-rc.4
Profile: package
<!-- sdd-kit:identity:end -->

<!-- sdd-kit:common:start -->
## Finalidade

Esta pasta mantém a especificação técnica versionada necessária para desenvolver,
validar, registrar decisões relevantes e transferir conhecimento sobre mudanças
realizadas neste repositório.

O Kit utiliza templates-base comuns. O perfil orienta como interpretar contratos,
validações, dependências e readiness sem criar variantes dos templates.

## Relação entre ClickUp, specs e código

O ClickUp é a fonte operacional da demanda: estado, responsáveis, prioridade,
sprint e coordenação permanecem nele. Os documentos em `specs/` complementam a
task com detalhes técnicos que precisam evoluir junto do código.

Dados operacionais mutáveis não devem ser copiados sem necessidade. Prefira IDs,
links, frentes, papéis e dependências. Nomes de responsáveis, status corrente,
timers, datas e prioridade permanecem no ClickUp.

A SPEC define o comportamento esperado. O IMPLEMENTATION traduz esse comportamento
para o estado real do repositório. O código materializa a solução; QA, decisões e
handoff registram validações, escolhas relevantes e o resultado entregue.

Esses artefatos não substituem a task, as instruções do projeto ou referências
organizacionais. Requisitos ausentes não podem ser inventados. Lacunas capazes de
alterar escopo, contrato, segurança, arquitetura ou operação devem ser resolvidas
antes da implementação.

## Unidade de implementação

Cada demanda documentada usa uma pasta:

```text
specs/<clickup-task-id>-<slug>/
```

Use o identificador oficial da unidade efetivamente implementada no repositório.
Não adicione prefixo artificial ao ID. Tasks e frentes relacionadas são
referenciadas dentro da SPEC sem alterar a identidade da pasta.

Não use diretórios `active/` ou `completed/`: o estado operacional permanece no
ClickUp.

## Artefatos

| Artefato | Responsabilidade |
| --- | --- |
| `SPEC.md` | Objetivo, escopo, regras, critérios, contratos, dependências e impactos. |
| `SPEC-REDUCED.md` | Mudança simples, localizada e de baixo risco. |
| `IMPLEMENTATION.md` | Plano baseado na SPEC e no estado real do projeto. |
| `QA.md` | Estratégia, cenários e evidências técnicas versionadas. |
| `DECISIONS.md` | Decisões técnicas relevantes, alternativas e consequências. |
| `HANDOFF.md` | Resultado entregue, validações, divergências, readiness e pendências confirmadas. |
| `assets/` | Evidências auxiliares indispensáveis e seguras para versionamento. |

Copie para a pasta da demanda apenas os documentos aplicáveis. Remova instruções
que não agreguem informação e crie `assets/` somente quando necessário.

## Subtasks dinâmicas

Subtasks representam somente as frentes aplicáveis; não existe estrutura rígida
obrigatória. Backend, Frontend, QA Backend, QA Frontend e outras unidades são
criadas apenas quando necessárias. Use N/A quando a ausência de uma frente precisar
ser registrada como decisão consciente.

## Lifecycle operacional

O lifecycle de referência é:

```text
Task mãe -> readiness operacional -> subtasks aplicáveis -> SPEC
-> IMPLEMENTATION -> implementação -> PR [DEV] Draft
-> trilha funcional STG -> PR [STG] Open -> review/merge STG
-> deploy STG -> handoff -> QA -> PASS, FAIL ou BLOCKED
-> DEV Ready for Review -> merge DEV -> PROD
-> encerramento -> limpeza avaliada
```

Etapas não aplicáveis ao perfil ou à demanda devem ser marcadas como N/A e
justificadas, sem criar frentes, branches ou ambientes artificiais.

### Estado de validação do lifecycle

O piloto validou task, SPEC, implementação, PR `[DEV]` Draft, trilha funcional
derivada de staging, PR `[STG]` Open, review, merge, deploy de STG, interrupção do
timer após trabalho ativo e liberação de QA somente após deploy e handoff.

QA PASS até revisão final de DEV, QA FAIL com correção e revalidação, produção,
encerramento integral e limpeza ainda precisam de validação ponta a ponta. Não
trate etapas ainda não validadas como fatos concluídos.

### Status, timer e handoff

Status indica posição no lifecycle. Timer mede somente esforço humano ativo;
esperas por review, merge, deploy, fornecedor ou gate externo não contam como
implementação ativa.

Handoff ocorre quando a frente anterior terminou, a entrega está disponível e a
próxima pode começar. A notificação operacional identifica origem, destino,
entrega, ambiente, versão ou SHA quando aplicável, divergências e bloqueios. Não
duplique literalmente essa notificação nos artefatos versionados.

### Trilhas DEV e STG

A PR de desenvolvimento usa a branch declarada pelo projeto como base, recebe
`[DEV]` e nasce Draft. A branch funcional de staging parte diretamente da branch
remota de staging e sua PR recebe `[STG]` e nasce Open.

O objetivo entre trilhas é equivalência funcional e de segurança, não identidade
byte a byte. Divergências intencionais precisam de justificativa. Artefatos
canônicos podem exigir hashes idênticos quando essa for a finalidade explícita.

### Gate de QA

A PR `[STG]` não libera QA. O gate exige review e merge, deploy de staging,
versão confirmada e handoff. Teste local não substitui validação no ambiente
oficial salvo exceção explícita.

Em QA PASS, registre evidências antes de avançar DEV. Em QA FAIL, registre o
finding, crie nova subtask ou unidade de correção quando necessária, implemente a
correção, repita a validação em STG quando aplicável e submeta a entrega à nova
validação de QA. Em BLOCKED, registre causa, evidência e responsabilidade sem
converter o bloqueio automaticamente em falha funcional. Não reabra silenciosamente
trabalho concluído para ocultar esforço novo.

### Produção e causalidade

PR destinada à produção recebe `[PROD]`. Gates de produção não comprovados não
devem ser inventados. Branches funcionais não são limpas enquanto o ciclo estiver
ativo.

Falha externa ou preexistente deve ser separada causalmente da demanda atual.
CI quebrada, configuração antiga ou dívida técnica não ampliam automaticamente o
escopo; follow-up exige decisão consciente.

## Proporcionalidade

SDD é proporcional à complexidade e ao risco.

| Tipo | Documentação esperada |
| --- | --- |
| Bug simples/localizado | SPEC reduzida e HANDOFF; demais conforme necessidade. |
| Bug relevante | SPEC, IMPLEMENTATION, QA e HANDOFF; DECISIONS quando necessário. |
| Feature padrão ou crítica | SPEC, IMPLEMENTATION, QA e HANDOFF; DECISIONS quando aplicável. |
| Refactor sem mudança de comportamento | SPEC reduzida; plano e QA conforme risco; HANDOFF reduzido. |
| Hotfix | Documentação mínima segura e complementação posterior quando necessária. |
| Investigação sem alteração | SDD não obrigatório. |

Use SPEC completa e os demais documentos aplicáveis quando houver risco relevante
em contrato, segurança, autorização, dados sensíveis, concorrência, integração,
persistência, migration, processamento assíncrono, múltiplos consumidores,
distribuição, compatibilidade ou impacto operacional.

Impacto operacional relevante exige no mínimo SPEC completa, IMPLEMENTATION e
HANDOFF. Tecnologia isolada não determina complexidade; avalie alcance, risco e
comportamento observável.

## Readiness

Código concluído não implica entrega pronta. Readiness confirma condições
técnicas, operacionais e de validação necessárias ao uso da entrega.

Avalie somente dimensões aplicáveis: configuração externa, migrations, workers,
observabilidade, compatibilidade, distribuição, documentação, consumidores e
ambientes. Ausência ou N/A devem ser explícitos, não preenchidos por suposição.

## Segurança

Nunca versione em `specs/` ou `assets/` valores secretos, credenciais, chaves
privadas, dumps, dados pessoais ou sensíveis reais, payloads confidenciais não
sanitizados ou anexos inadequados. Use referências, identificadores, dados
fictícios e evidências sanitizadas.

## Fluxo resumido

1. Identifique a unidade e sua task operacional.
2. Escolha SPEC completa ou reduzida conforme complexidade e risco.
3. Resolva lacunas que alterem comportamento, contrato, segurança ou operação.
4. Consolide o plano após inspecionar o projeto atual.
5. Implemente e valide com rastreabilidade entre critérios e cenários.
6. Registre decisões técnicas relevantes, sem transformar DECISIONS em diário.
7. Consolide no HANDOFF somente o entregue e realmente validado.
8. Avalie dependências e readiness sem confundir código pronto com entrega pronta.
<!-- sdd-kit:common:end -->

<!-- sdd-kit:profile:start -->
## Perfil — Package/Biblioteca

O contrato principal é reutilizado por consumidores. Classes, interfaces, DTOs,
enums, traits, service providers, bindings, configuração, eventos, comandos,
exceções e comportamento documentado podem constituir contrato público.

Use SPEC completa quando houver impacto relevante em contrato público,
compatibilidade, consumidor, instalação, configuração, semver, distribuição,
breaking change, deprecation, segurança ou integração externa. Correção interna
localizada e comprovadamente compatível pode usar SPEC reduzida.

Identifique consumidores conhecidos e suas constraints sem presumir que o
workspace contém todos os consumidores. Ausência de referência local não prova
ausência de consumo externo.

QA pode exigir matriz de PHP, framework e dependências, instalação limpa,
autodiscovery, configuração publicada, contrato público, upgrade e consumidor
representativo.

Readiness separa código pronto, testes aprovados, consumidor validado, versão
definida, tag criada e publicação concluída. Staging ou deploy próprio podem ser
N/A; mudanças necessárias em consumidores são avaliadas separadamente.
<!-- sdd-kit:profile:end -->

## Governança operacional Humu

Quando houver ação operacional necessária em consumidor ou ambiente, utilize a
task central **Demandas DevOps** da sprint aplicável. O registro deve referenciar
a task de origem e informar projeto ou serviço consumidor, ambiente ou canal,
ação necessária e status, sem armazenar valores secretos.

Para packages, impactos possíveis incluem Composer Auth, mudança de constraint,
configuração, referência a secrets, atualização do consumidor, limpeza de cache e
deploy do consumidor. São exemplos condicionais, não requisitos universais. Se
não houver ação operacional, registre como não aplicável.

## Particularidades deste package

- Integra-se diretamente com Twilio e SendGrid.
- Declara compatibilidade com Laravel 10 e 11.
- Possui múltiplos consumidores conhecidos, que devem ser descobertos e avaliados
  conforme a mudança em vez de mantidos como lista imutável neste documento.
- Não possui persistência nem processamento assíncrono próprios.
- Seus efeitos externos são síncronos.
- Consumidores podem usar o package dentro de jobs próprios; isso não torna o
  package assíncrono.

As questões abaixo não devem ser resolvidas por suposição ou incidentalmente:

- comportamento esperado da configuração publicada e de sua instalação opcional;
- comportamento de `MessageSent` quando um provider retorna `status=error`;
- compatibilidade Laravel 10 e 11 ainda não comprovada por matriz automatizada.
