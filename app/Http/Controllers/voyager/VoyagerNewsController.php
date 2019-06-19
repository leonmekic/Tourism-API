<?php

namespace App\Http\Controllers\Voyager;

use App\Contracts\Model;
use App\Models\User;
use App\Repositories\GeneralInfoRepository;
use App\Repositories\WorkingHoursRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;


class VoyagerNewsController extends VoyagerBaseController
{
    protected $generalInfoRepository;
    protected $workingHoursRepository;

    public function __construct(GeneralInfoRepository $generalInfoRepository , WorkingHoursRepository $workingHoursRepository)
    {
        $this->generalInfoRepository = $generalInfoRepository;
        $this->workingHoursRepository = $workingHoursRepository;
    }

    public function store(Request $request)
    {
        $slug = $this->getSlug($request);


        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        if (auth()->id() != User::SuperAdminId) {
            $request['app_id'] = auth()->user()->app_id;
        }
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        if ($request->file('photo')) {
            $data->attach($request->file('photo'), ['disk' => 'public']);
        }

        event(new BreadDataAdded($dataType, $data));

        return redirect()->route("voyager.{$dataType->slug}.index")->with(
            [
                'message' => __(
                        'voyager::generic.successfully_added_new'
                    ) . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]
        );
    }
}