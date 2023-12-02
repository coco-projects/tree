<?php

    declare(strict_types = 1);

    namespace Coco\tree;

    use LogicException;
    use function DeepCopy\deep_copy;

class TreeNode extends DataItem
{
    const SORT_ORDER_ASC  = 'asc';
    const SORT_ORDER_DESC = 'desc';

    protected int|null $id = null;

    protected ?TreeNode $parentNode = null;

    /**
     * @var TreeNode[] $childs
     */
    protected array $childsNode = [];

    public function __construct(int $id, ?TreeNode $parentNode = null, array $data = [])
    {
        parent::__construct($data);
        $this->setId($id);
        $this->childsNode = [];
        ($parentNode instanceof TreeNode) and $this->appendTo($parentNode);
    }

    public static function makeNode(int $id, ?TreeNode $parentNode = null, array $data = []): static
    {
        return new static($id, $parentNode, $data);
    }

    /********************************************************************
     ********************************************************************
     *    基础操作
     ********************************************************************
     *******************************************************************/

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return $this|null
     */
    public function getParentNode(): ?TreeNode
    {
        return $this->parentNode;
    }

    /**
     * @param TreeNode|null $parentNode
     *
     * @return $this
     */
    public function setParentNode(?TreeNode $parentNode): static
    {
        $this->parentNode = $parentNode;

        return $this;
    }

    /**
     * @return $this[]
     */
    public function getChildsNode(): array
    {
        return $this->childsNode;
    }

    /**
     * @param array $childsNode
     *
     * @return void
     */
    public function setChildsNode(array $childsNode): void
    {
        $this->childsNode = $childsNode;
    }


    /********************************************************************
     ********************************************************************
     *    parent 节点操作
     ********************************************************************
     *******************************************************************/

    /**
     * 判断当前节点是否形成死循环
     *
     * @param TreeNode $tree
     *
     * @return bool
     */
    public static function isCatenary(TreeNode $tree): bool
    {
        $isCatenary = false;
        $currentId  = $tree->getId();

        $tree->eachParents(function (TreeNode $parentNode) use ($currentId, &$isCatenary) {
            if ($parentNode->getId() == $currentId) {
                $isCatenary = true;
                return false;
            }
        });

        return $isCatenary;
    }

    /**
     * 遍历所有父级
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function eachParents(callable $callable): static
    {
        while ($parentNode = $this->getParentNode()) {
            if ($callable($parentNode) === false) {
                break;
            }
            return $parentNode->eachParents($callable);
        }

        return $this;
    }


    /**
     * 获取当前元素所有父级到一个数组里
     *
     * @return array|null
     */
    public function getParentsNode(): ?array
    {
        if (!$this->isTopNode()) {
            $parentsNode = [];
            $this->eachParents(function (TreeNode $parentNode) use (&$parentsNode) {
                $parentsNode[] = $parentNode;
            });

            return $parentsNode;
        }

        return null;
    }

    /**
     * 获取当前元素的最上级根节点
     *
     * @return $this|null
     */
    public function getTopParent(): ?TreeNode
    {
        $parentsNode = $this->getParentsNode();

        return is_array($parentsNode) ? array_pop($parentsNode) : null;
    }


    /********************************************************************
     ********************************************************************
     *    child 节点操作
     ********************************************************************
     *******************************************************************/

    /**
     * 根据节点id，递归查找当前元素子级是否包含此对象，有就返回对象，没有此对象就返回 null
     *
     * @param int $childId
     *
     * @return $this|null
     */
    public function getChildRecrusive(int $childId): ?TreeNode
    {
        $childNode_ = null;
        $this->eachChildsDFS(function (TreeNode $childNode) use (&$childNode_, $childId) {
            if ($childNode->getId() == $childId) {
                $childNode_ = $childNode;
            }
        });

        return $childNode_;
    }

    /**
     * 根据节点id，递归查找，删除子元素
     *
     * @param int $childId
     *
     * @return $this
     */
    public function removeChildRecrusive(int $childId): static
    {
        $this->eachChildsDFS(function (TreeNode $childNode) use (&$childId) {
            if (($childNode->getId() == $childId) && $childNode->getParentNode()) {
                $childNode->getParentNode()->removeChild($childNode->getId());
            }
        });

        return $this;
    }


    /**
     * 删除直属子元素
     *
     * @param int $childId
     *
     * @return $this
     */
    public function removeChild(int $childId): static
    {
        if (isset($this->childsNode[$childId])) {
            $this->childsNode[$childId]->setParentNode(null);

            unset($this->childsNode[$childId]);
        }

        return $this;
    }

    /**
     * @param int $toMoveNodeId
     * @param int $newParentNodeId
     *
     * @return $this
     */
    public function moveNodeToNewParent(int $toMoveNodeId, int $newParentNodeId): static
    {
        $toMoveNode = null;
        $this->eachChildsDFS(function (TreeNode $childNode) use (&$toMoveNode, $toMoveNodeId) {
            if ($childNode->getId() == $toMoveNodeId) {
                $toMoveNode = $childNode;
            }
        });

        $newParentNode = null;
        $this->eachChildsDFS(function (TreeNode $childNode) use (&$newParentNode, $newParentNodeId) {
            if ($childNode->getId() == $newParentNodeId) {
                $newParentNode = $childNode;
            }
        });

        if (is_null($toMoveNode)) {
            throw new LogicException('The node with ID ' . $toMoveNodeId . ' does not exist.');
        }

        if (is_null($newParentNode)) {
            throw new LogicException('The node with ID ' . $newParentNodeId . ' does not exist.');
        }

        $toMoveNode->appendTo($newParentNode);

        return $this;
    }

    /**
     * 递归查找，添加子元素到指定元素的子节点
     *
     * @param TreeNode $childNodeToAdd
     * @param int      $toChildNodeId
     *
     * @return $this
     */
    public function addChildRecrusive(TreeNode $childNodeToAdd, int $toChildNodeId): static
    {
        if (($obj = $this->getChildRecrusive($toChildNodeId)) instanceof static) {
            $obj->addChild($childNodeToAdd);
        }

        return $this;
    }


    /**
     * 子级转数组
     *
     * @return array
     */
    public function getChildArray(): array
    {
        return $this->childsNode;
    }

    /**
     * 根据节点id，递归查找当前元素子级是否包含此对象，返回 bool
     *
     * @param int $childId
     *
     * @return bool
     */
    public function isChildExistsRecrusive(int $childId): bool
    {
        return (bool)$this->getChildRecrusive($childId);
    }


    /**
     * 根据节点id，查找当前元素子级是否包含此对象，返回 bool
     *
     * @param int $childId
     *
     * @return bool
     */
    public function isChildExists(int $childId): bool
    {
        $childsArray = $this->getChildArray();

        foreach ($childsArray as $k => $childNode) {
            if ($childId === $childNode->getId()) {
                return true;
            }
        }

        return false;
    }


    /**
     * 当前节点是否有子级
     *
     * @return bool
     */
    public function hasChild(): bool
    {
        return $this->countChilds() !== 0;
    }

    /**
     * 添加元素到直接子级,如果此元素已经有父级,将强制添加
     *
     * @param TreeNode $childNode
     *
     * @return $this
     */
    public function addChild(TreeNode $childNode): static
    {
        //要找爹
        //如果之前有爹，先把之前爹的孩子列表中的他删除
        if ($childNode->getParentNode() instanceof static) {
            $childNode->getParentNode()->removeChild($childNode->getId());
        }

        //他也跟爹断绝关系，认新爹
        $childNode->setParentNode($this);

        //新爹认这个孩子
        $this->childsNode[$childNode->getId()] = $childNode;

        return $this;
    }


    /**
     * 计算当前元素有几个直接子级
     *
     * @return int
     */
    public function countChilds(): int
    {
        return count($this->childsNode);
    }


    /**
     * 遍历所有直属子级
     *
     * @param callable $childProcessor
     *
     * @return $this
     */
    public function eachChilds(callable $childProcessor): static
    {
        $childsArray = $this->getChildArray();

        foreach ($childsArray as $k => $childNode) {
            $res = call_user_func_array($childProcessor, [$childNode]);

            if (false === $res) {
                break;
            }
        }

        return $this;
    }


    /**
     * 深度优先遍历所有节点，包括自己
     *
     * @param callable $childProcessor
     *
     * @return $this
     */
    public function eachAllDFS(callable $childProcessor): static
    {
        call_user_func_array($childProcessor, [$this]);
        $childsArray = $this->getChildArray();
        foreach ($childsArray as $k => $childNode) {
            $childNode->eachAllDFS($childProcessor);
        }

        return $this;
    }

    /**
     * 广度优先遍历所有子级，包括自己
     *
     * @param callable $childProcessor
     *
     * @return $this
     */
    public function eachAllBFS(callable $childProcessor): static
    {
        static $stackArray = null;

        $stackArray = $this->getChildArray();
        call_user_func_array($childProcessor, [$this]);

        while ($childNode = array_shift($stackArray)) {
            call_user_func_array($childProcessor, [$childNode]);
            $stackArray = array_merge($stackArray, $childNode->getChildArray());
        }

        return $this;
    }

    /**
     * 深度优先遍历所有子级，不包括自己
     *
     * @param callable $childProcessor
     *
     * @return $this
     */
    public function eachChildsDFS(callable $childProcessor): static
    {
        $childsArray = $this->getChildArray();

        foreach ($childsArray as $k => $childNode) {
            call_user_func_array($childProcessor, [$childNode]);

            $childNode->eachChildsDFS($childProcessor);
        }

        return $this;
    }

    /**
     * 广度优先遍历所有子级，不包括自己
     *
     * @param callable $childProcessor
     *
     * @return $this
     */
    public function eachChildsBFS(callable $childProcessor): static
    {
        static $stackArray = null;

        $stackArray = $this->getChildArray();

        while ($childNode = array_shift($stackArray)) {
            call_user_func_array($childProcessor, [$childNode]);

            $stackArray = array_merge($stackArray, $childNode->getChildArray());
        }

        return $this;
    }

    /**
     * 递归计算当前元素有几个子级元素
     *
     * @return int
     */
    public function countChildsRecrusive(): int
    {
        $total = 0;
        $this->eachChildsDFS(function (TreeNode $childNode) use (&$total) {
            $total++;
        });

        return $total;
    }


    /********************************************************************
     ********************************************************************
     *    其他
     ********************************************************************
     *******************************************************************/

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * 自己是否为顶级元素
     *
     * @return bool
     */
    public function isTopNode(): bool
    {
        return !($this->getParentNode() instanceof static);
    }


    /**
     * 获取同级元素，包括自己
     *
     * @return array|null
     */
    public function getSiblingsAndSelf(): ?array
    {
        if (!$this->isTopNode()) {
            $parentNode    = $this->getParentNode();
            $siblingsArray = $parentNode->getChildArray();

            return $siblingsArray;
        }

        return null;
    }


    /**
     * 获取同级元素，不包括自己
     *
     * @return array|null
     */
    public function getSiblings(): ?array
    {
        if (!$this->isTopNode()) {
            $parentNode    = $this->getParentNode();
            $siblingsArray = $parentNode->getChildArray();

            $t = [];

            foreach ($siblingsArray as $k => $v) {
                if ($v->getId() !== $this->getId()) {
                    $t[$v->getId()] = $v;
                }
            }

            return $t;
        }

        return null;
    }


    /**
     * 将自己添加到指定父级
     *
     * @param TreeNode $parentNode
     *
     * @return $this
     */
    public function appendTo(TreeNode $parentNode): static
    {
        $parentNode->addChild($this);

        return $this;
    }


    /**
     * @return static
     */
    public function getCopy(): static
    {
        return deep_copy($this);
    }

    /**
     * 计算当前节点相对根在第几级
     *
     * @return int
     */
    public function getLevel(): int
    {
        $level = 0;
        $this->eachParents(function () use (&$level) {
            $level++;
        });

        return $level;
    }


    /**
     * 返回当前对象及所有子元素递归转换为数组, 和 formArray 方法对应
     *
     * @param bool $whitIdIndex
     *
     * @return array
     */
    public function toArrayAll(bool $whitIdIndex = true): array
    {
        return [
            'id'       => $this->getId(),
            'level'    => $this->getLevel(),
            'parentId' => $this->getParentNode()?->getId(),
            'data'     => $this->getData(),
            'childs'   => (function () use ($whitIdIndex) {
                $childs = [];
                $this->eachChilds(function (TreeNode $childNode) use (&$childs, $whitIdIndex) {

                    if ($whitIdIndex) {
                        $childs[$childNode->getId()] = $childNode->toArrayAll($whitIdIndex);
                    } else {
                        $childs[] = $childNode->toArrayAll($whitIdIndex);
                    }
                });

                return $childs;
            })(),
        ];
    }

    /**
     * 返回当前对象及所有下级元素转为数组，不递归
     *
     * @param bool $whitIdIndex
     *
     * @return array
     */
    public function toArraySelfAndChilds(bool $whitIdIndex = true): array
    {
        return [
            'id'       => $this->getId(),
            'level'    => $this->getLevel(),
            'parentId' => $this->getParentNode()?->getId(),
            'data'     => $this->getData(),
            'childs'   => (function () use ($whitIdIndex) {
                $childs = [];
                $this->eachChilds(function (TreeNode $childNode) use (&$childs, $whitIdIndex) {

                    $d = [
                        'id'       => $childNode->getId(),
                        'level'    => $childNode->getLevel(),
                        'parentId' => $childNode->getParentNode()?->getId(),
                        'data'     => $childNode->getData(),
                        "childs"   => [],
                    ];

                    if ($whitIdIndex) {
                        $childs[$childNode->getId()] = $d;
                    } else {
                        $childs[] = $d;
                    }
                });

                return $childs;
            })(),
        ];
    }


    /**
     * 序列化为 json, 和 formJson 方法对应
     *
     * @param bool $whitIdIndex
     *
     * @return string
     */
    public function toJson(bool $whitIdIndex = true): string
    {
        $res = json_encode($this->toArrayAll($whitIdIndex));
        if (!is_string($res)) {
            throw new LogicException(json_last_error_msg());
        }
        return $res;
    }

    public function sort(string $field, string $order = 'asc'): static
    {
        $sortCallback = static::sortByDataField($field, $order);

        $childsArray = $this->getChildArray();

        $this->setChildsNode($sortCallback($childsArray));

        foreach ($childsArray as $k => $childNode) {
            $childNode->sort($field, $order);
        }

        return $this;
    }


    /**
     * 过滤器，过滤函数返回 false 就忽略指定元素，否则保留指定元素，返回还是树状结构，被忽略的元素，子节点都会被舍弃，返回此对象的副本，不会对此对象本身造成影响
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function filter(callable $callable): static
    {
        $copy = $this->getCopy();

        $toRemoveIds = [];

        $copy->eachChildsDFS(function (TreeNode $childNode) use ($callable, &$toRemoveIds) {
            if (false === call_user_func_array($callable, [$childNode])) {
                $toRemoveIds[] = $childNode->getId();
            }
        });

        foreach ($toRemoveIds as $k => $id) {
            $copy->removeChildRecrusive($id);
        }

        return $copy;
    }

    /**
     * 查找器，过滤函数返回 false 就忽略指定元素，否则保留指定元素，返回满足条件的节点数组
     *
     * @param callable $callable
     *
     * @return $this[]
     */
    public function searchNodes(callable $callable): array
    {
        $nodes = [];

        $this->eachAllDFS(function (TreeNode $childNode) use ($callable, &$nodes) {
            if (call_user_func_array($callable, [$childNode])) {
                $nodes[] = $childNode;
            }
        });

        return $nodes;
    }

    /**
     * @param ?callable   $filterCallable 过滤函数
     * @param string|null $sortField      排序字段,可选
     * @param string|null $sortOrder      排序方向,可选
     *
     * @return array
     */
    public function toRaw(?callable $filterCallable = null, ?string $sortField = null, ?string $sortOrder = 'asc'): array
    {
        $data = [];
        $this->eachChildsDFS(function (TreeNode $childNode) use ($filterCallable, &$data) {
            if (is_callable($filterCallable)) {
                $resultNodeData = call_user_func_array($filterCallable, [$childNode->getData()]);

                if (is_array($resultNodeData)) {
                    $data[] = array_merge($resultNodeData, ["__LEVEL__" => $childNode->getLevel(),]);
                }
            } else {
                $data[] = array_merge($childNode->getData(), ["__LEVEL__" => $childNode->getLevel(),]);
            }
        });

        if (is_string($sortField)) {
            switch ($sortOrder) {
                case self::SORT_ORDER_ASC:
                    usort($data, function (array $a, array $b) use ($sortField) {

                        if ($a[$sortField] == $b[$sortField]) {
                            return 0;
                        }

                        return ($a[$sortField] < $b[$sortField]) ? -1 : 1;
                    });
                    break;
                case self::SORT_ORDER_DESC:
                    usort($data, function (array $a, array $b) use ($sortField) {

                        if ($a[$sortField] == $b[$sortField]) {
                            return 0;
                        }

                        return ($a[$sortField] > $b[$sortField]) ? -1 : 1;
                    });
                    break;
                default:
                    break;
            }
        }

        return $data;
    }


    /**
     * 转为回调方式处理
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function processor(callable $callback): static
    {
        $childs = [];

        call_user_func_array($callback, [
            $this,
            &$childs,
        ]);

        foreach ($childs as $k => $v) {
            $this->addChild($v);
        }

        return $this;
    }


    /**
     * 预定排序方法，按 data 里的字段排序
     *
     * @param string $field
     * @param string $order
     *
     * @return \Closure
     */
    protected static function sortByDataField(string $field, string $order): \Closure
    {
        return function ($childs) use ($field, $order) {
            $t = $childs;

            switch ($order) {
                case self::SORT_ORDER_ASC:
                    usort($t, function (TreeNode $a, TreeNode $b) use ($field) {

                        if ($a->getField($field) == $b->getField($field)) {
                            return 0;
                        }

                        return ($a->getField($field) < $b->getField($field)) ? -1 : 1;
                    });
                    break;
                case self::SORT_ORDER_DESC:
                    usort($t, function (TreeNode $a, TreeNode $b) use ($field) {

                        if ($a->getField($field) == $b->getField($field)) {
                            return 0;
                        }
                        return ($a->getField($field) > $b->getField($field)) ? -1 : 1;
                    });
                    break;
                default:
                    break;
            }

            return $t;
        };
    }
}
