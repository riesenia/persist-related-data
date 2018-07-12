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
     * Save also related model data.
     *
     * @param Event           $event
     * @param EntityInterface $entity
     */
    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        $relatedEntities = [];

        foreach ($this->getConfig('fields') as $field => $mapped) {
            list($mappedTable, $mappedField) = explode('.', $mapped);

            if (!isset($this->_table->{$mappedTable}) || $this->_table->{$mappedTable}->isOwningSide($this->_table)) {
                throw new Exception(sprintf('Incorrect definition of related data to persist for %s', $mapped));
            }

            $foreignKeys = $entity->extract((array) $this->_table->{$mappedTable}->getForeignKey());
            $dirtyForeignKeys = $entity->extract((array) $this->_table->{$mappedTable}->getForeignKey(), true);

            if (!empty($dirtyForeignKeys)) {
                // get related entity
                if (empty($relatedEntities[$mappedTable])) {
                    $relatedEntities[$mappedTable] = is_null(array_values($foreignKeys)[0]) ? null : $this->_table->{$mappedTable}->get($foreignKeys);
                }

                // set field value
                if (!is_null($relatedEntities[$mappedTable])) {
                    $entity->set($field, $relatedEntities[$mappedTable]->get($mappedField));
                }
            }
        }
    }
}
