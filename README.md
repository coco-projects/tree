
# Tree

##### Tree structure management tool that allows easy operations such as adding, modifying, deleting, searching, serializing, and deserializing nodes in a hierarchical structure.

---


## Installation

You can install the package via composer:

```bash
composer require coco-project/tree
```


> For more examples, please refer to the "examples","tests/Unit/TreeTest.php"


### Here's a quick example:

```php
<?php

    use Coco\tree\DataSource;
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

    $source = new DataSource($datas);
    $source->setIdField('id');
    $source->setParentField('parent');
    $source->setRootId(0);

    $source->setDataFields('id');
    $source->setDataFields('title');
    $source->setDataFields('order');
    $source->setDataFields('data', function($item, $allData, $field) {
        return '<' . $item[$field] . '>';
    });

    $tree = $source->toTree();
    print_r($tree->toArrayAll());

/*

Array
(
    [id] => 0
    [level] => 0
    [parentId] => 
    [data] => Array
        (
        )

    [childs] => Array
        (
            [3] => Array
                (
                    [id] => 3
                    [level] => 1
                    [parentId] => 0
                    [data] => Array
                        (
                            [id] => 3
                            [title] => Node 3-3
                            [order] => 17
                            [data] => <data 3-3>
                        )

                    [childs] => Array
                        (
                            [9] => Array
                                (
                                    [id] => 9
                                    [level] => 2
                                    [parentId] => 3
                                    [data] => Array
                                        (
                                            [id] => 9
                                            [title] => Node 9-3
                                            [order] => 10
                                            [data] => <data 9-3>
                                        )

                                    [childs] => Array
                                        (
                                        )

                                )

                            [6] => Array
                                (
                                    [id] => 6
                                    [level] => 2
                                    [parentId] => 3
                                    [data] => Array
                                        (
                                            [id] => 6
                                            [title] => Node 6-3
                                            [order] => 14
                                            [data] => <data 6-3>
                                        )

                                    [childs] => Array
                                        (
                                        )

                                )

                        )

                )

            [1] => Array
                (
                    [id] => 1
                    [level] => 1
                    [parentId] => 0
                    [data] => Array
                        (
                            [id] => 1
                            [title] => Node 1-0
                            [order] => 18
                            [data] => <data 1-0>
                        )

                    [childs] => Array
                        (
                            [8] => Array
                                (
                                    [id] => 8
                                    [level] => 2
                                    [parentId] => 1
                                    [data] => Array
                                        (
                                            [id] => 8
                                            [title] => Node 8-2
                                            [order] => 12
                                            [data] => <data 8-2>
                                        )

                                    [childs] => Array
                                        (
                                        )

                                )

                            [4] => Array
                                (
                                    [id] => 4
                                    [level] => 2
                                    [parentId] => 1
                                    [data] => Array
                                        (
                                            [id] => 4
                                            [title] => Node 4-1
                                            [order] => 11
                                            [data] => <data 4-1>
                                        )

                                    [childs] => Array
                                        (
                                        )

                                )

                            [2] => Array
                                (
                                    [id] => 2
                                    [level] => 2
                                    [parentId] => 1
                                    [data] => Array
                                        (
                                            [id] => 2
                                            [title] => Node 2-1
                                            [order] => 13
                                            [data] => <data 2-1>
                                        )

                                    [childs] => Array
                                        (
                                            [7] => Array
                                                (
                                                    [id] => 7
                                                    [level] => 3
                                                    [parentId] => 2
                                                    [data] => Array
                                                        (
                                                            [id] => 7
                                                            [title] => Node 7-5
                                                            [order] => 16
                                                            [data] => <data 7-5>
                                                        )

                                                    [childs] => Array
                                                        (
                                                        )

                                                )

                                            [5] => Array
                                                (
                                                    [id] => 5
                                                    [level] => 3
                                                    [parentId] => 2
                                                    [data] => Array
                                                        (
                                                            [id] => 5
                                                            [title] => Node 5-2
                                                            [order] => 15
                                                            [data] => <data 5-2>
                                                        )

                                                    [childs] => Array
                                                        (
                                                        )

                                                )

                                        )

                                )

                        )

                )

        )

)

 */

```


```php

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

    $tree = $source->toTree();

    $res = Tree::transformer($tree, function(TreeNode $treeNode) {
        return [
            'id_'       => $treeNode->getId(),
            'level_'    => $treeNode->getLevel(),
            'parentId_' => $treeNode->getParentNode()?->getId(),
            'data_'     => $treeNode->getData(),
            "subFields"      => [
                'order_'  => $treeNode['order'],
                'childs_' => [
                    "son" => '__CHILDS_FIELD__',
                ],
            ],
        ];
    });

    print_r(json_encode($res));

    /*
{
	"id_"       : 0,
	"level_"    : 0,
	"parentId_" : null,
	"data_"     : [],
	"subFields" : {
		"order_"  : null,
		"childs_" : {
			"son" : [
				{
					"id_"       : 3,
					"level_"    : 1,
					"parentId_" : 0,
					"data_"     : {
						"id"     : 3,
						"parent" : 0,
						"title"  : "Node 3-3",
						"order"  : 17,
						"data"   : "<data 3-3>"
					},
					"subFields" : {
						"order_"  : 17,
						"childs_" : {
							"son" : [
								{
									"id_"       : 9,
									"level_"    : 2,
									"parentId_" : 3,
									"data_"     : {
										"id"     : 9,
										"parent" : 3,
										"title"  : "Node 9-3",
										"order"  : 10,
										"data"   : "<data 9-3>"
									},
									"subFields" : {
										"order_"  : 10,
										"childs_" : {
											"son" : []
										}
									}
								},
								{
									"id_"       : 6,
									"level_"    : 2,
									"parentId_" : 3,
									"data_"     : {
										"id"     : 6,
										"parent" : 3,
										"title"  : "Node 6-3",
										"order"  : 14,
										"data"   : "<data 6-3>"
									},
									"subFields" : {
										"order_"  : 14,
										"childs_" : {
											"son" : []
										}
									}
								}
							]
						}
					}
				},
				{
					"id_"       : 1,
					"level_"    : 1,
					"parentId_" : 0,
					"data_"     : {
						"id"     : 1,
						"parent" : 0,
						"title"  : "Node 1-0",
						"order"  : 18,
						"data"   : "<data 1-0>"
					},
					"subFields" : {
						"order_"  : 18,
						"childs_" : {
							"son" : [
								{
									"id_"       : 8,
									"level_"    : 2,
									"parentId_" : 1,
									"data_"     : {
										"id"     : 8,
										"parent" : 1,
										"title"  : "Node 8-2",
										"order"  : 12,
										"data"   : "<data 8-2>"
									},
									"subFields" : {
										"order_"  : 12,
										"childs_" : {
											"son" : []
										}
									}
								},
								{
									"id_"       : 4,
									"level_"    : 2,
									"parentId_" : 1,
									"data_"     : {
										"id"     : 4,
										"parent" : 1,
										"title"  : "Node 4-1",
										"order"  : 11,
										"data"   : "<data 4-1>"
									},
									"subFields" : {
										"order_"  : 11,
										"childs_" : {
											"son" : []
										}
									}
								},
								{
									"id_"       : 2,
									"level_"    : 2,
									"parentId_" : 1,
									"data_"     : {
										"id"     : 2,
										"parent" : 1,
										"title"  : "Node 2-1",
										"order"  : 13,
										"data"   : "<data 2-1>"
									},
									"subFields" : {
										"order_"  : 13,
										"childs_" : {
											"son" : [
												{
													"id_"       : 7,
													"level_"    : 3,
													"parentId_" : 2,
													"data_"     : {
														"id"     : 7,
														"parent" : 2,
														"title"  : "Node 7-5",
														"order"  : 16,
														"data"   : "<data 7-5>"
													},
													"subFields" : {
														"order_"  : 16,
														"childs_" : {
															"son" : []
														}
													}
												},
												{
													"id_"       : 5,
													"level_"    : 3,
													"parentId_" : 2,
													"data_"     : {
														"id"     : 5,
														"parent" : 2,
														"title"  : "Node 5-2",
														"order"  : 15,
														"data"   : "<data 5-2>"
													},
													"subFields" : {
														"order_"  : 15,
														"childs_" : {
															"son" : []
														}
													}
												}
											]
										}
									}
								}
							]
						}
					}
				}
			]
		}
	}
}

     */


```

## Testing

``` bash
composer test
```

## License

---

MIT
