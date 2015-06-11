# PersistRelatedData behavior for CakePHP

[![Build Status](https://img.shields.io/travis/riesenia/persist-related-data/master.svg?style=flat-square)](https://travis-ci.org/riesenia/persist-related-data)
[![Latest Version](https://img.shields.io/packagist/v/riesenia/persist-related-data.svg?style=flat-square)](https://packagist.org/packages/riesenia/persist-related-data)
[![Total Downloads](https://img.shields.io/packagist/dt/riesenia/persist-related-data.svg?style=flat-square)](https://packagist.org/packages/riesenia/persist-related-data)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This plugin is for CakePHP 3.x and contains behavior that handles saving selected fields
of related data (redundantly).

## Installation

Update *composer.json* file to include this plugin

```json
{
    "require": {
        "riesenia/persist-related-data": "~1.0"
    }
}
```

Load plugin in *config/bootstrap.php*

```php
Plugin::load('PersistRelatedData');
```

## Usage

Good example for using this behavior is Invoices model that is related to Contacts. You
can provide select box with contacts and save only *contact_id* when creating new invoice.
But when contact data are modified later, your invoice should be left intact.

Example below assumes the *invoices* table has fields *contact_id*, *contact_name* and
*contact_address*, while the *contacts* table has fields *name* and *address*. When you
save Invoice entity with provided *contact_id*, fields *contact_name* and *contact_address*
will be filled automatically.

```php
class InvoicesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        // add PersistRelatedData behavior
        $this->addBehavior('PersistRelatedData.PersistRelatedData', [
            'fields' => [
                'contact_name' => 'Contacts.name',
                'contact_address' => 'Contacts.address'
            ]
        ]);

        // associations
        $this->belongsTo('Contacts', [
            'foreignKey' => 'contact_id',
            'className' => 'Contacts'
        ]);
    }
}

```
