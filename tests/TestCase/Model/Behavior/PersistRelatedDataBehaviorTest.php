<?php
namespace PersistRelatedData\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * PersistRelatedDataBehavior Test Case
 */
class PersistRelatedDataBehaviorTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.PersistRelatedData.invoices',
        'plugin.PersistRelatedData.contacts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Invoices = TableRegistry::get('Invoices', ['className' => 'PersistRelatedData\Test\Fixture\InvoicesTable']);
        $this->Contacts = TableRegistry::get('Contacts', ['className' => 'PersistRelatedData\Test\Fixture\ContactsTable']);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Invoices);
        unset($this->Contacts);

        parent::tearDown();
    }

    /**
     * Test saving new record
     *
     * @return void
     */
    public function testNewRecord()
    {
        $invoice = $this->Invoices->newEntity();
        $invoice->contact_id = 2;
        $this->Invoices->save($invoice);

        $this->assertEquals('Second name', $invoice->contact_name);
        $this->assertEquals('Second address', $invoice->contact_address);
    }

    /**
     * Test updating record
     *
     * @return void
     */
    public function testUpdate()
    {
        $contact = $this->Contacts->get(1);
        $contact->name = 'Changed name';
        $this->Contacts->save($contact);

        // no change
        $invoice = $this->Invoices->get(1);
        $this->Invoices->save($invoice);

        $this->assertEquals('First name', $invoice->contact_name);

        // change fields
        $invoice->contact_id = 2;
        $this->Invoices->save($invoice);

        $this->assertEquals('Second name', $invoice->contact_name);
        $this->assertEquals('Second address', $invoice->contact_address);
    }

    /**
     * Test null
     *
     * @return void
     */
    public function testNull()
    {
        $invoice = $this->Invoices->newEntity();
        $invoice->contact_name = 'Name';
        $this->Invoices->save($invoice);

        $this->assertEquals('Name', $invoice->contact_name);
    }
}
