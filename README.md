ArrayTree
=========

ArrayTree is a tool for converting 2-d array to tree structure array.

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
$categoryTree = new \ArrayTree\Tree($dataList);

$categoryTree->setIdKey('id');
$categoryTree->setParentIdKey('parent_id');
$categoryTree->setResultChildKey('childs');
$categoryTree->setResultParentIdsKey('parent_ids');

$dataTreeList = $categoryTree->getArrayTree();

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


## TODO

Write more docs
Write tests

