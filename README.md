<div align="center">
  <h1>Criador de estratégias para Lugo Bots</h1>

  <p>
    Cliente em PHP para criar <a href="https://lugobots.ai/">Lugo Bots</a>
  </p>

<!-- Badges -->
<p>
<img alt="PRs welcome!" src="https://img.shields.io/static/v1?label=PRs&message=WELCOME&style=for-the-badge&color=3b82f6&labelColor=222222" />
  <a href="https://github.com/mauriciorobertodev/lugo4php/graphs/contributors">
    <img src="https://img.shields.io/github/contributors/mauriciorobertodev/lugo4php?color=3b82f6&label=CONTRIBUTORS&logo=3C424B&logoColor=3C424B&style=for-the-badge&labelColor=222222" alt="contributors" />
  </a>
  <a href="">
    <img src="https://img.shields.io/github/last-commit/mauriciorobertodev/lugo4php?color=3b82f6&label=LAST UPDATE&logo=3C424B&logoColor=3C424B&style=for-the-badge&labelColor=222222" alt="last update" />
  </a>
  <a href="https://github.com/mauriciorobertodev/lugo4php/network/members">
    <img src="https://img.shields.io/github/forks/mauriciorobertodev/lugo4php?color=3b82f6&label=FORKS&logo=3C424B&logoColor=3C424B&style=for-the-badge&labelColor=222222" alt="forks" />
  </a>
  <a href="https://github.com/mauriciorobertodev/lugo4php/stargazers">
    <img src="https://img.shields.io/github/stars/mauriciorobertodev/lugo4php?color=3b82f6&label=STARS&logo=3C424B&logoColor=3C424B&style=for-the-badge&labelColor=222222" alt="stars" />
  </a>
  <a href="https://github.com/mauriciorobertodev/lugo4php/issues/">
    <img src="https://img.shields.io/github/issues/mauriciorobertodev/lugo4php?color=3b82f6&label=ISSUESS&logo=3C424B&logoColor=3C424B&style=for-the-badge&labelColor=222222" alt="open issues" />
  </a>
  <a href="https://github.com/mauriciorobertodev/lugo4php/blob/master/LICENSE">
    <img src="https://img.shields.io/github/license/mauriciorobertodev/lugo4php.svg?color=3b82f6&label=LICENSE&logo=3C424B&logoColor=3C424B&style=for-the-badge&labelColor=222222" alt="license" />
  </a>
</p>

![Alt text](https://raw.githubusercontent.com/mauriciorobertodev/lugo4php/main/screenshot.png)

</div>

<br />

<!-- About the Project -->

## :star2: Sobre o projeto

Lugo4PHP é um pacote feito em PHP criado para conectar um bot com servidor do [Lugo Bots](https://lugobots.ai/), escondendo a complexidade da conexão gRPC e disponibilizando várias classes e métodos que auxiliam um desenvolvedor a criar seu próprio bot.

<!-- Features -->

### :dart: Features

- Conectar ao game server do Lugo Bots.
- Classe env para capturar as variáveis e validar.
- Wrapper de classes do game para uma tipagem mais forte.
- Vários métodos comumente usadas já embutido no `Point` e no `Vector2D`, por exemplo `add()`, `scale()`, `subtract()`, `divide()` e outros...
- Vários métodos comumente usadas já embutido no `Player` e `Ball`, por exemplo `$ball->distanceToPoint($point)`, `$ball->directionToPlayer($player)` e outros...
- Vários métodos de atalho para um acesso mais rápido e claro, por exemplo `$inspector->getBallDirection()` em vez de `$inspector->getBall()->getVelocity()->getDirection()`.
- Vários métodos alternativos para gerar uma ordem, por exemplo `$inspector->makeOrderLookAtDirection($direction)`, `$inspector->makeOrderMoveToRegion($region)`, `$inspector->makeOrderKickToPlayer($player)` e outros...
- Vários métodos com duplicados, podendo optar por um que lança uma exceção ou um retorno nulo, por exemplo `$inspector->getMyPlayer(10)` (lança um erro caso o jogador não exista) e `$inspector->tryGetMyPlayer(10)` (retorna null caso o jogador não exista).
- 94% do código coberto por testes automatizados, facilitando o incremento de novas funcionalidades, com uma change muito menor de quebrar as já existentes.
- Totalmente documentada, para uma busca rápida, acesse: [Lugo4php Docs](https://lugo4php.mauricioroberto.com).


<br>

<!-- Usage -->

## :zap: Como usar

<a href="https://github.com/mauriciorobertodev/the-dummies-php">Veja o the-dummies-php, um bot criado usando esse pacote</a>

<br/>

<!-- Run Locally -->

## :wrench: Desenvolvimento

Clone o projeto

```bash
  git clone https://github.com/mauriciorobertodev/lugo4php.git
```

Entre na pasta do projeto

```bash
  cd lugo4php
```

Instale as dependências

```bash
  composer install
```

Rode um jogo de teste usando o `BotTester`

```bash
  docker compose -f ./example/bot/docker-compose.yml up --remove-orphans
```

<br>

<!-- Contributing -->

## :wave: Contribuindo

Contribuições são sempre bem vindas!

1. Faça o _fork_ do projeto (<https://github.com/mauriciorobertodev/lugo4php/fork>)
2. Crie uma _branch_ para sua modificação (`git checkout -b meu-novo-recurso`)
3. Faça o _commit_ (`git commit -am 'Adicionando um novo recurso...'`)
4. _Push_ (`git push origin meu-novo-recurso`)
5. Crie um novo _Pull Request_

</br>

<a href="https://github.com/mauriciorobertodev/lugo4php/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=mauriciorobertodev/lugo4php" />
</a>
</br>

<br>

<!-- License -->

## :lock: License

Licença MIT (MIT). Consulte o [arquivo de licença](https://github.com/mauriciorobertodev/lugo4php/LICENSE) para obter mais informações.

<br>

<!-- Contact -->

## :link: Links úteis
Portfolio: [mauricioroberto.com/](mauricioroberto.com/)

Link do projeto: [https://github.com/mauriciorobertodev/lugo4php](https://github.com/mauriciorobertodev/lugo4php)

Link da documentação: [https://lugo4php.mauricioroberto.com](https://lugo4php.mauricioroberto.com)

Link do the-dummies-php: [https://github.com/mauriciorobertodev/the-dummies-php](https://github.com/mauriciorobertodev/the-dummies-php)

<br>

<!-- Acknowledgments -->

## :gem: Créditos/Reconhecimento

-   [Shields.io](https://shields.io/)
-   [Awesome Readme Template](https://github.com/Louis3797/awesome-readme-template)
-   [Emoji Cheat Sheet](https://github.com/ikatyang/emoji-cheat-sheet/blob/master/README.md#travel--places)

<br>

<!-- References -->

## :microscope: Referências

-   [Lugo Bots Specs](https://spec.lugobots.ai/)
-   [lugo4node](https://github.com/lugobots/lugo4node)
-   [gRPC php Quick Start](https://grpc.io/docs/languages/php/quickstart/)
