<?php

    use Coco\tree\TreeNode;

    require '../vendor/autoload.php';

    $data1 = [
        1 => 111,
    ];
    $data2 = [
        2 => 222,
    ];
    $data3 = [
        3 => 333,
    ];

    $node1 = TreeNode::makeNode(1, null, $data1);
    $node2 = TreeNode::makeNode(2, null, $data2);
    $node3 = TreeNode::makeNode(3, null, $data3);

    $node1->addChild($node2);
    $node1->addChild($node3);
    
    print_r($node1->toJson(false));