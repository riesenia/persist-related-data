<?php
namespace PersistRelatedData\Test\Fixture;

use Cake\ORM\Table;
use Cake\TestSuite\Fixture\TestFixture;

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
        $this->belongsTo('Contacts');
    }
}

class InvoicesFixture extends TestFixture
{
    public $fields = [
        'id' => ['type' => 'integer'],
        'contact_id' => ['type' => 'integer', 'default' => null, 'null' => true],
        'contact_name' => ['type' => 'string', 'default' => null, 'null' => true],
        'contact_address' => ['type' => 'string', 'default' => null, 'null' => true],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']]
        ]
    ];

    public $records = [
        [
            'id'              => 1,
            'contact_id'      => 1,
            'contact_name'    => 'First name',
            'contact_address' => 'First address'
        ]
    ];
}
