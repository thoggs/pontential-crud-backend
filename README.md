# Pontential API

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Descrição

API RESTful desenvolvida com a finalidade de servir como backend para o
projeto [Pontential](https://github.com/thoggs/pontential-crud-frontend)

## Estrutura do Projeto

```
project-root/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   └── DeveloperController.php
│   │   ├── Middleware/
│   │   │   └── ValidateUUID.php
│   │   ├── Requests/
│   │   │   ├── StoreDeveloperRequest.php
│   │   │   └── UpdateDeveloperRequest.php
│   │   ├── Resources/
│   │   │   └── DeveloperResource.php
│   │   ├── Models/
│   │   │   └── DeveloperModel.php
│   │   └── Providers/
│   │       └── AppServiceProvider.php
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── seeders/
│   └── .gitignore
├── public/
├── resources/
├── routes/
│   └── api.php
├── storage/
├── tests/
├── Dockerfile
└── docker-compose.yml
```

## Pré-requisitos

- Ter o [Docker](https://www.docker.com/) instalado
- Ter o [GIT](https://git-scm.com/downloads) instalado

## Inicialização Rápida

Rode o comando em um terminal Linux/macOS ou Prompt de comando do Windows:

```bash
git clone https://github.com/thoggs/pontential-crud-backend.git && cd pontential-crud-backend && docker-compose up -d
```

> O projeto estará disponível em http://localhost:8080/api/developers

## Descrição da API

### Endpoints:

- **GET /api/developers**: retorna uma lista paginada de todos os desenvolvedores registrados. É possível personalizar a
  página e a quantidade de resultados exibidos na lista adicionando os seguintes parâmetros à URL:
    - **page**: número da página a ser exibida.
        - Exemplo: `http://localhost:8080/api/developers?page=2` exibe a segunda página de resultados.

    - **perPage**: quantidade de resultados exibidos por página.
        - Exemplo: `http://localhost:8080/api/developers?perPage=5&page=3` exibe a terceira página com até 5
          desenvolvedores por página.

    - **searchTerm**: termo de pesquisa para filtrar resultados.
        - Será usado um `LIKE` no banco de dados pelo termo informado.
        - Exemplo: `http://localhost:8080/api/developers?searchTerm=John` filtra resultados contendo "John".

    - **sorting**: ordenar por coluna.
        - Formato: `sorting=[{"id":"nomeDaColuna","desc":true|false}]`
            - `id`: Nome da coluna pelo qual você deseja ordenar.
            - `desc`: Direção da ordenação (`false` para ordem crescente e `true` para ordem decrescente).

        - Exemplos:
            - Ordenação por nome em ordem crescente:
              ```
              http://localhost:8080/api/developers?sorting=[{"id":"name","desc":false}]
              ```
            - Ordenação por data de criação em ordem decrescente:
              ```
              http://localhost:8080/api/developers?sorting=[{"id":"createdAt","desc":true}]
              ```
            - Ordenação por `firstName` em ordem crescente:
              ```
              http://localhost:8080/api/developers?page=1&perPage=50&sorting=[{"id":"firstName","desc":false}]
              ```

- **GET /api/developers/{id}**: retorna informações detalhadas sobre um desenvolvedor específico.

- **POST /api/developers**: cria um novo registro de desenvolvedor.

- **PUT /api/developers/{id}**: atualiza as informações de um desenvolvedor existente.

- **DELETE /api/developers/{id}**: exclui um registro de desenvolvedor existente.

## License

Project license [Apache-2.0](https://opensource.org/license/apache-2-0)
