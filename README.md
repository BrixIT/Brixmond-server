#Masterserver for brixmond

Brixmond is a server monitoring solution focused on monitoring a lot of on-premise servers from different customers running as hypervisor and a series of virtual machines inside that, all running behind a NAT.

The way this is archived is by making the clients on the on-premise servers send their data on intervals to the master server over HTTP instead of making the master server poll the servers over a custom protocol. This makes monitoring a lot of Linux instances behind a NAT possible without any port forwarding and even behind a http proxy.

The clients that need to be installed are using only python 3 (3.4 or higher recommended) and packages available with python-pip.

The masterserver is a PHP application build with symfony 2.6 and requires a mysql server for storage.

The code and installation instructions for the client is available in [its own repository](https://github.com/BrixIT/Brixmond)

## Features

- Monitors load, cpu usage, memory usage, swap, various network stats and disk usage
- Monitors webservers (only apache for now)
- Uses audit information from lynis
- Notifies admins with pushover.net or email
- Translated in english and dutch
- User configurable "watches" used to generate notifications for admins

To be implemented:
- Support for nginx in webserver monitoring
- Support for varnish monitoring
- Support for logging systemd units (notify on failed units)
- Add failover wan in network graph
- Ping edge routers to check for internet provider failure

## Screenshots

<a href="http://brixitcdn.net/github/brixmond/overview.png">
    <img src="http://brixitcdn.net/github/brixmond/overview.png" width="200">
</a>
<a href="http://brixitcdn.net/github/brixmond/messages.png">
    <img src="http://brixitcdn.net/github/brixmond/messages.png" width="200">
</a>
<a href="http://brixitcdn.net/github/brixmond/charts.png">
    <img src="http://brixitcdn.net/github/brixmond/charts.png" width="200">
</a>
<br>
<a href="http://brixitcdn.net/github/brixmond/audits.png">
    <img src="http://brixitcdn.net/github/brixmond/audits.png" width="200">
</a>
<a href="http://brixitcdn.net/github/brixmond/watch.png">
    <img src="http://brixitcdn.net/github/brixmond/watch.png" width="200">
</a>

## Masterserver installation

```bash
# Clone the git repository
$ git clone https://github.com/BrixIT/Brixmond-server.git
$ cd Brixmond-server
# Install the composer dependencies
$ composer install
# Answer the questions in the installation wizard
$ bower install
# Create and fill the database
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:update --force
# Create the admin user to log in on the webinterface
$ php app/console fos:user:create --super-admin
```
