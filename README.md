# Requirements

- PHP >= 7.2
- Node.js
- Yarn for front-end dependencies management
- Composer

# Installation instructions

- Clone the project: <code>git clone https://github.com/egarofalo/media-monks-test.git</code>
- Run <code>composer install</code> in the project root folder to install php dependencies
- Run <code>yarn install</code> to install the front-end dependencies
- Run <code>yarn run encore dev</code> to compile assets with Webpack Encore

# Create database tables and load testing data

- Run <code>php bin/console doctrine:database:create</code> to create the SQLite database stored in <code>var/app.db</code>
- Run <code>php bin/console doctrine:migrations:migrate</code> to create the database tables
- Run <code>php bin/console doctrine:fixtures:load</code> to load testing data in the database

# Run the server

- Execute <code>php bin/console server:run</code> to run the Symfony web server

# Admin area

Open in the browser http://127.0.0.1:8000/admin/login and use one of the following users to login to the blog admin area:
- Username: egarofalo , Password: 1234
- Username: admin , Password: 1234

# API endpoint

- To lists the blog post id and title make a GET request to http://127.0.0.1:8000/api/blogs
- To get the details of a prticular post make a GET request to http://127.0.0.1:8000/api/blogs/{id}
- To list registered tags make a GET request to http://127.0.0.1:8000/api/blog/tags
