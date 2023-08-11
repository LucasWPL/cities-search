# Cities search

Este projeto implementa uma API para pesquisa e listagem de municípios de uma unidade federativa (UF). A API utiliza uma fonte de dados externa para obter os municípios, garantindo informações precisas e atualizadas, e oferece um mecanismo de fallback para casos de indisponibilidade da fonte principal.
## Requisitos

- [Docker](https://docs.docker.com/engine/install/ubuntu/)
- [Composer](https://getcomposer.org/download/)

## Tecnologias Utilizadas

- Laravel: Framework PHP utilizado para desenvolver a API.
- Guzzle: Biblioteca para fazer requisições HTTP para as APIs externas.
- Cache (redis): Uso de caching para melhorar a performance e reduzir chamadas repetidas às APIs externas.
- PHPUnit: Para escrever testes unitários.
- Docker: Containerização do projeto para facilitar o ambiente de desenvolvimento e implantação.

## Conceitos Aplicados

- Paginação: O projeto implementa a paginação dos resultados por meio da inclusão dos parâmetros `per_page` e `page` nas requisições.
- Tratamento de Exceções: Exceções são tratadas para garantir respostas adequadas ao cliente. Se uma UF inválida for fornecida ou ocorrer um erro na chamada às APIs, a API retornará mensagens de erro apropriadas e códigos de status HTTP correspondentes.
- Commits Atômicos e Descritivos: O desenvolvimento deste projeto segue a prática de commits atômicos e descritivos, mantendo um histórico claro das mudanças realizadas.
## Instalação

1. Clone o repositório: `git clone https://github.com/LucasWPL/cities-search.git`
2. Inicie os containers: `make up`

    Nota: Talvez esse comando demore um pouco, a imagem base dele não é optimizada. Aguarde.

3. Acesse o container e instale as dependências:`make bash` e `composer install`
4. Crie o arquivo `.env` a partir do `.env.example` e configure as variáveis de ambiente.
5. Teste a rota principal por meio do Postman ou navegador.

## Rotas

O projeto implementa a seguinte rota para a API:

- `GET /api/uf/{uf}/listar-municipios`: Retorna uma lista de municípios da unidade federativa (UF) especificada. Esta rota suporta os seguintes parâmetros opcionais:
  - `per_page`: Define a quantidade de itens por página para a paginação dos resultados.
  - `page`: Define a página atual para a paginação dos resultados.

Exemplo de uso:

```
GET /api/uf/RJ/listar-municipios
GET /api/uf/RJ/listar-municipios?per_page=20&page=2
```

A inclusão dos parâmetros `per_page` e `page` permite a navegação fácil pelos resultados, proporcionando uma experiência amigável ao usuário final.

Além disso, o projeto está preparado para adicionar mais rotas no futuro, conforme a necessidade de expansão.

## Testes

Execute os testes unitários e de integração:

```bash
php artisan test
```

## Implantação
Este projeto pode ser facilmente implantado em serviços de hospedagem compatíveis com Laravel, como Heroku ou AWS Elastic Beanstalk. A configuração de ambiente e a configuração do servidor da web podem variar de acordo com o provedor escolhido.

## Licença
Este projeto está licenciado sob a [Licença MIT](https://www.mit.edu/~amini/LICENSE.md).