<?php
namespace PersistRelatedData\Test\Fixture;

use Cake\ORM\Table;
use Cake\TestSuite\Fixture\TestFixture;

class ContactsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        // associations
        $this->hasMany('Invoices');
    }
}

class ContactsFixture extends TestFixture
{

    public $fields = [
        'id' => ['type' => 'integer'],
        'name' => ['type' => 'string', 'default' => null, 'null' => true],
        'address' => ['type' => 'string', 'default' => null, 'null' => true],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']]
        ]
    ];
    public $records = [
        [
            'id'      => 1,
            'name'    => 'First name',
            'address' => 'First address'
        ],
        [
            'id'      => 2,
            'name'    => 'Second name',
            'address' => 'Second address'
        ]
    ];
}
