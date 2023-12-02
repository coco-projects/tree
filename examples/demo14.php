<?php

    use Coco\tree\TreeNode;

    require '../vendor/autoload.php';

    $datas = [
        [
            'id'     => 1,
            'parent' => 0,
            'title'  => 'Node 1-0',
            'data'   => 'data 1-0',
            'order'  => 18,
        ],
        [
            'id'     => 2,
            'parent' => 1,
            'title'  => 'Node 2-1',
            'data'   => 'data 2-1',
            'order'  => 13,
        ],
        [
            'id'     => 3,
            'parent' => 0,
            'title'  => 'Node 3-3',
            'data'   => 'data 3-3',
            'order'  => 17,
        ],
        [
            'id'     => 4,
            'parent' => 1,
            'title'  => 'Node 4-1',
            'data'   => 'data 4-1',
            'order'  => 11,
        ],
        [
            'id'     => 5,
            'parent' => 2,
            'title'  => 'Node 5-2',
            'data'   => 'data 5-2',
            'order'  => 15,
        ],
        [
            'id'     => 6,
            'parent' => 3,
            'title'  => 'Node 6-3',
            'data'   => 'data 6-3',
            'order'  => 14,
        ],
        [
            'id'     => 7,
            'parent' => 2,
            'title'  => 'Node 7-5',
            'data'   => 'data 7-5',
            'order'  => 16,
        ],
        [
            'id'     => 8,
            'parent' => 1,
            'title'  => 'Node 8-2',
            'data'   => 'data 8-2',
            'order'  => 12,
        ],
        [
            'id'     => 9,
            'parent' => 3,
            'title'  => 'Node 9-3',
            'data'   => 'data 9-3',
            'order'  => 10,
        ],
    ];

    $source = new \Coco\tree\DataSource($datas);
    $source->setIdField('id');
    $source->setParentField('parent');
    $source->setRootId(0);

    $source->setDataFields('id');
    $source->setDataFields('parent');
    $source->setDataFields('title');
    $source->setDataFields('order');
    $source->setDataFields('data', function($item, $allData, $field) {
        return implode('', [
            '<',
            $item[$field],
            '>',
        ]);
    });

    //    $source->setData($datas);

    $tree = $source->toTree();

//    var_export($tree->toRaw());
    echo PHP_EOL;
    echo PHP_EOL;

    var_export($tree->toRaw(function(array $childNodeData) {
        if ($childNodeData['order'] > 0)
        {
            return [
                "id"     => $childNodeData['id'],
                "data"   => $childNodeData['data'],
                "parent" => $childNodeData['parent'],
            ];
        }
    }, 'id', TreeNode::SORT_ORDER_ASC));

















