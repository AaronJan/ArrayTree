ArrayTree
=========

ArrayTree is a tool for converting 2-d array to tree structure(also called nested or hierarchical) array.


Here is an example:

```php
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

//Converting
$dataTree = new \ArrayTree\Tree($dataList);

$dataTree->setIdKey('id');
$dataTree->setParentIdKey('parent_id');
$dataTree->setResultChildKey('childs');
$dataTree->setResultParentIdsKey('parent_ids');

$dataTreeList = $dataTree->getArrayTree();

print_r($dataTreeList);

/*
results:

array(
    'id'         => 1,
    'parent_id'  => 0,
    'parent_ids' => array(),
    'name'       => 'games',
    'childs'     => array(
        array(
            'id'         => 2,
            'parent_id'  => 1,
            'parent_ids' => array(1),
            'name'       => 'TV games',
            'childs'     => array(
                array(
                    'id'         => 3,
                    'parent_id'  => 2,
                    'parent_ids' => array(1, 2),
                    'name'       => 'ACT',
                    'childs'     => array()
                ),
            )
        ),
    )
)
*/
```

---------------------------------------
## \ArrayTree\Tree

You only need to deal with this class.


#### __construct([$dataArray])
You can pass data array to constructor.

#### setData(array $dataList)
Set data array.

#### addData(array $dataEntry)
Add a data entry to data array.

#### setIdKey($idKey)
Specify key name for data array's id attribute.

#### setParentIdKey($parentKey)
Specify key name for data array's parent id attribute.

#### setResultChildKey($childKey)
Specify key name of result for store child arrays.

#### setResultParentIdsKey($parentIdsKey)
Specify key name of result for store parent nodes' ids.

#### getArrayTree()
Convert data to nodes, then converting nodes to array-tree-style nested array like the example above.

#### getArray()
Convert data to nodes, then converting nodes to array.

If you only want the "parent ids" results and a 2-d array, you can use this.

---------------------------------------
## \ArrayTree\Node

Linked-list helper class.

---------------------------------------

## TODO

Write docs about \ArrayTree\Node

Write more useful examples

