<?php

namespace App\Http\Controllers\Voyager;

use App\Contracts\Model;
use App\Models\App;
use App\Models\News;
use App\Models\User;
use App\Repositories\GeneralInfoRepository;
use App\Repositories\WorkingHoursRepository;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;

class VoyagerNewsController extends VoyagerBaseController
{
    protected $generalInfoRepository;

    protected $workingHoursRepository;

    public function __construct(
        GeneralInfoRepository $generalInfoRepository,
        WorkingHoursRepository $workingHoursRepository
    ) {
        $this->generalInfoRepository = $generalInfoRepository;
        $this->workingHoursRepository = $workingHoursRepository;
    }

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object)[
            'value'  => $request->get('s'),
            'key'    => $request->get('key'),
            'filter' => $request->get('filter')
        ];
        $searchable = $dataType->server_side ? array_keys(
            SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray()
        ) : '';
        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', null);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + 1;
            $orderColumn = [[$index, 'desc']];
            if (!$sortOrder && isset($dataType->order_direction)) {
                $sortOrder = $dataType->order_direction;
                $orderColumn = [[$index, $dataType->order_direction]];
            } else {
                $orderColumn = [[$index, 'desc']];
            }
        }

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            if ($dataType->scope && $dataType->scope != '' && method_exists(
                    $model,
                    'scope' . ucfirst($dataType->scope)
                )) {
                $query = $model->{$dataType->scope}();
            } else {
                $query = $model::select('*');
            }

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses($model)) && app('VoyagerAuth')->user()->can(
                    'delete',
                    app($dataType->model_name)
                )) {
                $usesSoftDeletes = true;

                if ($request->get('showSoftDeleted')) {
                    $showSoftDeleted = true;
                    $query = $query->withTrashed();
                }
            }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%' . $search->value . '%';
                $query->where($search->key, $search_filter, $search_value);
            }

            if ($orderBy && in_array($orderBy, $dataType->fields())) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                $dataTypeContent = call_user_func(
                    [
                        $query->orderBy($orderBy, $querySortOrder),
                        $getter,
                    ]
                );
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        if (($isModelTranslatable = is_bread_translatable($model))) {
            $dataTypeContent->load('translations');
        }

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        if (auth()->id() != User::SuperAdminId) {
            $dataTypeContent = $dataTypeContent->filter(
                function ($value, $key) {
                    return $value->app_id == auth()->user()->app_id;
                }
            );
        }

        return Voyager::view(
            $view,
            compact(
                'dataType',
                'dataTypeContent',
                'isModelTranslatable',
                'search',
                'orderBy',
                'orderColumn',
                'sortOrder',
                'searchable',
                'isServerSide',
                'defaultSearchKey',
                'usesSoftDeletes',
                'showSoftDeleted'
            )
        );
    }

    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists(
                    $model,
                    'scope' . ucfirst($dataType->scope)
                )) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        $user = auth()->user();

        if ($user->id != User::SuperAdminId && $dataTypeContent->app_id != $user->app_id) {
            return app('App\Http\Controllers\Controller')->outWithError(__('user.forbidden'), 403);
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
    }

    public function create(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0) ? new $dataType->model_name() : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        $apps = DB::table('apps')->get();

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'apps'));
    }

    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists(
                    $model,
                    'scope' . ucfirst($dataType->scope)
                )) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        foreach ($dataType->editRows as $key => $row) {
            $dataType->editRows[$key]['col_width'] = isset($row->details->width) ? $row->details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        $apps = DB::table('apps')->get();

        $user = auth()->user();

        if ($user->id != User::SuperAdminId && $dataTypeContent->app_id != $user->app_id) {
            return app('App\Http\Controllers\Controller')->outWithError(__('user.forbidden'), 403);
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'apps'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate(
            [
                'app_id' => [Rule::requiredIf(auth()->id() == User::SuperAdminId), Rule::in(App::AppIds)],
            ]
        );

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        }

        // Check permission
        $this->authorize('edit', $data);

        if (auth()->id() != User::SuperAdminId) {
            $app_id = auth()->user()->app_id;
        } else {
            $app_id = $request->input('app_id');
        }

        $title_translations = json_decode($request->title_i18n, true);
        $body_translations = json_decode($request->body_i18n, true);

        $title = $title_translations[app()->getLocale()];
        $body = $body_translations[app()->getLocale()];

        $locales = array_keys($title_translations);

        $data->title = $title;
        $data->body = $body;
        $data->app_id = $app_id;
        $data->save();

        if ($request->file('photo')) {
            $data->attach($request->file('photo'), ['disk' => 'public']);
        }

        foreach ($locales as $locale) {
            if ($title_translations[$locale]) {
                $data = $data->translate($locale);
                $data->title = $title_translations[$locale];
                $data->body = $body_translations[$locale];
                $data->save();
            } else {
                continue;
            }
        }

        event(new BreadDataUpdated($dataType, $data));

        return redirect()->route("voyager.{$dataType->slug}.index")->with(
            [
                'message'    => __('voyager::generic.successfully_updated') . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]
        );
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'app_id' => [Rule::requiredIf(auth()->id() == User::SuperAdminId), Rule::in(App::AppIds)],
            ]
        );

        if (auth()->id() != User::SuperAdminId) {
            $app_id = auth()->user()->app_id;
        } else {
            $app_id = $request->input('app_id');
        }

        $title_translations = json_decode($request->title_i18n, true);
        $body_translations = json_decode($request->body_i18n, true);

        $title = $title_translations[app()->getLocale()];
        $body = $body_translations[app()->getLocale()];

        $locales = array_keys($title_translations);

        $news = new News(
            [
                'title'  => $title,
                'body'   => $body,
                'app_id' => $app_id
            ]
        );
        $news->save();

        if ($request->file('photo')) {
            $news->attach($request->file('photo'), ['disk' => 'public']);
        }

        foreach ($locales as $locale) {
            if ($title_translations[$locale]) {
                $news = $news->translate($locale);
                $news->title = $title_translations[$locale];
                $news->body = $body_translations[$locale];
                $news->save();
            } else {
                continue;
            }
        }

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        event(new BreadDataAdded($dataType, $news));

        return redirect()->route("voyager.{$dataType->slug}.index")->with(
            [
                'message'    => __(
                        'voyager::generic.successfully_added_new'
                    ) . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]
        );
    }
}