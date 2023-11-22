<?php

    declare(strict_types = 1);

    namespace Coco\tree;

class Tree
{
    /**
     * 数据库查出的数据直接导入，自动生成树
     *
     * @param DataSource $source
     *
     * @return TreeNode
     */
    public static function fromSource(DataSource $source): TreeNode
    {
        return $source->toTree();
    }

    /**
     * json 还原成树, 和 toJson 方法对应
     *
     * @param string $treeJson
     *
     * @return TreeNode
     */
    public static function formJson(string $treeJson): TreeNode
    {
        $tree = json_decode($treeJson, true);

        return static::formArray($tree);
    }

    /**
     * 数组还原成对象, 和 exportArrayRecrusive 方法对应
     *
     * @param array $tree
     *
     * @return TreeNode
     */
    public static function formArray(array $tree): TreeNode
    {
        $obj = TreeNode::makeNode($tree['id'], null, $tree['data']);

        foreach ($tree['childs'] as $k1 => $v1) {
            $obj->addChild(static::formArray($v1));
        }

        return $obj;
    }

    /**
     * 将树导出为自定义结构的数组
     *
     * @param TreeNode      $tree
     * @param callable      $callable
     * @param callable|null $indexCallback
     *
     * @return array
     */
    public static function transformer(TreeNode $tree, callable $callable, callable $indexCallback = null): array
    {
        $result = call_user_func_array($callable, [$tree]);

        return self::_processArray($result, $tree, $callable, $indexCallback);
    }

    /**
     * @param array         $arr
     * @param TreeNode      $treeNode
     * @param callable      $callable
     * @param callable|null $indexCallback
     *
     * @return array
     */
    private static function _processArray(array &$arr, TreeNode $treeNode, callable $callable, callable $indexCallback = null): array
    {
        foreach ($arr as &$v) {
            if ($v == '__CHILDS_FIELD__') {
                $v = (function () use ($treeNode, $callable, $indexCallback) {
                    $childs = [];
                    $treeNode->eachChilds(function (TreeNode $childNode) use (&$childs, $callable, $indexCallback) {
                        if (is_callable($indexCallback)) {
                            $index = call_user_func_array($indexCallback, [$childNode]);
                            $childs[$index] = static::transformer($childNode, $callable, $indexCallback);
                        } else {
                            $childs[] = static::transformer($childNode, $callable, $indexCallback);
                        }
                    });

                    return $childs;
                })();
            }

            if (is_array($v)) {
                self::_processArray($v, $treeNode, $callable, $indexCallback);
            }
        }

        return $arr;
    }
}
