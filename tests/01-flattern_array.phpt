--TEST--
Basic usage
--FILE--
<?php
include_once(__DIR__ . '/bootstrap.php');

class TreeTest extends \ArrayTree\Tree
{
    public function testFlattenArray($array)
    {
        return self::FlattenArray($array);
    }
}

$nestedArray = array(
    1,
    array(
        2,
        array(
            3
        )
    )
);

$testTree = new TreeTest();
print_r($testTree->testFlattenArray($nestedArray));

?>
--EXPECTF--
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
)
