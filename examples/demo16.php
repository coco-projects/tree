<?php

    use Coco\tree\DataItem;

    require '../vendor/autoload.php';

    $data = new DataItem([
        'id'     => 1,
        'parent' => 0,
        'title'  => 'Node 1-0',
        'data'   => 'data 1-0',
        'order'  => 18,
    ]);

    var_dump($data->isEquals('id', 1));
    var_dump($data->isArray('parent'));
    var_dump($data->isGreaterThan('id', 1));
    var_dump($data->isGreaterThanOrEqualTo('id', 1));
    var_dump($data->isLessThan('id', 1));
    var_dump($data->isLessThanOrEqualTo('id', 1));
    var_dump($data->isStrictEqual('id', 1));

    var_dump($data->isStartWith('title', 'Node'));
    var_dump($data->isContainsWith('title', 'e 1'));
    var_dump($data->isEndWith('title', '1-0'));