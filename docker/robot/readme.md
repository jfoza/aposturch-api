# Testes funcionais com Robot Framework

## Requisitos

Para rodar os testes funcionais com o Robot Framework é necessário somente ter o docker instalado na máquina.

Também é necessário que a aplicação do e-commerce esteja rodando, caso não a tenha configurado pode ser seguido [este readme](../../readme.md).

## Rodando os testes

- Inicialmente crie o arquivo .env.test com o comando abaixo:
  
  ```cp .env.test.example .env.test```
- Acessar a pasta docker do robot:
  
  ``` cd docker/robot ```
- Após criado o arquivo `.env.test` é necessário configurar os dados de banco de dados neste arquivo. Os dados de banco devem ser os mesmos utilizados pelo senai-services que está sendo utilizado pelo e-commerce.
- Após configurar os dados de banco executar o comando `./qa build`.
- Após concluir o build do docker é possível rodar os testes com o comando `./qa`.

## Subindo servidor de relatório
Para acessar os relatórios do Robot Framework pode ser utilizado o comando `./qa server-start` após executar o comando é possível ver o relatório de testes em `localhost:2600`.
## Estruturas de pastas

- **docker/robot**: Nesse diretório ficam as configurações do docker responsável por executar os testes automatizados.
- **docker/robot/qa**: Arquivo responsável por executar os comandos do docker-compose.
- **docker/robot/bin**: Neste diretório ficam os arquivos necessários para rodar os comandos do robot dentro do container do docker. Caso seja necessário adicionar algum parâmetro fixo na execução dos testes esse arquivo pode ser editado.
- **.env.test**: Neste arquivo ficam presente algumas configurações interessantes:
  - DB_HOST: Host do banco utilizado pelo services onde será executado os comandos de banco de dados.
  - DB_PORT: Porta do banco de dados.
  - DB_NAME: Nome do banco de dados.
  - DB_USER: Usuário do banco dados.
  - DB_PASS: Senha do banco de dados
  - ROBOT_OPTIONS: Parâmetros temporários a ser passado para o Robot Framework.
  - SCREEN_HEIGHT: Referente a altura da tela do teste.
  - SCREEN_WIDTH: Referente a largura da tela do teste.
  - RESOURCES_URL: URL do endereço que será testado.
- **tests/Functional**: Diretório onde se encontram os testes que serão executados pelo robot, ela possui dois subdiretórios `resources`  e  `specs`.
- **tests/Functional/resources**: Neste diretório ficam arquivos acessórios dos testes como as pages, que são arquivos com as definições de cada página, as keywords padrões, bibliotecas python e imagens.
- **specs**: Diretório ondem ficam as suites de testes.