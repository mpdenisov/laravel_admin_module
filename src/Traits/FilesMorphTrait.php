<?php

namespace Rhinoda\Admin\Traits;

/**
 * Trait to boot files polymorphic relation to model.
 *
 * Trait FilesMorphTrait
 * @package App\Traits
 */

trait FilesMorphTrait
{
    /**
     * Entity type name to map polymorphic relation.
     * @var static
     */
    protected $entity_type;

    /**
     * File polymorphic relation.
     */
    public function files()
    {
        return $this->morphMany('App\Models\File', 'entity');
    }

    /**
     *  Gets entity type attribute.
     *
     * @return mixed
     */
    public function getEntityTypeAttribute()
    {
        return $this->entity_type;
    }

    /**
     * Sets entity type.
     *
     * @param $entity_type
     */
    public function setEntityTypeAttribute($entity_type)
    {
        $this->attributes['entity_type'] = $entity_type;
    }
}