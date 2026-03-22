<?php

/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        IIndex.php
    VERSION:     See BaseController.php
    DESCRIPTION: Displays a list view of itmes wihtin the Ignition 
                 Index/Form paradigm
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- form helper class 
class IIndex
{
    protected $columns = [];
	protected $autoForm;

    // ----- constructor
    public function __construct($autoForm = FALSE)
    {
        $this->autoForm = $autoForm;

    }

    // ----- build and render index page containing header and rows
    //       params[] contains a built list of two major items: the
    //       header and the rows; $tableHeader and $tableRows.  See 
    //       examples under Site/Channels for the application of CI 
    //       MVC and its implementation within Ignition.  Style set 
    //       in header determines display style (button or text)
    //       Often this class will be implemented and called from 
    //       CI view located under directory:
    //      /app/Site/Channels/<MyChannel>/Views/index.php
    public function render(array $params = [])
    {

        // ----- init
        $this->columns = $params['tableHeader'];

        $columnCount = count($this->columns);

        // ----- check coluns headers match

        // ----- begin table
        echo '<div><table class="table-data3"><thead style="background-color: #' . $GLOBALS['ADMINMENUCOLOR'] . '"><tr>';

        // ----- setup loop to render header cells
        foreach ($this->columns as $colCell) {

			// ----- get type 
			if (is_array($colCell['headerCell'])) {
				$cellLabel = $colCell['headerCell'];
				$cellLabel = $cellLabel['label'];
			} else {
				$cellLabel = $colCell['headerCell'];
			}

            echo '<th>' . $cellLabel . '</th>';
        }

        // ----- close out header
        echo '</tr></thead><tbody>';

        // ----- convert anonymous closure function to array
        $tableRows = $params['tableRows'];
        $tableRows = $tableRows->bindTo($this);		// this is generator function, magic of yield

        // ----- output rows
        foreach ($tableRows() as $key => $row) {

            // ----- init row
            echo '<tr>';
            $colStep = 1;

            // ----- check for autoform, must send as one array (shed outer array)
            if ($this->autoForm)
                $row = $row[0];

            // ----- setup loop to display all columns for a row
            foreach ($row as $cell)
            {
                // ----- check for column count exceeded
                if ($colStep > $columnCount) {
                    echo "Column count exceeded in index render call.  Program error.";
                    return;
                }

                echo $this->RowCell($cell, $colStep - 1);

               // ----- increment 
                $colStep += 1;

            }


            // ----- end row
            echo '</tr>';

        }

        // ----- close out table 
        echo '</tbody></table></div>';

    }

    // ----- analyze entry and return html for td cell 
    protected function RowCell($cellData, $os)
    {
        // ----- init
        $retVal = '';
        $type = $this->columns[$os]['type'];
        $style = $this->columns[$os]['style'] ?? "";

        // ----- check for array entry 
        if (is_array($cellData)) {
            if ($type == "buttonEdit") {
                $retVal .= '<td style="width: 1%; white-space: nowrap"><a title="' . lang('base.edit') . '" href="';
                $retVal .= $cellData['url'] . '"' . (isset($cellData['style']) ? ' style="' . $cellData['style'] . '"': '') . '><i class="fa fa-edit"></i></a></td>';
            } elseif ($type == "link") {
                $retVal .= '<td style="width: 1%; white-space: nowrap"><a title="' . lang('base.account') . '" href="';
                $retVal .= $cellData['url'] . '" class="btn&#x20;btn-primary"' . (isset($cellData['style']) ? ' style="' . $cellData['style'] . '"': '') . '>' . $cellData['displayValue'] . '</a></td>';
            } elseif ($type == "image") {
                $retVal .= '<td style="width: 1%; white-space: nowrap"><img src="';
                $retVal .= $cellData['imageURL'] . '"></td>';
            } elseif ($type == "radio") {
                $retVal .= '<td style="width: 1%; white-space: nowrap">' . $cellData['radioVal'] . '</td>';
            } elseif ($type == "buttonAccount") {
                $retVal .= '<td style="width: 1%; white-space: nowrap"><a title="' . lang('base.account') . '" href="';
                $retVal .= $cellData['url'] . '"><i class="fa fa-newspaper"></i></a></td>';
            } elseif ($type == "buttonDelete") {
                $retVal .= '<td style="width: 1%; white-space: nowrap"><a title="' . lang('base.delete') . '" href="';
                $retVal .= $cellData['url'] . '"><i class="fa fa-times-circle"></i></a></td>';
            } elseif ($type == "buttonView") {
                $retVal .= '<td style="width: 1%; white-space: nowrap"><a title="' . lang('base.view') . '" href="';
                $retVal .= $cellData['url'] . '"><i class="fa fa-newspaper"></i></a></td>';
            }

        } else {
            $retVal .= '<td class="process" style="' . $style . '">' . $cellData . '</td>';
        }

        return $retVal;
    }

    public function start() {
        echo '<div>';
    }

    public function end() {
        echo '</div>';
    }

    // ----- create open tag
    public function IIndexMessages($errors = "")
    {

        // ----- check for form errors
        if (!empty($errors)) {
            echo '<div class="alert alert-danger">';

            // ----- setup loop to display all errors
            foreach ($errors as $field => $error)
                echo $error;

            echo '</div>';
        }

        $session = service('session');
        if ($session->has('success')) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }

    }

}


?>