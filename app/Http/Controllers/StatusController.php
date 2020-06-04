<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatusResource;
use App\Status;
use Illuminate\Http\Request;

class StatusController extends ItemController
{
    protected $class = Status::class;
    protected $resourceClass = StatusResource::class;
    protected $fillable = [
        'variant',
        'is_published',
        'is_default',
    ];
    protected $translatable = true;

    /**
     * @inheritdoc
     */
    public function getValidationRules(Request $request, $id = null)
    {
        return [
            'variant' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'is_default' => 'boolean',
            'translations' => 'nullable|array',
            'translations.*' => 'nullable|array',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.' . config('translatable.fallback_locale') . '.name' => 'required|string|max:255',
        ];
    }

    /**
     * @inheritdoc
     */
    public function newItemsQuery(Request $request)
    {
        $items = parent::newItemsQuery($request);

        $items->join('status_translations', 'status_translations.status_id', '=', 'statuses.id');

        $search = filter_var($request->search, FILTER_SANITIZE_STRING);
        if ($search) {
            $items->orWhereTranslationLike('name', '%' . $search . '%');
        }

        return $items;
    }
}
