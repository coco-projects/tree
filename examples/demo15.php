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
//    $source->setParentField(null);
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
    }, function(TreeNode $treeNode) {
        return $treeNode->getId();
    });

    print_r(json_encode($res));

    /*
{
	"id_"       : 0,
	"level_"    : 0,
	"parentId_" : null,
	"data_"     : [],
	"subFields" : {
		"order_"  : [],
		"childs_" : {
			"son" : [
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
												},
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
												}
											]
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
								}
							]
						}
					}
				},
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
								},
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