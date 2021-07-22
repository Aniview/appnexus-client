<?php

namespace Audiens\AppnexusClient\entity;

use Laminas\Hydrator\ReflectionHydrator;
use Zend\Hydrator\Reflection;

trait HydratableTrait
{
    /**
     * @param array $objectArray
     *
     * @return self
     */
    public static function fromArray(array $objectArray)
    {
        $object = new self();
        self::getHydrator()->hydrate($objectArray, $object);

        return $object;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return self::getHydrator()->extract($this);
    }

    /**
     * @return ReflectionHydrator
     */
    private static function getHydrator()
    {
        return new ReflectionHydrator();
    }
}
