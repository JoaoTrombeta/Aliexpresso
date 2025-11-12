## Aliexpresso - Plataforma E-commerce
sobre o projeto
Aliexpresso é um projeto de e-commerce que simula uma loja virtual de produtos com cafeína. Desenvolvido como um trabalho acadêmico, o sistema inclui funcionalidades essenciais como cadastro de clientes, funcionários e produtos, além de um sistema de login e autenticação. A arquitetura segue o padrão MVC (Model-View-Controller) para organizar o código de forma clara e eficiente.

## 🚀 Tecnologias Utilizadas
O projeto foi construído utilizando as seguintes tecnologias:

Backend: PHP

Frontend: HTML, CSS, JavaScript

Banco de Dados: MySQL

## 📂 Estrutura de Arquivos
O repositório está organizado seguindo a arquitetura MVC:

/

├── 📁 assets
│   ├── 📁 css
│   │   ├── 🎨 admin.css
│   │   ├── 🎨 carrinho.css
│   │   ├── 🎨 dashVendas.css
│   │   ├── 🎨 footer.css
│   │   ├── 🎨 header.css
│   │   ├── 🎨 home.css
│   │   └── 🎨 produtos.css
│   ├── 📁 images
│   │   └── 📁 produtos
│   │       ├── 🖼️ 6858d1cb53dde-Chocolate C amargo.png
│   │       ├── 🖼️ 6858d1d25867c-Chocolate C amargo.png
│   │       ├── 🖼️ 6858d1e2954bf-Chocolate C amargo.png
│   │       ├── 🖼️ 6858d1e57d382-Chocolate C amargo.png
│   │       ├── 🖼️ 6858d2df39579-Chocolate C amargo.png
│   │       ├── 🖼️ 68634f970f302-Chocolate C amargo.png
│   │       ├── 🖼️ 68634fcf4ffeb-monster.jpg
│   │       ├── 🖼️ 686350448fd93-capsula.jpg
│   │       ├── 🖼️ 6863509076b72-3coracoes.jpg
│   │       ├── 🖼️ 68dc7fd8d79fa-coffeeCup.png
│   │       └── 🖼️ 68dc804ab3d71-coffeeCup.png
│   ├── 📁 img
│   │   ├── 📁 produtos
│   │   │   ├── 🖼️ 3coracoes.jpg
│   │   │   ├── 🖼️ Chocolate C amargo.png
│   │   │   ├── 🖼️ Chocolate.png
│   │   │   ├── 🖼️ camargo.png
│   │   │   ├── 🖼️ capsula.jpg
│   │   │   └── 🖼️ monster.jpg
│   │   ├── 🖼️ Facebook.png
│   │   ├── 🖼️ Instagram.png
│   │   ├── 🖼️ Linkedin.png
│   │   ├── 🖼️ Twitter.png
│   │   ├── 🖼️ Youtube.png
│   │   ├── 🖼️ app_store.png
│   │   ├── 🖼️ boleto.png
│   │   ├── 🖼️ elo.png
│   │   ├── 🖼️ g_play.png
│   │   ├── 🖼️ hipercard.png
│   │   ├── 🖼️ icon_site.png
│   │   ├── 🖼️ logo_aliexpresso.png
│   │   ├── 🖼️ logo_aliexpresso2.png
│   │   ├── 🖼️ logo_aliexpresso3.png
│   │   ├── 🖼️ logo_aliexpresso4.png
│   │   ├── 🖼️ logo_apple.png
│   │   ├── 🖼️ mastercard.png
│   │   ├── 🖼️ pix.png
│   │   ├── 🖼️ qr-code.png
│   │   └── 🖼️ visa.png
│   └── 📁 js
│       ├── 📄 carrinho.js
│       ├── 📄 cart-ajax.js
│       ├── 📄 header.js
│       └── 📄 image-preview.js
├── 📁 controller
│   ├── 🐘 AdminController.php
│   ├── 🐘 CarrinhoController.php
│   ├── 🐘 ProdutoController.php
│   ├── 🐘 UsuarioController.php
│   ├── 🐘 carrinho.php
│   ├── 🐘 pageController.php
│   └── 🐘 pedidoController.php
├── 📁 database
│   ├── 📄 aliexpresso(old).sql
│   └── 📄 aliexpresso.sql
├── 📁 helper
│   ├── 📁 export
│   │   ├── 🐘 CSVExportAdapter.php
│   │   ├── 🐘 ExportadorInterface.php
│   │   └── 🐘 GeradorCSV.php
│   └── 🐘 Auth.php
├── 📁 model
│   ├── 📁 produtos
│   │   ├── 🐘 CafeEmGraos.php
│   │   ├── 🐘 CapsulaCafe.php
│   │   ├── 🐘 Doces.php
│   │   ├── 🐘 Energeticos.php
│   │   └── 🐘 ProdutoCafeina.php
│   ├── 🐘 CarrinhoFactory.php
│   ├── 🐘 CupomModel.php
│   ├── 🐘 Database.php
│   ├── 🐘 EnderecoModel.php
│   ├── 🐘 ItemPedidoModel.php
│   ├── 🐘 PedidoModel.php
│   ├── 🐘 ProdutoFactory.php
│   ├── 🐘 ProdutoModel.php
│   └── 🐘 UsuarioModel.php
├── 📁 view
│   ├── 📁 Carrinho
│   │   ├── 🐘 aaa.php
│   │   └── 🐘 index.php
│   ├── 📁 admin
│   │   ├── 🐘 cupom_crud.php
│   │   ├── 🐘 dashboard.php
│   │   ├── 🐘 dashboard_vendas.php
│   │   ├── 🐘 produto_crud.php
│   │   └── 🐘 user_crud.php
│   ├── 📁 home
│   │   └── 🐘 index.php
│   ├── 📁 pedidos
│   │   └── 🐘 historico.php
│   ├── 📁 produtos
│   │   ├── 🐘 form.php
│   │   └── 🐘 index.php
│   └── 📁 usuarios
│       ├── 🐘 Form.php
│       ├── 🐘 Listar.php
│       ├── 🐘 Login.php
│       ├── 🐘 Perfil.php
│       └── 🐘 Register.php
├── ⚙️ .htaccess
├── 📄 LICENSE
├── 📝 README.md
├── 🐘 autoloader.php
├── 🐘 config.php
└── 🐘 index.php
## ⚙️ Instalação e Execução
Para rodar este projeto localmente, siga os passos abaixo:

Pré-requisitos
Um ambiente de servidor web local como XAMPP ou WAMP.

Git (opcional, mas recomendado).

Passos
Clone o repositório:

Bash

git clone https://github.com/JoaoTrombeta/Aliexpresso.git
Caso não tenha Git, você pode baixar o projeto como um arquivo ZIP e extraí-lo.

Mova os arquivos do projeto: Mova a pasta Aliexpresso para o diretório htdocs (no XAMPP) ou www (no WAMP) do seu servidor local.

Configure o Banco de Dados:

Inicie os serviços do Apache e MySQL no seu painel de controle (ex: XAMPP Control Panel).

Acesse o phpMyAdmin através do seu navegador (geralmente em http://localhost/phpmyadmin).

Crie um novo banco de dados.

Importe o arquivo aliexpresso.sql, localizado na raiz do projeto, para criar as tabelas e a estrutura de dados necessárias.

Acesse a aplicação: Abra o seu navegador e acesse http://localhost/Aliexpresso para ver o projeto em funcionamento.

## 👥 Colaboradores
Este projeto foi desenvolvido com a colaboração dos seguintes membros:

João Vitor Trombeta: [JoaoTrombeta](https://github.com/JoaoTrombeta)

Matheus William Rodrigues do Nascimento: [MatheusWilliam](https://github.com/MatheusWilliam0309)
