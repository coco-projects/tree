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
}
