#Masterserver for brixmond

Brixmond is a server monitoring solution focused on monitoring a lot of on-premise servers from different customers running as hypervisor and a series of virtual machines inside that, all running behind a NAT.

The way this is archived is by making the clients on the on-premise servers send their data on intervals to the master server over HTTP instead of making the master server poll the servers over a custom protocol. This makes monitoring a lot of Linux instances behind a NAT possible without any port forwarding and even behind a http proxy.

The clients that need to be installed are using only python 3 (3.4 or higher recommended) and packages available with python-pip.

The masterserver is a PHP application build with symfony 2.6 and requires a mysql server for storage.

![Overview screenshot](http://brixitcdn.net/github/brixmond/overview.png)

![Server details](http://brixitcdn.net/github/brixmond/charts.png)

The code and installation instructions for the client is available in [its own repository](https://github.com/BrixIT/Brixmond)

## Masterserver installation

```bash
$ git clone https://github.com/BrixIT/Brixmond-server.git
$ cd Brixmond-server
$ composer install
# Answer the questions in the installation wizard
$ bower install
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:update --force
```
