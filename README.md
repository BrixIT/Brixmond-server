#Masterserver for brixmond

## Installation

```bash
$ git clone https://github.com/BrixIT/Brixmond-server.git
$ cd Brixmond-server
$ composer install
# Answer the questions in the installation wizard
$ bower install
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:update --force
```