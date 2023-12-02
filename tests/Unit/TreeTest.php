<?php

    declare(strict_types = 1);

    namespace Coco\Tests\Unit;

    use Coco\tree\Tree;
    use Coco\tree\TreeNode;
    use PHPUnit\Framework\TestCase;

final class TreeTest extends TestCase
{
    public $nodes = [];

    //每次测试之前都会执行的方法，类似 __contruct
    protected function setUp(): void
    {
        $this->nodes[0] = TreeNode::makeNode(0);

        $this->nodes[1]  = TreeNode::makeNode(1);
        $this->nodes[12] = TreeNode::makeNode(12);
        $this->nodes[13] = TreeNode::makeNode(13);

        $this->nodes[2]  = TreeNode::makeNode(2);
        $this->nodes[21] = TreeNode::makeNode(21);
        $this->nodes[22] = TreeNode::makeNode(22);
        $this->nodes[23] = TreeNode::makeNode(23);
        $this->nodes[24] = TreeNode::makeNode(24);
        $this->nodes[25] = TreeNode::makeNode(25);

        $this->nodes[121] = TreeNode::makeNode(121);
        $this->nodes[131] = TreeNode::makeNode(131);

        $this->nodes[221] = TreeNode::makeNode(221);
        $this->nodes[231] = TreeNode::makeNode(231);

        $this->nodes[3] = TreeNode::makeNode(3);

        $this->nodes[31] = TreeNode::makeNode(31);
        $this->nodes[32] = TreeNode::makeNode(32);

        $this->nodes[1000] = TreeNode::makeNode(1000);
    }

    //每次测试结束都会执行的方法，类似 __destruct
    protected function tearDown(): void
    {
    }

    public function testA()
    {
        $this->nodes[0]->addChild($this->nodes[1]);
        $this->nodes[0]->addChild($this->nodes[2]);

        $this->nodes[1]->addChild($this->nodes[12]);
        $this->nodes[1]->addChild($this->nodes[13]);

        $this->nodes[2]->addChild($this->nodes[21]);
        $this->nodes[2]->addChild($this->nodes[22]);
        $this->nodes[2]->addChild($this->nodes[23]);
        $this->nodes[2]->addChild($this->nodes[24]);
        $this->nodes[2]->addChild($this->nodes[25]);

        $this->nodes[12]->addChild($this->nodes[121]);
        $this->nodes[13]->addChild($this->nodes[131]);

        $this->nodes[22]->addChild($this->nodes[221]);

        $this->nodes[0]->addChildRecrusive($this->nodes[231], 23);

        $n = $this->nodes[0]->toArrayAll();

        $this->assertTrue($n['childs'][1]['childs'][12]['childs'][121]['level'] === 3);

        $this->assertEquals($this->nodes[231]->getTopParent()->getId(), 0);
        $this->assertEquals($this->nodes[231]->getParentNode()->getId(), 23);
        $this->assertEquals($this->nodes[0]->getChildRecrusive(231)->getId(), 231);
        $this->assertEquals($this->nodes[0]->isChildExists(1), true);
        $this->assertEquals($this->nodes[0]->isChildExistsRecrusive(221), true);
        $this->assertEquals($this->nodes[0]->isChildExistsRecrusive(2101), false);

        $_221 = $this->nodes[0]->getChildRecrusive(221);

        $this->nodes[1000]->appendTo($_221);

        $this->assertEquals($this->nodes[221]->isChildExists(1000), true);
        $this->assertEquals($this->nodes[221]->isChildExists(2000), false);

        $s1 = $this->nodes[23]->getSiblingsAndSelf();
        $s2 = $this->nodes[23]->getSiblings();
    }

    public function testB()
    {
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';

        if (isset($this->nodes[0]['k2'])) {
            unset($this->nodes[0]['k2']);
        }

        $this->assertEquals($this->nodes[0]['k1'], 'v1');
    }

    public function testC()
    {
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';
        $this->nodes[0]->destroy();

        $this->assertEquals($this->nodes[0]->getData(), []);
    }


    public function testD()
    {
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';

        $t = [];

        //            $this->nodes[0]->eachField(fn($v, $k) => ($t[$k] = $v));

        $this->nodes[0]->eachField(function ($v, $k) use (&$t) {
            $t[$k] = $v;
        });

        $this->assertEquals($t, [
            "k1" => "v1",
            "k2" => "v2",
        ]);
    }

    public function testE()
    {
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';

        $t = [];

        foreach ($this->nodes[0] as $k => $v) {
            $t[$k] = $v;
        }

        $this->assertEquals($t, [
            "k1" => "v1",
            "k2" => "v2",
        ]);
    }

    public function testF()
    {
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';

        $this->nodes[0]->fetchField('k2');
        $this->nodes[0]->fetchField('k3');

        $this->assertEquals($this->nodes[0]->getData(), [
            'k1' => 'v1',
        ]);
    }

    public function testG()
    {
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';
        $this->nodes[0]->importData([
            "k3" => "v3",
        ]);

        $this->assertEquals($this->nodes[0]->count(), 3);
    }

    public function testH()
    {
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';
        $this->nodes[0]->importData([
            "k3" => "v3",
        ]);

        $this->nodes[0]->addChild($this->nodes[1]);
        $this->nodes[0]->addChild($this->nodes[2]);

        $this->nodes[1]->addChild($this->nodes[12]);
        $this->nodes[1]->addChild($this->nodes[13]);

        $this->nodes[2]->addChild($this->nodes[21]);
        $this->nodes[2]->addChild($this->nodes[22]);
        $this->nodes[2]->addChild($this->nodes[23]);
        $this->nodes[2]->addChild($this->nodes[24]);
        $this->nodes[2]->addChild($this->nodes[25]);

        $this->nodes[12]->addChild($this->nodes[121]);
        $this->nodes[13]->addChild($this->nodes[131]);

        $this->nodes[22]->addChild($this->nodes[221]);

        $s1 = $this->nodes[0]->toArrayAll();

        $s2 = $this->nodes[0]->toArraySelfAndChilds();

        $s1 = $this->nodes[0]->toJson();

        $total1 = 0;
        $this->nodes[0]->eachChildsDFS(function ($childNode) use (&$total1) {
            $total1++;
        });

        $total2 = 0;
        $this->nodes[0]->eachChildsBFS(function ($childNode) use (&$total2) {
            $total2++;
        });

        $this->assertEquals($this->nodes[0]->countChildsRecrusive(), $total2);
        $this->assertEquals($total1, $total2);

        $this->nodes[22]->removeChildRecrusive(221);
        $this->nodes[0]->removeChildRecrusive(121);

        $this->assertEquals($this->nodes[0]->countChildsRecrusive(), 10);

        $total1 = 0;
        $this->nodes[0]->eachChildsDFS(function ($childNode) use (&$total1) {
            $total1++;
        });

        $total2 = 0;
        $this->nodes[0]->eachChildsBFS(function ($childNode) use (&$total2) {
            $total2++;
        });

        (string)$this->nodes[0];

        $this->assertEquals($this->nodes[0]->countChilds(), 2);
        $this->assertEquals($this->nodes[0]->hasChild(), true);
        $this->assertEquals($this->nodes[0]->getParentsNode(), false);
    }


    public function testI()
    {
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';
        $this->nodes[0]->importData([
            "k3" => "v3",
        ]);

        $this->nodes[0]->addChild($this->nodes[1]);
        $this->nodes[0]->addChild($this->nodes[2]);

        $this->nodes[1]->addChild($this->nodes[12]);
        $this->nodes[1]->addChild($this->nodes[13]);

        $this->nodes[2]->addChild($this->nodes[21]);
        $this->nodes[2]->addChild($this->nodes[22]);
        $this->nodes[2]->addChild($this->nodes[23]);
        $this->nodes[2]->addChild($this->nodes[24]);
        $this->nodes[2]->addChild($this->nodes[25]);

        $this->nodes[12]->addChild($this->nodes[121]);
        $this->nodes[13]->addChild($this->nodes[131]);

        $this->nodes[22]->addChild($this->nodes[221]);

        $arr1  = $this->nodes[0]->toArrayAll();
        $json1 = $this->nodes[0]->toJson();

        $node = Tree::formArray($arr1);
        $arr2 = $node->toArrayAll();

        $node1 = Tree::formJson($json1);
        $json2 = $node1->toJson();

        $this->assertEquals($arr1, $arr2);
        $this->assertEquals($json1, $json2);

        $this->nodes[0]->toArrayAll(false);
        $this->nodes[0]->toArraySelfAndChilds(false);

        $this->nodes[0]->moveNodeToNewParent(221, 13);
    }


    public function testI1()
    {

        $this->expectException(\LogicException::class);
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';
        $this->nodes[0]->importData([
            "k3" => "v3",
        ]);

        $this->nodes[0]->addChild($this->nodes[1]);
        $this->nodes[0]->addChild($this->nodes[2]);

        $this->nodes[1]->addChild($this->nodes[12]);
        $this->nodes[1]->addChild($this->nodes[13]);

        $this->nodes[2]->addChild($this->nodes[21]);
        $this->nodes[2]->addChild($this->nodes[22]);
        $this->nodes[2]->addChild($this->nodes[23]);
        $this->nodes[2]->addChild($this->nodes[24]);
        $this->nodes[2]->addChild($this->nodes[25]);

        $this->nodes[12]->addChild($this->nodes[121]);
        $this->nodes[13]->addChild($this->nodes[131]);

        $this->nodes[22]->addChild($this->nodes[221]);

        $this->nodes[0]->moveNodeToNewParent(2210, 13);
    }


    public function testI2()
    {
        $this->expectException(\LogicException::class);
        $this->nodes[0]['k1'] = 'v1';
        $this->nodes[0]['k2'] = 'v2';
        $this->nodes[0]->importData([
            "k3" => "v3",
        ]);

        $this->nodes[0]->addChild($this->nodes[1]);
        $this->nodes[0]->addChild($this->nodes[2]);

        $this->nodes[1]->addChild($this->nodes[12]);
        $this->nodes[1]->addChild($this->nodes[13]);

        $this->nodes[2]->addChild($this->nodes[21]);
        $this->nodes[2]->addChild($this->nodes[22]);
        $this->nodes[2]->addChild($this->nodes[23]);
        $this->nodes[2]->addChild($this->nodes[24]);
        $this->nodes[2]->addChild($this->nodes[25]);

        $this->nodes[12]->addChild($this->nodes[121]);
        $this->nodes[13]->addChild($this->nodes[131]);

        $this->nodes[22]->addChild($this->nodes[221]);

        $this->nodes[0]->moveNodeToNewParent(221, 130);
    }

    public function testJ()
    {
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

        $this->assertEquals($arr['childs'][1]['childs'][4]['data']['data'], '<data 4-1>');
    }

    public function testK()
    {

        $datas = [
            [
                'id'     => 1,
                'parent' => 0,
                'title'  => 'Node 1-0',
                'data'   => 'data 1-0',
            ],
            [
                'id'     => 2,
                'parent' => 0,
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

        $tree = $source->toTree();
        $arr  = $tree->toArrayAll();

        $res1 = [];
        $tree->eachAllDFS(function ($childNode) use (&$res1) {
            $res1[] = $childNode->getId();
        });

        $res2 = [];
        $tree->eachChildsDFS(function ($childNode) use (&$res2) {
            $res2[] = $childNode->getId();
        });

        $res3 = [];
        $tree->eachAllBFS(function ($childNode) use (&$res3) {
            $res3[] = $childNode->getId();
        });

        $res4 = [];
        $tree->eachChildsBFS(function ($childNode) use (&$res4) {
            $res4[] = $childNode->getId();
        });

        $this->assertEquals(false, TreeNode::isCatenary($tree));
    }


    /**
     * @test
     */
    public function L()
    {
        $this->nodes[0]->addChild($this->nodes[1]);
        $this->nodes[1]->addChild($this->nodes[12]);
        $this->nodes[12]->addChild($this->nodes[121]);
        $this->nodes[121]->addChild($this->nodes[0]);
        $this->assertTrue(TreeNode::isCatenary($this->nodes[121]));
    }


    /**
     * @test
     */
    public function M()
    {

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
        $source->setDataFields('title');
        $source->setDataFields('order');
        $source->setDataFields('data', function ($item, $allData, $field) {
            return implode('', [
                '<',
                $item[$field],
                '>',
            ]);
        });

        // $source->setData($datas);

        $tree = $source->toTree();

        $expect = [
            0,
            3,
            9,
            6,
            1,
            4,
            8,
            2,
            5,
            7,
        ];
        $tree->sort('order', TreeNode::SORT_ORDER_ASC);
        $res1 = [];
        $tree->eachAllDFS(function ($childNode) use (&$res1) {
            $res1[] = $childNode->getId();
        });
        $this->assertTrue($res1 == $expect);

        $tree->sort('order', TreeNode::SORT_ORDER_DESC);
        $res2    = [];
        $expect2 = [
            0,
            1,
            2,
            7,
            5,
            8,
            4,
            3,
            6,
            9,
        ];
        $tree->eachAllDFS(function ($childNode) use (&$res2) {
            $res2[] = $childNode->getId();
        });
        $this->assertTrue($res2 == $expect2);
    }

    public function testM()
    {

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
                'order'  => 11,
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
        $source->setDataFields('title');
        $source->setDataFields('order');
        $source->setDataFields('data', function ($item, $allData, $field) {
            return implode('', [
                '<',
                $item[$field],
                '>',
            ]);
        });

        //    $source->setData($datas);

        $tree = $source->toTree();

        $result = $tree->filter(function (TreeNode $childNode) {
            return $childNode->getField('order') < 13;
        });

        $expect2 = [
            0 => 0,
            1 => 3,
            2 => 9,
        ];

        $res1 = [];
        $result->eachAllDFS(function ($childNode) use (&$res1) {
            $res1[] = $childNode->getId();
        });

        $this->assertTrue($res1 == $expect2);
    }

    public function testO()
    {

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
        $source->setDataFields('data', function ($item, $allData, $field) {
            return implode('', [
                '<',
                $item[$field],
                '>',
            ]);
        });

        //    $source->setData($datas);

        $tree = $source->toTree();

        ($tree->toRaw(null, 'order', TreeNode::SORT_ORDER_DESC));

        $result = $tree->toRaw(function (array $childNodeData) {
            if ($childNodeData['order'] > 14) {
                return [
                    "id"   => $childNodeData['id'],
                    "data" => $childNodeData['data'],
                ];
            }
        }, 'id', TreeNode::SORT_ORDER_ASC);

        $expect = [
            0 => [
                'id'        => 1,
                'data'      => '<data 1-0>',
                '__LEVEL__' => 1,
            ],
            1 => [
                'id'        => 3,
                'data'      => '<data 3-3>',
                '__LEVEL__' => 1,
            ],
            2 => [
                'id'        => 5,
                'data'      => '<data 5-2>',
                '__LEVEL__' => 3,
            ],
            3 => [
                'id'        => 7,
                'data'      => '<data 7-5>',
                '__LEVEL__' => 3,
            ],
        ];

        $this->assertTrue($result == $expect);
    }

    public function testP()
    {

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
        $source->setDataFields('data', function ($item, $allData, $field) {
            return implode('', [
                '<',
                $item[$field],
                '>',
            ]);
        });

        $tree = Tree::fromSource($source);

        $res = Tree::transformer($tree, function (TreeNode $treeNode) {
            return [
                'id_'       => $treeNode->getId(),
                'level_'    => $treeNode->getLevel(),
                'parentId_' => $treeNode->getParentNode()?->getId(),
                'data_'     => $treeNode->getData(),
                "test"      => [
                    'order_'  => $treeNode['order'],
                    'childs_' => [
                        "son" => '__CHILDS_FIELD__',
                    ],
                ],
            ];
        });

        $this->assertTrue(true);
    }

    public function testQ()
    {

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
        $source->setDataFields('data', function ($item, $allData, $field) {
            return implode('', [
                '<',
                $item[$field],
                '>',
            ]);
        });

        //    $source->setData($datas);

        $tree = $source->toTree();

        $res1 = $tree->searchNodes(function (TreeNode $childNode) {
            return $childNode->isContainsWith('title', '-1');
        });

        $res2 = $tree->searchNodes(function (TreeNode $childNode) {
            return $childNode->isStartWith('title', 'Node 8-');
        });

        $res3 = $tree->searchNodes(function (TreeNode $childNode) {
            return $childNode->isGreaterThan('order', 13);
        });

        $res4 = $tree->searchNodes(function (TreeNode $childNode) {

            return $childNode->searchByField('title', function ($value) {
                if (!is_string($value)) {
                    return false;
                }
                return !!preg_match('#-3$#ius', $value);
            });
        });
        $this->assertTrue(true);
    }

    public function testR()
    {

        $data1 = [
            1   => 111,
            'a' => 'aaa',
        ];

        $node1 = TreeNode::makeNode(1, null, $data1);

        $node1_copy = $node1->getCopy();

        $node1_copy[1] = 222;

        $node1_copy['a'] = 'bbb';

        $node1_copy[1] = 222;
        $this->assertTrue($node1[1] == 111);
        $this->assertTrue($node1_copy[1] == 222);

        $this->assertTrue($node1['a'] == 'aaa');
        $this->assertTrue($node1_copy['a'] == 'bbb');
    }
}
