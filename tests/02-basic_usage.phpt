--TEST--
Basic usage
--FILE--
<?php
include_once(__DIR__ . '/bootstrap.php');

$dataList = array(
    array(
        'id'        => 1,
        'parent_id' => 0,
        'name'      => 'games'
    ),
    array(
        'id'        => 2,
        'parent_id' => 1,
        'name'      => 'TV games'
    ),
    array(
        'id'        => 3,
        'parent_id' => 2,
        'name'      => 'ACT'
    ),
);

$dataTree = new \ArrayTree\Tree($dataList);

$dataTree->setIdKey('id');
$dataTree->setParentIdKey('parent_id');
$dataTree->setResultChildKey('childs');
$dataTree->setResultParentIdsKey('parent_ids');

$dataTreeList = $dataTree->getArrayTree();

print_r($dataTreeList);

?>
--EXPECTF--
Array
(
    [0] => Array
        (
            [id] => 1
            [parent_id] => 0
            [name] => games
            [parent_ids] => Array
                (
                )

            [childs] => Array
                (
                    [0] => Array
                        (
                            [id] => 2
                            [parent_id] => 1
                            [name] => TV games
                            [parent_ids] => Array
                                (
                                    [0] => 1
                                )

                            [childs] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 3
                                            [parent_id] => 2
                                            [name] => ACT
                                            [parent_ids] => Array
                                                (
                                                    [0] => 2
                                                    [1] => 1
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
