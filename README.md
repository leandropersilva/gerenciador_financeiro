# Gerenciador Financeiro

Um gerenciador financeiro é uma ferramenta que ajuda a gerenciar relatórios financeiros personalizados com base em dados reais. Ele pode ser usado para criar relatórios de despesa, orçamentos, planos de negócios, entre outros.

---

## Requisitos

*   PHP 8.1+
*   Node.js 18+
*   Um servidor web (Apache, Nginx)

---

## Instalação e Configuração

1.  Clone o repositório:
    ```
    git clone [URL_DO_SEU_REPOSITORIO]
    ```
2.  Instale as dependências do Node.js:
    ```
    npm install
    ```
3.  Configure suas variáveis de ambiente. Renomeie o arquivo `.env.example` para `.env` e ajuste as configurações do banco de dados.

---

## Como Executar

1.  Aponte a raiz do seu servidor web para o diretório `/` do projeto.
2.  Acesse `http://localhost` (ou o endereço configurado) em seu navegador.

---

## Arquitetura da Aplicação

A aplicação utiliza uma arquitetura baseada no padrão **Model-View-Controller (MVC)**. O objetivo é promover uma clara separação de responsabilidades entre a lógica de negócio (Model), a interface do usuário (View) e o controle da aplicação (Controller).

#### **Estrutura de Roteamento**

O roteamento é baseado na URL, seguindo um formato padronizado e intuitivo:

`https://dominio.com/controller/metodo`

*   `controller`: Mapeia para a classe do **Controller** que irá tratar a requisição.
*   `metodo`: Mapeia para o **método** dentro da classe do Controller que será executado.

#### **Componentes Principais**

**1. Controller**

O Controller atua como o ponto de entrada e orquestrador para cada requisição.

*   **Responsabilidade**: Receber a requisição do usuário, interagir com o Model para buscar ou manipular dados e, por fim, carregar a View correspondente para exibir o resultado.
*   **Implementação**: Todo controller deve herdar da classe abstrata `Controller`.
*   **Carregamento**: Utiliza o método `loadMV()` para carregar o Model e a View associados àquele endpoint.

**2. View**

A View é responsável exclusivamente pela camada de apresentação.

*   **Responsabilidade**: Exibir os dados fornecidos pelo Controller em formato HTML. Ela não deve conter lógica de negócio.
*   **Implementação**: É um arquivo que mescla HTML com os dados passados pelo Controller.

**3. Model**

O Model representa a camada de dados e a lógica de negócio da aplicação.

*   **Responsabilidade**: Interagir com o banco de dados e aplicar as regras de negócio.
*   **Implementação**: Todo model deve herdar da classe abstrata `Model` e implementar seu contrato.

#### **Recursos de Frontend (Assets)**

*   **Localização**: Todos os arquivos estáticos (CSS, JS, imagens) estão no diretório `/public`.
*   **Módulos JavaScript**: Os módulos são centralizados e importados através do arquivo principal `main.js`.

#### **Convenção de Nomenclatura**

A regra é simples: **o nome de um método em um Controller determina os nomes dos arquivos correspondentes para o Model, a View e o módulo JavaScript.**

**Exemplo Prático: Perfil de Usuário**

1.  **URL**: `https://dominio.com/usuario/perfil`
2.  **Controller**: O sistema invocará o método `perfil()` dentro da classe `Usuario`.
3.  **Ação**: A chamada `loadMV()` irá carregar automaticamente:
    *   **Model**: `Perfil.php`
    *   **View**: `perfil.php`
    *   **Script**: O script associado será `perfil.js`.