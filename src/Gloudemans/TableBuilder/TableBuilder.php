<?php namespace Gloudemans\TableBuilder;

class TableBuilder {

	/**
	 * Holds the table html string
	 *
	 * @var string
	 */
	protected $table = '';

	/**
	 * Holds the supplied data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Holds the table attributes
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Holds the table headers
	 *
	 * @var array
	 */
	protected $headers;

	/**
	 * Holds custom headers if they are passed
	 *
	 * @var array
	 */
	protected $customHeaders;

	/**
	 * Return the build table
	 *
	 * @param  array    $data
	 * @param  array    $attributes
	 * @param  array 	$headers
	 * @return string
	 */
	public function generate($data, $attributes = null, $headers = null)
	{
		$this->attributes = $attributes;
		$this->customHeaders = $headers;

		$this->prepareDataAndHeaders($data);

		$this->generateTable();

		return $this->table;
	}

	/**
	 * Generate the table
	 *
	 * @return void
	 */
	protected function generateTable()
	{
		$this->generateTableHeader();

		$this->generateTableBody();

		$this->generateTableFooter();
	}

	/**
	 * Prepare the data and fetch the headers
	 *
	 * @param  array   $data
	 * @return void
	 */
	protected function prepareDataAndHeaders($data)
	{
		$this->data = $data;

		$this->transformData();

		$this->prepareHeaders();
	}

	/**
	 * Transform the data if it's not an array
	 *
	 * @return void
	 */
	protected function transformData()
	{
		$firstRow = $this->data[0];

		if($firstRow instanceof \Illuminate\Support\Collection)
		{
			$this->transformDataCollections();
			$firstRow = $this->data[0];
		}
		else if(is_object($firstRow))
		{
			$this->transformDataObjects();
			$firstRow = $this->data[0];
		}
	}

	/**
	 * Fetch the headers
	 *
	 * @return void
	 */
	protected function prepareHeaders()
	{
		$firstRow = $this->data[0];

		$this->headers = array_combine(array_keys($firstRow), array_keys($firstRow));

		if( ! is_null($this->customHeaders))
		{
			$this->substituteCustomHeaders();
		}
	}

	protected function substituteCustomHeaders()
	{
		foreach($this->headers as $key => $header)
		{
			if(isset($this->customHeaders[$key]))
			{
				$this->headers[$key] = $this->customHeaders[$key];
			}
		}
	}

	/**
	 * Transform the data if it is an array of objects
	 *
	 * @return void
	 */
	protected function transformDataObjects()
	{
		$dataObjects = $this->data;

		foreach($dataObjects as $key => $row)
		{
			$this->data[$key] = (array) $row;
		}
	}

	/**
	 * Transform the data if it is an array of collections
	 *
	 * @return void
	 */
	protected function transformDataCollections()
	{
		$dataCollections = $this->data;

		foreach($dataCollections as $key => $row)
		{
			$this->data[$key] = $row->toArray();
		}
	}

	/**
	 * Generate the table header
	 *
	 * @return void
	 */
	protected function generateTableHeader()
	{
		$attributes = $this->fetchAttributes();

		$this->table .= '<table' . $attributes . '>';

		$this->generateTableHeaderRow();
	}

	/**
	 * Fetch the attributes and build an attribute string
	 *
	 * @return string
	 */
	protected function fetchAttributes()
	{
		if(is_null($this->attributes) || empty($this->attributes)) return '';

		$attributes = ' ';

		foreach($this->attributes as $key => $value)
		{
			$attributes .= $key . '="' . $value . '" ';
		}

		return rtrim($attributes);
	}

	/**
	 * Generate the table header row
	 *
	 * @return void
	 */
	protected function generateTableHeaderRow()
	{
		$this->table .= '<thead><tr>';

		$this->generateTableHeaderRowCells();

		$this->table .= '</tr></thead>';
	}

	/**
	 * Generate the table header row cells
	 *
	 * @return void
	 */
	protected function generateTableHeaderRowCells()
	{
		foreach($this->headers as $header)
		{
			$this->table .= '<th>' . $header . '</th>';
		}
	}

	/**
	 * Generate the table body
	 *
	 * @return void
	 */
	protected function generateTableBody()
	{
		$this->table .= '<tbody>';

		$this->generateTableBodyRows();

		$this->table .= '</tbody>';
	}

	/**
	 * Generate the table body rows
	 *
	 * @return void
	 */
	protected function generateTableBodyRows()
	{
		foreach($this->data as $row)
		{
			$this->table .= '<tr>';

			$this->generateTableBodyRowCells($row);

			$this->table .= '</tr>';
		}
	}

	/**
	 * Generate the table body row cells
	 *
	 * @param  array   $row
	 * @return void
	 */
	protected function generateTableBodyRowCells($row)
	{
		foreach($row as $cell)
		{
			$this->table .= '<td>' . $cell . '</td>';
		}
	}

	/**
	 * Generate the table footer
	 *
	 * @return void
	 */
	protected function generateTableFooter()
	{
		$this->table .= '</table>';
	}

}