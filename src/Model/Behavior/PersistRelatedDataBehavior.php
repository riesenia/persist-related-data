<?php
namespace PersistRelatedData\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Core\Exception\Exception;

/**
 * Behavior for persisting selected fields from related table
 *
 * Set fields option as [field => RelatedTable.related_field]
 */
class PersistRelatedDataBehavior extends Behavior
{
    /**
     * Default options
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => []
    ];

    /**
     * Save also related model data
     *
     * @param \Cake\Event\Event
     * @param \Cake\ORM\Entity;
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        foreach ($this->config('fields') as $field => $mapped) {
            list($mappedTable, $mappedField) = explode('.', $mapped);

            if (!isset($this->_table->{$mappedTable}) || $this->_table->{$mappedTable}->isOwningSide($this->_table)) {
                throw new Exception(sprintf('Incorrect definition of related data to persist for %s', $mapped));
            }

            $foreign = $entity->get($this->_table->{$mappedTable}->foreignKey());

            if (!is_null($foreign)) {
                // get related entity
                $related = $this->_table->{$mappedTable}->get($foreign);

                // set field value
                $entity->set($field, $related->get($mappedField));
            }
        }
    }
}
