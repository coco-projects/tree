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
     * @param TreeNode $tree
     * @param          $callable
     *
     * @return array
     */
    public static function transformer(TreeNode $tree, $callable):array
    {
        $result = call_user_func_array($callable, [$tree]);

        return static::_processArray($result, $tree, $callable);
    }

    /**
     * @param array    $arr
     * @param TreeNode $treeNode
     * @param callable $callable
     *
     * @return array
     */
    private static function _processArray(array &$arr, TreeNode $treeNode, callable $callable):array
    {
        foreach ($arr as $k => &$v) {
            if ($v == '__CHILDS_FIELD__') {
                $v = (function () use ($treeNode, $callable) {
                    $childs = [];
                    $treeNode->eachChilds(function (TreeNode $childNode) use (&$childs, $callable) {

                        $childs[] = static::transformer($childNode, $callable);
                    });

                    return $childs;
                })();
            }

            if (is_array($v)) {
                static::_processArray($v, $treeNode, $callable);
            }
        }

        return $arr;
    }
}
