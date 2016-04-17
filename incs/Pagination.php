<?php 

class Pagination
{
	public $current_page;
	public $per_page;
	public $total_count;

	public function __construct($current_page=1, $per_page=20, $total_count=0)
	{
		$this->current_page = (int)$current_page;
		$this->per_page     = (int)$per_page;
		$this->total_count  = (int)$total_count; 
	}

	public function offset()
	{
		return (int)($this->current_page - 1) * $this->per_page;
	}

	public function total_pages()
	{
		return ceil($this->total_count / $this->per_page);
	}

	public function next_page()
	{
		return $this->current_page + 1;
	}

	public function previous_page()
	{
		return $this->current_page - 1;
	}

	public function hasNextPage()
	{
		return $this->next_page() <= $this->total_pages() ? true : false;
	}

	public function hasPreviousPage()
	{
		return $this->previous_page() >= 1 ? true : false;
	}



}