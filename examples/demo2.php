<?php

    require '../vendor/autoload.php';

    $data = new \Coco\tree\TreeNode(1, null, [
        1   => 111,
        2   => 222,
        3   => 333,
        "a" => "aaa",
        "b" => "bbb",
        "c" => "ccc",
    ]);

    $data['d'] = 'ddd';

    $data->eachField(function($v, $k) {
        echo implode('', [
            $k,
            ' => ',
            $v,
            PHP_EOL,
        ]);
    });

    print_r($data);