<?php

    use Coco\tree\TreeNode;

    require '../vendor/autoload.php';

    $node = TreeNode::makeNode(0);

    $node->processor(function(TreeNode $this_, &$childs) {

        $childs[] = TreeNode::makeNode(1)->processor(function(TreeNode $this_, &$childs) {
            $childs[] = TreeNode::makeNode(11)->processor(function(TreeNode $this_, &$childs) {
            });
            $childs[] = TreeNode::makeNode(12)->processor(function(TreeNode $this_, &$childs) {
            });
            $childs[] = TreeNode::makeNode(13)->processor(function(TreeNode $this_, &$childs) {
                $childs[] = TreeNode::makeNode(131)->processor(function(TreeNode $this_, &$childs) {
                });
            });
        });

        $childs[] = TreeNode::makeNode(2)->processor(function(TreeNode $this_, &$childs) {
            $childs[] = TreeNode::makeNode(21)->processor(function(TreeNode $this_, &$childs) {
                $childs[] = TreeNode::makeNode(211)->processor(function(TreeNode $this_, &$childs) {
                });
            });
            $childs[] = TreeNode::makeNode(22)->processor(function(TreeNode $this_, &$childs) {
                $childs[] = TreeNode::makeNode(221)->processor(function(TreeNode $this_, &$childs) {
                });
            });
        });

        $childs[] = TreeNode::makeNode(3)->processor(function(TreeNode $this_, &$childs) {
            $childs[] = TreeNode::makeNode(31)->processor(function(TreeNode $this_, &$childs) {
            });
            $childs[] = TreeNode::makeNode(32)->processor(function(TreeNode $this_, &$childs) {
                $childs[] = TreeNode::makeNode(321)->processor(function(TreeNode $this_, &$childs) {
                });
            });
            $childs[] = TreeNode::makeNode(33)->processor(function(TreeNode $this_, &$childs) {
            });
        });

    });

    print_r($node->toJson());
    echo PHP_EOL;
    echo PHP_EOL;

    $node->moveNodeToNewParent(33, 221);
    print_r($node->toJson());
    echo PHP_EOL;
    echo PHP_EOL;
