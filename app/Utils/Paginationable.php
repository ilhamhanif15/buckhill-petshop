<?php 

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class Paginationable
{
    /**
     * Model Related for pagination
     *
     * @var Model
     */
    protected $model;

    /**
     * Request that get from Client
     *
     * @var Request
     */
    protected $request;

    /**
     * Default Per Page Fetched Data
     *
     * @var integer
     */
    protected $per_page = 15;

    /**
     * Default params name for searching
     *
     * @var string
     */
    protected $default_search_param_key = "q";

    /**
     * Searchable columns from related Model
     *
     * @var array $searchable_cols
     */
    protected $searchable_cols = [];

    /**
     * With Relations
     *
     * @var array
     */
    protected $with_relations = [];

    /**
     * Pagination Order Column (default is created_at)
     *
     * @var string
     */
    protected $pagination_order_col = null;

    /**
     * Pagination Order By (default is desc)
     *
     * @var string
     */
    protected $pagination_order_by = null;

    /**
     * COnstructor
     *
     * @param Model|Builder|QueryBuilder $model
     * @param Request $request
     */
    public function __construct($model, Request $request)
    {
        $this->model = $model;
        $this->setRequest($request);

        // Init By Request
        $this->per_page             = $this->request->input("per_page", $this->per_page);
        $this->pagination_order_col = $this->request->input("order_col", $this->pagination_order_col);
        $this->pagination_order_by  = $this->request->input("order_by", $this->pagination_order_by);
    }

    /**
     * Create Paginate by Current Model
     *
     * @return Paginator
     */
    public function paginate()
    {
        $this->setBulkSearch()
            ->setOrderBy();

        return $this->model->paginate($this->per_page)->withQueryString();
    }

    /**
     * Get Current Model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set Request
     *
     * @param Request $request
     * @return self
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Set Order by column name
     *
     * @param string $col_name
     * @return self
     */
    public function setPaginationOrderCol(string $col_name)
    {
        $this->pagination_order_col = $col_name;
        return $this;
    }

    /**
     * Set Order By Ascending/Descending
     *
     * @param string $order_by (asc or desc)
     * @return self
     */
    public function setPaginationOrderBy(string $order_by)
    {
        $this->pagination_order_by = $order_by;
        return $this;
    }

    /**
     * Set searchable_cols for searching through columns
     *
     * @param array $searchable_cols
     * @return self
     */
    public function setSearchableColumns(array $searchable_cols = [])
    {
        $this->searchable_cols = $searchable_cols;
        return $this;
    }

    /**
     * Set Item Per Page
     *
     * @param integer $value
     * @return self
     */
    public function setPerPage(int $value)
    {
        $this->per_page = $value;
        return $this;
    }

    /**
     * Search through multiple columns that defined from searchable_cols
     *
     * @return self
     */
    public function setBulkSearch()
    {
        $search_text = $this->request->{$this->default_search_param_key};

        // If The request query not found
        if (!$search_text) {
            return $this;
        }

        $this->model = $this->model->where( function($query) use($search_text) {
            foreach ($this->searchable_cols as $col) {
                $query = $this->_searchQueryBySearchableCol($col, $search_text, $query);
                // $query = $query->orWhere($col, 'ILIKE', '%'.$search_text.'%');
            }
        });

        return $this;
    }

    /**
     * Search by searchable Col
     *
     * @param string $searchable_col
     * @param string $search_text
     * @param QueryBuilder|Builder $query
     * @return QueryBuilder|Builder
     */
    private function _searchQueryBySearchableCol($searchable_col, $search_text, $query)
    {
        $exploded_relations = explode(".", $searchable_col);

        if (count($exploded_relations) === 1) 
        {
            // $query = $query->orWhere($searchable_col, 'ILIKE', '%'.$search_text.'%');
            
            $search_text = Str::lower($search_text);
            $query = $query->orWhere( DB::raw("lower({$searchable_col})"), 'LIKE', '%'.$search_text.'%');
        }
        else if (count($exploded_relations) > 1)
        {
            $col = array_pop($exploded_relations);
            $query = $this->_recursiveQuerySearch($query, $col, $search_text, $exploded_relations);
        }

        return $query;
    }

    /**
     * Recursively using whereHas so it can search through relations
     *
     * @param QueryBuilder|Builder $query
     * @param string $searchable_col
     * @param string $search_text
     * @param array $relations
     * @return QueryBuilder|Builder
     */
    private function _recursiveQuerySearch($query, $searchable_col, $search_text, $relations)
    {
        if (empty($relations)) 
        {
            $cols = explode(",", $searchable_col);

            foreach ($cols as $col) {
                $query = $query->orWhere($col, 'ILIKE', '%'.$search_text.'%');
            }
        }
        else
        {
            $relation   = array_shift($relations);
            $query      = $query->whereHas($relation, function($q) use($searchable_col, $search_text, $relations) {
                $q->where( function($queryTemp) use($searchable_col, $search_text, $relations) {
                    return $this->_recursiveQuerySearch($queryTemp, $searchable_col, $search_text, $relations);
                });
            });
        }

        return $query;
    }

    /**
     * Set ordering for pagination
     * 
     * @return self
     */
    public function setOrderBy()
    {
        if ($this->pagination_order_by && $this->pagination_order_col) {
            $this->model = $this->model->orderBy($this->pagination_order_col, $this->pagination_order_by);
        }

        return $this;
    }
}