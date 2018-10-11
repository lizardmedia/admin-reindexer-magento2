[![Build Status](https://scrutinizer-ci.com/g/lizardmedia/admin-reindexer-magento2/badges/build.png?b=master)](https://scrutinizer-ci.com/g/lizardmedia/admin-reindexer-magento2/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lizardmedia/admin-reindexer-magento2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lizardmedia/admin-reindexer-magento2/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/lizardmedia/module-admin-reindexer/v/stable)](https://packagist.org/packages/lizardmedia/module-admin-reindexer)
[![License](https://poser.pugx.org/lizardmedia/module-admin-reindexer/license)](https://packagist.org/packages/lizardmedia/module-admin-reindexer)

# Magento2 Admin Indexer #
Module LizardMedia_AdminIndexer adds possibility to reindex indexes from admin, using separte processes in background.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

* Magento 2.2
* PHP 7.1

### Installing

#### Download the module

##### Using composer (suggested)

Simply run

```
composer require lizardmedia/module-admin-reindexer
```

##### Downloading ZIP

Download a ZIP version of the module and unpack it into your project into
```
app/code/LizardMedia/AdminIndexer
```

#### Install the module

Run this command
```
bin/magento module:enable LizardMedia_AdminIndexer
bin/magento setup:upgrade
```

## Usage

#### Admin panel

* reindex chosen indexes from indexes grids as mass actions

## For developers

Indexing is performed in background, using reactPHP child process component

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/lizardmedia/admin-reindexer-magento2/tags).

## Authors

* **Bartosz Kubicki** - *Initial work* - [github](https://github.com/bartek9007)
* **Paweł Papke** - *Initial work* - [github](https://github.com/trimar)

See also the list of [contributors](https://github.com/lizardmedia/admin-reindexer-magento2/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## To do

* add possibility to track indexing progress
* add integration tests