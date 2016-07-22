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
        $relatedEntities = [];

        foreach ($this->config('fields') as $field => $mapped) {
            list($mappedTable, $mappedField) = explode('.', $mapped);

            if (!isset($this->_table->{$mappedTable}) || $this->_table->{$mappedTable}->isOwningSide($this->_table)) {
                throw new Exception(sprintf('Incorrect definition of related data to persist for %s', $mapped));
            }

            $foreignKeys = $entity->extract((array) $this->_table->{$mappedTable}->foreignKey());
            $dirtyForeignKeys = $entity->extract((array) $this->_table->{$mappedTable}->foreignKey(), true);

            if (!empty($dirtyForeignKeys)) {
                // get related entity
                if (empty($relatedEntities[$mappedTable])) {
                    $relatedEntities[$mappedTable] = $this->_table->{$mappedTable}->get($foreign);
                }

                // set field value
                $entity->set($field, $relatedEntities[$mappedTable]->get($mappedField));
            }
        }
    }
}
