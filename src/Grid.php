<?php
namespace App;

use App\Button;
use App\Grid\Column;

class Grid
{
    /**
     * @var Column[]
     */
    private $columns = [];
    /**
     * @var Button[]
     */
    private $buttons = [];
    /**
     * @var array
     */
    private $rows = [];
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $id;

    /**
     * @var bool
     */
    private $useDataTable = true;
    /**
     * @var bool
     */
    private $showPaging = true;
    /**
     * @var bool
     */
    private $showSearch = true;
    /**
     * @var bool
     */
    private $showSorting = true;
    /**
     * @var bool
     */
    private $showPagingAll = true;
    /**
     * @var array
     */
    private $pagingValues = [10, 20, 50, 100];
    /**
     * @var string
     */
    private $emptyMessage = 'There are no records';
    /**
     * @var string
     */
    private $template = 'crud/grid.html.twig';
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Grid constructor.
     * @param \Twig_Environment $twig
     * @param array $options
     */
    public function __construct(
        \Twig_Environment $twig,
        array $options
    ) {
        $this->twig = $twig;
        $fields = [
            'emptyMessage',
            'id',
            'title',
            'useDataTable',
            'showPaging',
            'showSearch',
            'showSorting',
            'showPagingAll',
            'pagingValues',
            'template'
        ];
        foreach ($fields as $field) {
            if (isset($options[$field])) {
                $method = 'set'.ucfirst($field);
                $this->$method($options[$field]);
            }
        }
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getEmptyMessage()
    {
        return $this->emptyMessage;
    }

    /**
     * @param string $emptyMessage
     */
    public function setEmptyMessage($emptyMessage)
    {
        $this->emptyMessage = $emptyMessage;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $key
     * @param Button $button
     */
    public function addButton($key, Button $button)
    {
        $this->buttons[$key] = $button;
    }

    /**
     * @return Button[]
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @param Column $column
     */
    public function addColumn($key, Column $column)
    {
        $this->columns[$key] = $column;
    }

    /**
     * @param $key
     */
    public function removeColumn($key)
    {
        unset($this->columns[$key]);
    }

    /**
     * @param $key
     * @return Column|null
     */
    public function getColumn($key)
    {
        return $this->columns[$key] ?? null;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param array $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    /**
     * @return bool
     */
    public function isUseDataTable()
    {
        return $this->useDataTable;
    }

    /**
     * @param bool $useDataTable
     */
    public function setUseDataTable($useDataTable)
    {
        $this->useDataTable = $useDataTable;
    }

    /**
     * @return bool
     */
    public function isShowPaging()
    {
        return $this->showPaging;
    }

    /**
     * @param bool $showPaging
     */
    public function setShowPaging($showPaging)
    {
        $this->showPaging = $showPaging;
    }

    /**
     * @return bool
     */
    public function isShowSearch()
    {
        return $this->showSearch;
    }

    /**
     * @param bool $showSearch
     */
    public function setShowSearch($showSearch)
    {
        $this->showSearch = $showSearch;
    }

    /**
     * @return bool
     */
    public function isShowSorting()
    {
        return $this->showSorting;
    }

    /**
     * @param bool $showSorting
     */
    public function setShowSorting($showSorting)
    {
        $this->showSorting = $showSorting;
    }

    /**
     * @return bool
     */
    public function isShowPagingAll()
    {
        return $this->showPagingAll;
    }

    /**
     * @param bool $showPagingAll
     */
    public function setShowPagingAll($showPagingAll)
    {
        $this->showPagingAll = $showPagingAll;
    }

    /**
     * @return array
     */
    public function getPagingValues()
    {
        return $this->pagingValues;
    }

    /**
     * @param array $pagingValues
     */
    public function setPagingValues($pagingValues)
    {
        $this->pagingValues = $pagingValues;
    }

    public function render()
    {
        return $this->twig->render($this->getTemplate(), ['grid' => $this ]);
    }

    /**
     * @return string
     */
    public function getDataTableConfig()
    {
        $config = [];
        if (!$this->isShowSorting()) {
            $config['sorting'] = false;
        } else {
            $nonSortable = [];
            $defaultOrderColumn = null;
            $defaultOrder = null;
            $index = 0;
            foreach ($this->getColumns() as $key => $column) {
                if (!$column->isSortable()) {
                    $nonSortable[] = $index;
                }
                if ($column->isDefaultSort()) {
                    $defaultOrderColumn = $index;
                    $defaultOrder = $column->getDefaultSortDir();
                }
                $index++;
            }
            if (count($nonSortable)) {
                $config['columnDefs'] = [
                    ['orderable' => false, 'targets' => $nonSortable]
                ];
            }
            if ($defaultOrderColumn) {
                $config['order'] = [
                    [$defaultOrderColumn, strtolower($defaultOrder)]

                ];
            }
        }
        if (!$this->isShowPaging()) {
            $config['paging'] = false;
        } else {
            $pagingValues = $this->getPagingValues();
            $pagingLabels = $this->getPagingValues();
            if ($this->isShowPagingAll()) {
                $pagingValues[] = -1;
                $pagingLabels[] = 'All';
            }
            $config['lengthMenu'] = [$pagingValues, $pagingLabels];
        }
        if (!$this->isShowSearch()) {
            $config['search'] = false;
        }
        return json_encode($config);
    }
}
