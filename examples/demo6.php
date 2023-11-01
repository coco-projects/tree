<?php

    use Coco\tree\TreeNode;

    require '../vendor/autoload.php';

    $nodes = [];

    $nodes[0]  = TreeNode::makeNode(0);

    $nodes[1]  = TreeNode::makeNode(1);
    $nodes[12] = TreeNode::makeNode(12);
    $nodes[13] = TreeNode::makeNode(13);

    $nodes[2]  = TreeNode::makeNode(2);
    $nodes[21] = TreeNode::makeNode(21);
    $nodes[22] = TreeNode::makeNode(22);
    $nodes[23] = TreeNode::makeNode(23);

    $nodes[121] = TreeNode::makeNode(121);
    $nodes[131] = TreeNode::makeNode(131);


    $nodes[221] = TreeNode::makeNode(221);
    $nodes[231] = TreeNode::makeNode(231);



    $nodes[1]->addChild($nodes[12]);
    $nodes[1]->addChild($nodes[13]);


    $nodes[2]->addChild($nodes[21]);
    $nodes[2]->addChild($nodes[22]);
    $nodes[2]->addChild($nodes[23]);


    $nodes[0]->addChild($nodes[1]);
    $nodes[0]->addChild($nodes[2]);



    $nodes[12]->addChild($nodes[121]);
    $nodes[13]->addChild($nodes[131]);

    $nodes[22]->addChild($nodes[221]);
    $nodes[23]->addChild($nodes[231]);

    $childArray = $nodes[0]->getChildArray();

    $nodes[0]['k1'] = 'v1';

    print_r($nodes[0]->toJson());

