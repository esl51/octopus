<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ItemController;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Rules\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends ItemController
{
    protected $class = Page::class;
    protected $resourceClass = PageResource::class;
    protected $fillable = [
        'status_id',
    ];
    protected $with = [
        'author',
        'status',
    ];
    protected $translatable = true;

    /**
     * @inheritdoc
     */
    public function getValidationRules(Request $request, $id = null)
    {
        return [
            'author_id' => 'nullable|exists:users,id',
            'status_id' => 'required|exists:statuses,id',
            'translations' => 'nullable|array',
            'translations.*' => 'nullable|array',
            'translations.*.slug' => ['required', 'max:255', new Slug],
            'translations.*.title' => 'nullable|string|max:255',
            'translations.' . config('translatable.fallback_locale') . '.title' => 'required|string|max:255',
            'translations.*.headline' => 'nullable|string|max:255',
            'translations.*.body' => 'nullable|string',
            'translations.*.meta_title' => 'nullable|string|max:255',
            'translations.*.meta_description' => 'nullable|string|max:255',
            'translations.*.meta_keywords' => 'nullable|string|max:255',
        ];
    }

    /**
     * @inheritdoc
     */
    public function newItemsQuery(Request $request)
    {
        $items = parent::newItemsQuery($request);

        $items->join('page_translations', 'page_translations.page_id', '=', 'pages.id');

        $search = filter_var($request->search, FILTER_SANITIZE_STRING);
        if ($search) {
            $items->orWhereTranslationLike('slug', '%' . $search . '%');
            $items->orWhereTranslationLike('title', '%' . $search . '%');
            $items->orWhereTranslationLike('headline', '%' . $search . '%');
            $items->orWhereTranslationLike('body', '%' . $search . '%');
            $items->orWhereTranslationLike('meta_title', '%' . $search . '%');
            $items->orWhereTranslationLike('meta_description', '%' . $search . '%');
            $items->orWhereTranslationLike('meta_keywords', '%' . $search . '%');
        }

        return $items;
    }

    /**
     * Generate slug.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function slug(Request $request)
    {
        return response()->json([
            'slug' => Str::slug($request->title),
        ]);
    }

    public function render(Request $request)
    {
        $slug = $request->slug ?: 'home';
        $page = Page::published()
            ->whereTranslation('slug', $slug, app()->getLocale())
            ->first();
        if ($page) {
            $pages = Page::published()->get();
            return view('page', [
                'page' => $page,
                'pages' => $pages,
            ]);
        } else {
            return abort(404);
        }
    }
}
