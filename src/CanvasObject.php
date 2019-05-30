<?php

namespace Canvas;

class CanvasObject
{
    protected $_opts;
    protected $_originalValues;
    protected $_values;
    protected $_unsavedValues;
    protected $_transientValues;
    protected $_retrieveOptions;
    protected $_lastResponse;

    public function __construct($id = null, $opts = null)
    {
        list($id, $this->_retrieveOptions) = Util\Util::normalizeId($id);
        $this->_opts = Util\RequestOptions::parse($opts);
        $this->_originalValues = [];
        $this->_values = [];
        $this->_unsavedValues = new Util\Set();
        $this->_transientValues = new Util\Set();
        if ($id !== null) {
            $this->_values['id'] = $id;
        }
    }

}
