<?php
namespace ArrayTree;

/**
 * ArrayTree
 * 
 * Convert 2-dimensions array to parent-child relation array
 * 
 * Copyright 2014  Aaron Jan <https://github.com/aaronjan>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @version 0.9
 * @author  Aaron Jan <https://github.com/AaronJan/ArrayTree>
 * @link    https://github.com/aaronjan
 * @license http://www.apache.org/licenses/LICENSE-2.0 The Apache License V2
 */

class Tree
{
    /**
     * Data that use for generate array tree
     *
     * @var array
     */
    protected $_dataList = array();
    
    /**
     * "Id" key name for every data entry
     *
     * @var string
     */
    protected $_idKey    = 'id';
    
    /**
     * "Parent id" key name for every data entry
     *
     * @var string
     */
    protected $_parentIdKey = 'parent_id';
    
    /**
     * "Child entries" key name for store child entries
     *
     * @var string
     */
    protected $_resultChildKey = 'childs';
    
    /**
     * Key name for store this node's parent nodes' ids, set to "false" to disable this function
     *
     * @var string/bool
     */
    protected $_resultParentIdsKey = 'parent_ids';
    
    /**
     * Whether or not the tree has been builded
     *
     * @var bool
     */
    protected $_builded   = false;
    
    /**
     * Tree must have an root node (only one), store root node's id key here
     *
     * @var mixed
     */
    protected $_rootId    = false;
    
    /**
     * Nodes array
     *
     * @var array
     */
    protected $_nodes      = array();
    
    /**
     * Constructor
     *
     * @return object
     */
    public function __construct(array $dataList = array())
    {
        if (!empty($dataList)) {
            $this->setData($dataList);
        }
        
        return $this;
    }
    
    /**
     * Specify key name for data array's id attribute
     *
     * @return void
     */
    public function setIdKey($idKey)
    {
        $this->_markChange();
        $this->_idKey = $idKey;
    }
    
    /**
     * Specify key name for data array's parent id attribute
     *
     * @return void
     */
    public function setParentIdKey($parentKey)
    {
        $this->_markChange();
        $this->_parentIdKey = $parentKey;
    }
    
    /**
     * Specify key name of result for store child arrays
     *
     * @return void
     */
    public function setResultChildKey($childKey)
    {
        $this->_markChange();
        $this->_resultChildKey = $childKey;
    }
    
    /**
     * Specify key name of result for store parent nodes' ids
     *
     * @return void
     */
    public function setResultParentIdsKey($parentIdsKey)
    {
        $this->_markChange();
        $this->_resultParentIdsKey = $parentIdsKey;
    }
    
    /**
     * Set data array
     *
     * @return void
     */
    public function setData(array $dataList)
    {
        $this->_markChange();
        $this->_dataList = $dataList;
    }
    
    /**
     * Add a data entry to data array
     *
     * @return void
     */
    public function addData(array $dataEntry)
    {
        $this->_markChange();
        $this->_dataList[] = $dataEntry;
    }
    
    /**
     * Convert data to nodes, then converting nodes to array-tree-style nested array
     *
     * @return array
     */
    public function getArrayTree()
    {
        if (!$this->_builded) {
            $this->buildTree();
        }
        
        $arrayTree = $this->_getAllNodeDataRecursively($this->_nodes[$this->_rootId]);
        return $arrayTree[$this->_resultChildKey];
    }
    
    /**
     * Convert data to nodes, then converting nodes to array.
     * If you only want the "parent ids" results and a 2-d array, you can use this.
     *
     * @return array
     */
    public function getArray()
    {
        if (!$this->_builded) {
            $this->buildTree();
        }
        
        $nodeDataArray = array();
        $childIds      = array_diff(array_keys($this->_nodes), array($this->_rootId));
        foreach ($childIds as $eachChildId) {
            $nodeDataArray[] = $this->_nodes[$eachChildId]->data;
        }
        
        return $nodeDataArray;
    }
    
    /**
     * Convert data arrays to organized nodes
     *
     * @return boolean
     */
    public function buildTree()
    {
        // reunion dataArray buy "id" value, and get all "parent ids".
        $reunionedList = array();
        $parentIds     = array();
        foreach ($this->_dataList as $eachData) {
            $parentId                                = $eachData[$this->_parentIdKey];
            $parentIds[]                             = $parentId;
            $reunionedList[$eachData[$this->_idKey]] = $eachData;
        }
        $parentIds = array_unique($parentIds);
        // get root node id (only one)
        $rootIds   = array_diff($parentIds, array_keys($reunionedList));
        if (count($rootIds) != 1) {
            throw new \Exception('Can only have one root node.');
        }
        $this->_rootId = array_pop($rootIds);
        // convert data to node objects
        $this->_generateNode($reunionedList);
        $this->_organizeNode();

        return true;
    }
    
    /**
     * Generate node objects using reunioned array
     *
     * @return boolean
     */
    protected function _generateNode($reunionedList)
    {
        $this->_nodes = array();
        $this->_nodes[$this->_rootId] = new Node(array(), $this->_rootId);
        foreach ($reunionedList as $key => $eachData) {
            $this->_nodes[$key] = new Node($eachData, $key);
        }
        
        return true;
    }
    
    /**
     * Organize node objects, make connections
     *
     * @return boolean
     */
    protected function _organizeNode()
    {
        //Get all child nodes' keys except root node
        $childIds = array_diff(array_keys($this->_nodes), array($this->_rootId));
        
        //Connect all nodes
        foreach ($childIds as $eachId) {
            $childNode  = $this->_nodes[$eachId];
            $parentId   = $childNode->data[$this->_parentIdKey];
            $parentNode = $this->_nodes[$parentId];
            $childNode->attachTo($parentNode);
        }
        //Setting all nodes' parent ids
        if ($this->_resultParentIdsKey) {
            $rootNodeId = $this->_rootId;
            foreach ($childIds as $eachId) {
                $childNode                                   = $this->_nodes[$eachId];
                $parentIds                                   = $this->_getNodeParentIdRecursively($childNode, $rootNodeId);
                $childNode->data[$this->_resultParentIdsKey] = $parentIds;
            }
        }
        
        return true;
    }
    
    /**
     * Get target node's array tree (recursively)
     *
     * @return array
     */
    protected function _getNodeParentIdRecursively($node, $rootNodeId, $callOnDemand = true)
    {
        $returnValue = array();
        
        $parentNode = $node->getParentNode();
        if ($parentNode === false) {
            throw new \Exception('Oops, seems there\'s one node hasn\'t parent node, and it isn\'t root node.');
        } elseif ($parentNode->id == $rootNodeId) {
            return array();
        }
        
        $returnValue[] = $parentNode->id;
        $returnValue[] = $this->_getNodeParentIdRecursively($parentNode, $rootNodeId, false);
        
        if ($callOnDemand) {
            $returnValue = self::FlattenArray($returnValue);
        }
        
        return $returnValue;
    }
    
    /**
     * Get target node's array tree (recursively)
     *
     * @return array
     */
    protected function _getAllNodeDataRecursively($treeNode)
    {
        $returnArr = $treeNode->data;
        $returnArr[$this->_resultChildKey] = array();
        $childNodeList = $treeNode->getAllChildNode();
        
        if (!empty($childNodeList)) {
            foreach ($childNodeList as $eachChildNode) {
                $returnArr[$this->_resultChildKey][] = $this->_getAllNodeDataRecursively($eachChildNode);
            }
        }
        
        return $returnArr;
    }
    
    /**
     * Flat an array (recursively)
     *
     * @return array
     */
    protected static function FlattenArray($array)
    {
        $returnArray = array();
        $flattenFunc = function ($item, $key) use(&$returnArray) {
            if (!is_array($item)) {
                $returnArray[] = $item;
            }
        };
        array_walk_recursive($array, $flattenFunc);
        
        return $returnArray;
    }
    
    /**
     * Mark changes for regenerate nodes
     *
     * @return void
     */
    protected function _markChange()
    {
        $this->_builded = false;
    }
    
}
