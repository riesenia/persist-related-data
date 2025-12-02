<?php
/**
 * This file is part of riesenia/persist-related-data package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace PersistRelatedData\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;

/**
 * Behavior for persisting selected fields from related table.
 *
 * Set fields option as [field => RelatedTable.related_field]
 */
class PersistRelatedDataBehavior extends Behavior
{
    /**
     * @var array<string,mixed>
     */
    protected $_defaultConfig = [
        'fields' => [],
        'changeable' => []
    ];

    /**
     * @param \Cake\Event\Event<\Cake\ORM\Table> $event
     * @param \ArrayObject<string,mixed>         $options
     */
    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options): void
    {
        $relatedEntities = [];

        $fields = $this->getConfig('fields');

        if (!\is_array($fields)) {
            return;
        }

        foreach ($fields as $field => $mapped) {
            if (!\is_string($field) || !\is_string($mapped)) {
                continue;
            }

            list($mappedTable, $mappedField) = \explode('.', $mapped);

            if (!isset($this->_table->{$mappedTable}) || $this->_table->{$mappedTable}->isOwningSide($this->_table)) {
                throw new \RuntimeException(\sprintf('Incorrect definition of related data to persist for %s', $mapped));
            }

            $foreignKeys = $entity->extract((array) $this->_table->{$mappedTable}->getForeignKey());
            $dirtyForeignKeys = $entity->extract((array) $this->_table->{$mappedTable}->getForeignKey(), true);

            if (!empty($dirtyForeignKeys)) {
                // get related entity
                if (empty($relatedEntities[$mappedTable])) {
                    $relatedEntities[$mappedTable] = \is_null(\array_values($foreignKeys)[0]) ? null : $this->_table->{$mappedTable}->get($foreignKeys);
                }

                // set field value
                if (!\is_null($relatedEntities[$mappedTable])) {
                    $changeable = $this->getConfig('changeable');

                    if (\is_array($changeable) && \in_array($field, $changeable, true)) {
                        $entity->set($field, $entity->get($field) ?: $relatedEntities[$mappedTable]->get($mappedField));
                    } else {
                        $entity->set($field, $relatedEntities[$mappedTable]->get($mappedField));
                    }
                }
            }
        }
    }
}
