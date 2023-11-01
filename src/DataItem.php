<?php

    declare(strict_types = 1);

    namespace Coco\tree;

class DataItem implements \ArrayAccess, \Countable, \IteratorAggregate
{
    public array $data = [];

    public function __construct(array $data = [])
    {
        $this->importData($data);
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function eachField(callable $callback): static
    {
        foreach ($this as $k => &$v) {
            $callback($v, $k);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
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
     * @param string $key
     *
     * @return bool
     */
    public function hasField(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setField(string $key, mixed $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function removeField(string $key): static
    {
        unset($this->data[$key]);

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function fetchField(string $key): mixed
    {
        $value = $this->getField($key);
        $this->removeField($key);

        return $value;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getField(string $key): mixed
    {
        if ($this->hasField($key)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->hasField($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        return $this->getField($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->setField($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {

        $this->removeField($offset);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->getData());
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->getData());
    }

    public function searchByField(string $field, callable $callback): bool
    {
        $value = $this->getField($field);

        return call_user_func_array($callback, [$value]);
    }

    public function isEquals(string $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $val == $value;
        });
    }

    public function isStrictEqual(string $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $val === $value;
        });
    }


    public function isGreaterThan(string $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $value > $val;
        });
    }

    public function isGreaterThanOrEqualTo(string $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $value >= $val;
        });
    }

    public function isLessThan(string $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $value < $val;
        });
    }

    public function isLessThanOrEqualTo(string $field, mixed $val): bool
    {
        return $this->searchByField($field, function ($value) use ($val) {
            return $value <= $val;
        });
    }

    public function isArray(string $field): bool
    {
        return $this->searchByField($field, function ($value) {
            return is_array($value);
        });
    }

    public function isStartWith(string $field, string $prefix): bool
    {
        return $this->searchByField($field, function ($value) use ($prefix) {
            if (!is_string($value)) {
                return false;
            }
            return str_starts_with($value, $prefix);
        });
    }

    public function isEndWith(string $field, string $suffix): bool
    {
        return $this->searchByField($field, function ($value) use ($suffix) {
            if (!is_string($value)) {
                return false;
            }
            return str_ends_with($value, $suffix);
        });
    }

    public function isContainsWith(string $field, string $substring): bool
    {
        return $this->searchByField($field, function ($value) use ($substring) {
            if (!is_string($value)) {
                return false;
            }
            return str_contains($value, $substring);
        });
    }


    public function destroy(): void
    {
        $this->data = [];
    }
}
