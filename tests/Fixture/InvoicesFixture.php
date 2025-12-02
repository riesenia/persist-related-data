<?php
/**
 * This file is part of riesenia/persist-related-data package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace PersistRelatedData\Test\Fixture;

use Cake\ORM\Table;
use Cake\TestSuite\Fixture\TestFixture;

class InvoicesTable extends Table
{
    public function initialize(array $config): void
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
        $this->belongsTo('Contacts');
    }
}

class InvoicesFixture extends TestFixture
{
    public $fields = [
        'id' => ['type' => 'integer'],
        'a_field' => ['type' => 'string', 'length' => 255, 'null' => true],
        'contact_id' => ['type' => 'integer', 'null' => true],
        'contact_name' => ['type' => 'string', 'length' => 255, 'null' => true],
        'contact_address' => ['type' => 'string', 'length' => 255, 'null' => true],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']]
        ]
    ];

    public $records = [
        [
            'id' => 1,
            'contact_id' => 1,
            'contact_name' => 'First name',
            'contact_address' => 'First address'
        ]
    ];
}
