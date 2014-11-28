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

/*
 * Node
 * -----------------------------------------------------------------------------
 * Simple node structure.
 * 
 */

class Node
{
    /**
     * Auto-generated id
     *
     * @var string
     */
    public $id;
    
    /**
     * Data array that this node carring
     *
     * @var array
     */
    public $data;
    
    /**
     * This node's parent node
     *
     * @var \ArrayTree\Node
     */
    protected $_parentNode;
    
    /**
     * Child nodes array
     *
     * @var array
     */
    protected $_childNodes = array();
    
    
    /**
     * Constructor
     *
     * @return object
     */
    public function __construct($data, $id = false)
    {
        $this->data = $data;
        $this->id = $id ? $id : uniqid('array_tree', true);
        
        return $this;
    }
    
    /**
     * Attach this node to another node
     *
     * @return object
     */
    public function attachTo($parentNode)
    {
        $parentNode->appendChild($this);
    }
    
    /**
     * Set this node's parent node
     *
     * @return object
     */
    public function setParentNode($parentNode)
    {
        $this->_parentNode = $parentNode;
    }
    
    /**
     * Append an child node to this node
     *
     * @return void
     */
    public function appendChild($childNode)
    {
        $this->_childNodes[$childNode->id] = $childNode;
        $childNode->setParentNode($this);
    }
    
    /**
     * Detach this node from it's parent node
     *
     * @return void
     */
    public function detach()
    {
        $this->_parentNode->removeChild($this);
    }
    
    /**
     * Remove an child node
     *
     * @return void
     */
    public function removeChild($childNode)
    {
        unset($this->_childNodes[$childNode->id]);
        $childNode->setParentNode(null);
    }
    
    /**
     * Get all child nodes
     *
     * @return array
     */
    public function getAllChildNode()
    {
        return $this->_childNodes;
    }
    
    /**
     * Get all child nodes
     *
     * @return \ArrayTree\Node | bool
     */
    public function getParentNode()
    {
        return $this->_parentNode ? $this->_parentNode : false;
    }
}
