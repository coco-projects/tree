<?php

    declare(strict_types = 1);

    namespace Coco\tree;

    /**
     * 数据库查出的数组记录直接导入，自动生成树状结构
     */
final class DataSource
{
    public array       $data        = [];
    public string|null $idField     = null;
    public array       $dataFields  = [];
    public string|null $parentField = null;
    public int         $rootId      = 0;

    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * @return TreeNode
     */
    public function toTree(): TreeNode
    {
        $rootNode = new TreeNode($this->getRootId());
        $nodes    = $this->makeNodes();

        foreach ($nodes as $k => $node) {
            $node['node']->importData($node['data']);
            if ($node['pid'] == $rootNode->getId()) {
                $rootNode->addChild($node['node']);
            } else {
                $rootNode->addChildRecrusive($node['node'], $node['pid']);
            }
        }

        return $rootNode;
    }

    /**
     * @return iterable
     */
    private function makeNodes(): iterable
    {
        $dataProcessor = $this->getDataFields();
        $data_         = $this->getData();

        usort($data_, function ($a, $b) {
            return ($a[$this->getParentField()] < $b[$this->getParentField()]) ? -1 : 1;
        });

        foreach ($data_ as $k => $dataItem) {
            $node = [
                "node" => new TreeNode($dataItem[$this->getIdField()]),
                "pid"  => $dataItem[$this->getParentField()],
                "data" => (function () use ($dataProcessor, $data_, $dataItem) {

                    $data = [];
                    foreach ($dataProcessor as $dataField => $callback) {
                        if (is_callable($callback)) {
                            $data[$dataField] = call_user_func_array($callback, [
                                $dataItem,
                                $data_,
                                $dataField,
                            ]);
                        } else {
                            $data[$dataField] = $dataItem[$dataField];
                        }
                    }
                    return $data;
                })(),
            ];

            yield $node;
        }
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
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdField(): ?string
    {
        return $this->idField;
    }

    /**
     * @param string $idField
     *
     * @return $this
     */
    public function setIdField(string $idField): self
    {
        $this->idField = $idField;

        return $this;
    }

    /**
     * @return array
     */
    public function getDataFields(): array
    {
        return $this->dataFields;
    }

    /**
     * @param string        $dataField
     * @param callable|null $callback
     *
     * @return $this
     */
    public function setDataFields(string $dataField, callable $callback = null): self
    {
        if (is_callable($callback)) {
            $this->dataFields[$dataField] = $callback;
        } else {
            $this->dataFields[$dataField] = null;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getParentField(): ?string
    {
        return $this->parentField;
    }

    /**
     * @param string $parentField
     *
     * @return $this
     */
    public function setParentField(string $parentField): self
    {
        $this->parentField = $parentField;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRootId(): ?int
    {
        return $this->rootId;
    }

    /**
     * @param int $rootId
     *
     * @return $this
     */
    public function setRootId(int $rootId): self
    {
        $this->rootId = $rootId;

        return $this;
    }
}
