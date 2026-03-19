<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        IGrid.php
    VERSION:     See BaseController.php
    DESCRIPTION: CSS Grid abstraction within Ignition
    COPYRIGHT:   2022
    FIRST REV:   14 Dec 2023
    LICENSE:     MIT

*********************************************************************/

// ----- Used to build CSS grid.  See 
//       examples under /app/??? and its implementation within Ignition
class IGrid
{
    protected $model;
    protected $colOpen = false;
    protected $rowOpen = false;
	protected $fieldList;
    public $data;

    // ----- constructor
    public function __construct($model = '', $data = '')
    {
		/// this is provisional until elim data (already stored in model)
		if ($data == '' && $model != '')
			$this->data = $model->data;
		else
			$this->data = $data;

        $this->model = $model;

    }

    // ----- create open tag
    public function IGridCreate($action = '')
    {
		echo '<style>';
		echo '.gridContainer {';
		echo '	display: flex';

	}

    // ----- create open tag
    public function IGridOpen($action = '')
    {

        if ($action === '')
            $action = fixed_url();

        echo form_open($action);
    }

    // ----- create open tag
    public function IGridClose()
    {
        echo '';

        // ----- check for open state of col
        if ($this->colOpen)
            $this->IColumnClose();

        // ----- check for open state of row 
        if ($this->rowOpen)
            echo '';
    }

    // ----- manually close a column (normally is done automatically in form close)
    public function IColumnClose()
    {
        if ($this->colOpen) {
            echo '';
            $this->colOpen = false;
        }
    }

    // ----- manually close a row (normally is done automatically in form close)
    public function IRowClose()
    {
        if ($this->colOpen) {
            echo '';
            $this->colOpen = false;
        }

        if ($this->rowOpen) {
            echo '';
            $this->rowOpen = false;
        }
    }

    // ----- create new IForm column
    public function IColumn($title = "", $wide = false)
    {

        // ----- if already col open, then close it 
        if ($this->colOpen)
            echo '';

        // ----- check for row open status
        if (!$this->rowOpen) {
            echo '';
            $this->rowOpen = true;
        }

        // ----- create column
        if ($title != "") {
            // ----- outer div container wide, or narrow
            if ($wide)
                echo '';
            else
                echo '';

            echo '';
            echo $title . '';
        } else {
            // ----- create basic column

            // ----- outer div container wide, or narrow
            if ($wide)
                echo '';
            else
                echo '';

            echo '';
        }

        $this->colOpen = true;
    }


}


?>
