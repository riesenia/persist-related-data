<?php
namespace PersistRelatedData\Model\Behavior;

use Cake\Core\Exception\Exception;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;

/**
 * Behavior for persisting selected fields from related table
 *
 * Set fields option as [field => RelatedTable.related_field]
 */
class PersistRelatedDataBehavior extends Behavior
{
    /** @var array */
    protected $_defaultConfig = [
        'fields' => [],
        'changeable' => []
    ];

    /**
     * {@inheritDoc}
     */
    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        $relatedEntities = [];

        foreach ($this->getConfig('fields') as $field => $mapped) {
            list($mappedTable, $mappedField) = explode('.', $mapped);

            if (!isset($this->_table->{$mappedTable}) || $this->_table->{$mappedTable}->isOwningSide($this->_table)) {
                throw new Exception(sprintf('Incorrect definition of related data to persist for %s', $mapped));
            }

            $foreignKeys = $entity->extract((array)$this->_table->{$mappedTable}->getForeignKey());
            $dirtyForeignKeys = $entity->extract((array)$this->_table->{$mappedTable}->getForeignKey(), true);

            if (!empty($dirtyForeignKeys)) {
                // get related entity
                if (empty($relatedEntities[$mappedTable])) {
                    $relatedEntities[$mappedTable] = is_null(array_values($foreignKeys)[0]) ? null : $this->_table->{$mappedTable}->get($foreignKeys);
                }

                // set field value
                if (!is_null($relatedEntities[$mappedTable])) {
                    if (in_array($field, $this->getConfig('changeable'))) {
                        $entity->set($field, $entity->get($field) ? : $relatedEntities[$mappedTable]->get($mappedField));
                    }
                    else {
                        $entity->set($field, $relatedEntities[$mappedTable]->get($mappedField));
                    }
                }
            }
        }
    }
}
