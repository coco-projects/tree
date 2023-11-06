<?php

    use Coco\tree\Tree;
    use Coco\tree\TreeNode;

    require '../vendor/autoload.php';
    $datas = [
        [
            'id'     => 1,
            'parent' => 0,
            'title'  => 'Node 1-0',
            'data'   => 'data 1-0',
        ],
        [
            'id'     => 2,
            'parent' => 1,
            'title'  => 'Node 2-1',
            'data'   => 'data 2-1',
        ],
        [
            'id'     => 3,
            'parent' => 2,
            'title'  => 'Node 3-3',
            'data'   => 'data 3-3',
        ],
        [
            'id'     => 4,
            'parent' => 1,
            'title'  => 'Node 4-1',
            'data'   => 'data 4-1',
        ],
        [
            'id'     => 5,
            'parent' => 2,
            'title'  => 'Node 5-2',
            'data'   => 'data 5-2',
        ],
        [
            'id'     => 6,
            'parent' => 9,
            'title'  => 'Node 6-3',
            'data'   => 'data 6-3',
        ],
        [
            'id'     => 7,
            'parent' => 5,
            'title'  => 'Node 7-5',
            'data'   => 'data 7-5',
        ],
        [
            'id'     => 8,
            'parent' => 2,
            'title'  => 'Node 8-2',
            'data'   => 'data 8-2',
        ],
        [
            'id'     => 9,
            'parent' => 3,
            'title'  => 'Node 9-3',
            'data'   => 'data 9-3',
        ],
    ];

    $source = new \Coco\tree\DataSource($datas);
    $source->setIdField('id');
    $source->setParentField('parent');
    $source->setRootId(0);

    $source->setDataFields('id');
    $source->setDataFields('title');
    $source->setDataFields('data', function ($item, $allData, $field) {
        return implode('', [
            '<',
            $item[$field],
            '>',
        ]);
    });

    //    $source->setData($datas);
    $tree = Tree::fromSource($source);

    $arr = $tree->toArrayAll();
    print_r(json_encode($arr));