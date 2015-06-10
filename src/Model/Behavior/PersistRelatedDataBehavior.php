<?php
namespace PersistRelatedData\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\Event\Event;

/**
 * Behavior for persisting selected fields from related table
 */
class PersistRelatedDataBehavior extends Behavior
{
    /**
     * Default options
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Save also related model data
     *
     * @param \Cake\Event\Event
     * @param \Cake\ORM\Entity;
     * @return void
     */
    public function beforeSave(Event $event, Entity $entity)
    {
        // TODO
    }
}
