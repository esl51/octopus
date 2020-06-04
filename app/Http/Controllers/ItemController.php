<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

abstract class ItemController extends Controller
{
    /**
     * Item class name
     * @var string
     */
    protected $class;
    /**
     * Resource class name
     * @var string
     */
    protected $resourceClass;
    /**
     * Fillable properties of class
     * @var array
     */
    protected $fillable = [];
    /**
     * Relations for loading
     * @var array
     */
    protected $with = [];
    /**
     * Return translated items
     * @var boolean
     */
    protected $translatable = false;
    /**
     * Return sortable listing
     * @var boolean
     */
    protected $sortable = false;

    /**
     * Get validation rules for new or existing resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer|null  $id
     * @return array
     */
    abstract protected function getValidationRules(Request $request, $id = null);

    /**
     * Unset empty translations.
     *
     * @param array $translations Translations
     * @return array Prepared translations
     */
    public static function prepareTranslations($translations)
    {
        foreach ($translations as $key => $value) {
            if (is_array($value)) {
                $translations[$key] = self::prepareTranslations($translations[$key]);
            }

            if (empty($translations[$key])) {
                unset($translations[$key]);
            }
        }

        return $translations;
    }

    /**
     * Check permissions hook.
     *
     * @param \App\EloquentBuilder $query
     * @return \App\EloquentBuilder
     */
    public function checkPermissions($query)
    {
        return $query;
    }

    /**
     * Get items query.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\EloquentBuilder
     */
    protected function newItemsQuery(Request $request)
    {
        $table = (new $this->class)->getTable();

        // Need to take all columns from table for grouping by them later
        $columns = [];
        foreach (Schema::getColumnListing($table) as $col) {
            $columns[] = $table . '.' . $col;
        }

        $items = call_user_func([$this->class, 'query']);
        $items->select($columns)->groupBy($columns);

        $items = $this->checkPermissions($items);
        $with = $this->with;
        if ($this->translatable) {
            $with[] = 'translations';
            //$items = call_user_func([$items, 'withTranslation']);
        }
        if (!empty($with)) {
            $items->with($with);
        }
        if (!empty($this->sortable)) {
            $items->sorted();
        }

        $search = filter_var($request->search, FILTER_SANITIZE_STRING);
        if (is_numeric($search)) {
            $items->where('id', $search);
        }

        $sortBy = filter_var($request->sort_by, FILTER_SANITIZE_STRING);
        $sortDesc = (bool) $request->sort_desc;
        if ($sortBy) {
            $items->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');
        }

        return $items;
    }

    /**
     * Get item.
     *
     * @param  integer  $id
     * @param  boolean  $withRelations
     * @return mixed
     */
    public function getItem($id, $withRelations = false)
    {
        $table = (new $this->class)->getTable();

        // Need to take all columns from table for grouping by them later
        $columns = [];
        foreach (Schema::getColumnListing($table) as $col) {
            $columns[] = $table . '.' . $col;
        }
        $item = call_user_func([$this->class, 'query']);
        $item->select($columns)->groupBy($columns);

        $item = $this->checkPermissions($item);
        if ($withRelations) {
            $with = $this->with;
            // load all translations for modifying purposes
            if ($this->translatable) {
                $with[] = 'translations';
            }
            if (!empty($with)) {
                $item = $item->with($with);
            }
        }

        return $item->find($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return call_user_func([$this->resourceClass, 'collection'], $this->newItemsQuery($request)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = $this->getValidationRules($request);
        $this->validate($request, $rules);
        $data = $request->only($this->fillable);
        if ($this->translatable) {
            $data = array_merge($data, self::prepareTranslations($request->translations));
        }
        $item = call_user_func([$this->class, 'create'], $data);
        return $this->show($item->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = $this->getItem($id, true);
        return new $this->resourceClass($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = $this->getValidationRules($request, $id);
        $this->validate($request, $rules);
        $item = $this->getItem($id);
        $data = $request->only($this->fillable);
        if ($this->translatable) {
            $data = array_merge($data, self::prepareTranslations($request->translations));
            $item->deleteTranslations();
        }
        $item->update($data);
        return $this->show($item->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = call_user_func([$this->class, 'find'], $id);
        $item->delete();
        return response()->json(null, 204);
    }

    /**
     * Move the specified resource before another.
     *
     * @param  integer  $id
     * @param  integer  $before
     * @return  \Illuminate\Http\Response
     */
    public function moveBefore($id, $before)
    {
        $item = $this->getItem($id);
        $itemBefore = $this->getItem($before);
        $item->moveBefore($itemBefore);
        return $this->show($item->id);
    }

    /**
     * Move the specified resource after another.
     *
     * @param  integer  $id
     * @param  integer  $after
     * @return  \Illuminate\Http\Response
     */
    public function moveAfter($id, $after)
    {
        $item = $this->getItem($id);
        $itemAfter = $this->getItem($after);
        $item->moveAfter($itemAfter);
        return $this->show($item->id);
    }
}
