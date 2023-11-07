<?php

    declare(strict_types = 1);

    namespace Coco\tree;

use Coco\magicAccess\MagicArrayTrait;

class DataItem implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use MagicArrayTrait;

    public function __construct(array $data = [])
    {
        $this->importData($data);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function importData(array $data): static
    {
        foreach ($data as $k => $v) {
            $this->setField($k, $v);
        }

        return $this;
    }

    /**
     * @param string|int $offset
     *
     * @return bool
     */
    public function hasField(string|int $offset): bool
    {
        return $this->offsetExists($offset);
    }

    /**
     * @param string|int $offset
     * @param mixed      $value
     *
     * @return $this
     */
    public function setField(string|int $offset, mixed $value): static
    {
        $this->offsetSet($offset, $value);

        return $this;
    }

    /**
     * @param string|int $offset
     *
     * @return $this
     */
    public function removeField(string|int $offset): static
    {
        $this->offsetUnset($offset);

        return $this;
    }

    /**
     * @param string|int $offset
     *
     * @return mixed
     */
    public function fetchField(string|int $offset): mixed
    {
        $value = $this->getField($offset);
        $this->removeField($offset);

        return $value;
    }

    /**
     * @param string|int $offset
     *
     * @return mixed
     */
    public function &getField(string|int $offset): mixed
    {
        return $this->offsetGet($offset);
    }


    public function searchByField(string|int $field, callable $callback): bool
    {
        $value = $this->getField($field);

        return call_user_func_array($callback, [$value]);
    }

    public function isEquals(string|int $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $val == $value;
        });
    }

    public function isStrictEqual(string|int $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $val === $value;
        });
    }


    public function isGreaterThan(string|int $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $value > $val;
        });
    }

    public function isGreaterThanOrEqualTo(string|int $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $value >= $val;
        });
    }

    public function isLessThan(string|int $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $value < $val;
        });
    }

    public function isLessThanOrEqualTo(string|int $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $value <= $val;
        });
    }

    public function isArray(string|int $field): bool
    {
        return $this->searchByField($field, function ($value) {
            return is_array($value);
        });
    }

    public function isStartWith(string|int $field, string $prefix): bool
    {
        return $this->searchByField($field, function ($value) use ($prefix) {
            if (!is_string($value)) {
                return false;
            }
            return str_starts_with($value, $prefix);
        });
    }

    public function isEndWith(string|int $field, string $suffix): bool
    {
        return $this->searchByField($field, function ($value) use ($suffix) {
            if (!is_string($value)) {
                return false;
            }
            return str_ends_with($value, $suffix);
        });
    }

    public function isContainsWith(string|int $field, string $substring): bool
    {
        return $this->searchByField($field, function ($value) use ($substring) {
            if (!is_string($value)) {
                return false;
            }
            return str_contains($value, $substring);
        });
    }
}
