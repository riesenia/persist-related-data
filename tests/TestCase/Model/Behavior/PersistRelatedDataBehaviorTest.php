<?php
/**
 * This file is part of riesenia/persist-related-data package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace PersistRelatedData\Test\TestCase\Model\Behavior;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * PersistRelatedDataBehavior Test Case.
 */
class PersistRelatedDataBehaviorTest extends TestCase
{
    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'plugin.PersistRelatedData.invoices',
        'plugin.PersistRelatedData.contacts'
    ];

    /**
     * Test saving new record.
     */
    public function testNewRecord()
    {
        $invoice = $this->Invoices->newEntity([]);
        $invoice->contact_id = 2;
        $this->Invoices->save($invoice);

        $this->assertEquals('Second name', $invoice->contact_name);
        $this->assertEquals('Second address', $invoice->contact_address);
    }

    /**
     * Test updating record.
     */
    public function testUpdate()
    {
        $contact = $this->Contacts->get(1);
        $contact->name = 'Changed name';
        $this->Contacts->save($contact);

        // no change
        $invoice = $this->Invoices->get(1);
        $invoice->a_field = 'test';
        $this->Invoices->save($invoice);

        $this->assertEquals('First name', $invoice->contact_name);

        // change fields
        $invoice = $this->Invoices->get(1);
        $invoice->contact_id = 2;
        $invoice->a_field = 'test';
        $this->Invoices->save($invoice);

        $this->assertEquals('Second name', $invoice->contact_name);
        $this->assertEquals('Second address', $invoice->contact_address);
    }

    /**
     * Test null.
     */
    public function testNull()
    {
        $invoice = $this->Invoices->newEntity([]);
        $invoice->contact_name = 'Name';
        $this->Invoices->save($invoice);

        $this->assertEquals('Name', $invoice->contact_name);
    }

    /**
     * setUpBeforeClass method - Create fixture tables.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $connection = \Cake\Datasource\ConnectionManager::get('test');

        // Create fixture tables
        $fixtures = [
            new \PersistRelatedData\Test\Fixture\InvoicesFixture(),
            new \PersistRelatedData\Test\Fixture\ContactsFixture()
        ];

        foreach ($fixtures as $fixture) {
            $fixture->create($connection);
        }
    }

    /**
     * setUp method.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->Invoices = TableRegistry::getTableLocator()->get('Invoices', ['className' => 'PersistRelatedData\Test\Fixture\InvoicesTable']);
        $this->Contacts = TableRegistry::getTableLocator()->get('Contacts', ['className' => 'PersistRelatedData\Test\Fixture\ContactsTable']);
    }

    /**
     * tearDown method.
     */
    public function tearDown(): void
    {
        unset($this->Invoices, $this->Contacts);

        parent::tearDown();
    }
}
