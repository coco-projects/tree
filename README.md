
# Tree

##### Tree structure management tool that allows easy operations such as adding, modifying, deleting, searching, serializing, and deserializing nodes in a hierarchical structure.

---

### Here's a quick example:

```php
<?php

    use Coco\tree\TreeNode;

    require '../vendor/autoload.php';

    $nodes = [];

    $nodes[0] = TreeNode::makeNode(0);

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

    print_r($nodes[0]->toJson());

    /*
{
	"id"       : 0,
	"level"    : 0,
	"parentId" : null,
	"data"     : [],
	"childs"   : {
		"1" : {
			"id"       : 1,
			"level"    : 1,
			"parentId" : 0,
			"data"     : [],
			"childs"   : {
				"12" : {
					"id"       : 12,
					"level"    : 2,
					"parentId" : 1,
					"data"     : [],
					"childs"   : {
						"121" : {
							"id"       : 121,
							"level"    : 3,
							"parentId" : 12,
							"data"     : [],
							"childs"   : []
						}
					}
				},
				"13" : {
					"id"       : 13,
					"level"    : 2,
					"parentId" : 1,
					"data"     : [],
					"childs"   : {
						"131" : {
							"id"       : 131,
							"level"    : 3,
							"parentId" : 13,
							"data"     : [],
							"childs"   : []
						}
					}
				}
			}
		},
		"2" : {
			"id"       : 2,
			"level"    : 1,
			"parentId" : 0,
			"data"     : [],
			"childs"   : {
				"21" : {
					"id"       : 21,
					"level"    : 2,
					"parentId" : 2,
					"data"     : [],
					"childs"   : []
				},
				"22" : {
					"id"       : 22,
					"level"    : 2,
					"parentId" : 2,
					"data"     : [],
					"childs"   : {
						"221" : {
							"id"       : 221,
							"level"    : 3,
							"parentId" : 22,
							"data"     : [],
							"childs"   : []
						}
					}
				},
				"23" : {
					"id"       : 23,
					"level"    : 2,
					"parentId" : 2,
					"data"     : [],
					"childs"   : {
						"231" : {
							"id"       : 231,
							"level"    : 3,
							"parentId" : 23,
							"data"     : [],
							"childs"   : []
						}
					}
				}
			}
		}
	}
}

     */

```


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

## Installation

You can install the package via composer:

```bash
composer require coco-project/tree
```


> For more examples, please refer to the "examples","tests/Unit/TreeTest.php" 

## Testing

``` bash
composer test
```

## License

---

MIT
