<?php

namespace App\Repositories;

use App\Storage\Form\Form;
use App\Support\Word\TemplateProcessor;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class FormRepository
 *
 * @package App\Repositories
 * @version September 24, 2017, 1:35 pm UTC
 *
 * @method Form findWithoutFail($id, $columns = ['*'])
 * @method Form find($id, $columns = ['*'])
 * @method Form first($columns = ['*'])
 *
 */
class FormRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Form::class;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        if (array_has($attributes, ['categories'])) {
            $attributes['categories'] = explode(',', $attributes['categories']);
        };

        /** @var Form $model */
        $model = parent::create($attributes);
        $model->addMediaFromRequest('template')->toMediaCollection('templates', 'public');

        return $model;
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $attributes['categories'] = explode(',', $attributes['categories']);

        /** @var Form $model */
        $model = parent::update($attributes, $id);

        if ($model->hasMedia('templates') && request()->file('template')) {
            $model->deleteMedia($model->getFirstMedia('templates')->id);
        }

        if (request()->file('template')) {
            $model->addMediaFromRequest('template')->toMediaCollection('templates', 'public');
        }

        return $model;
    }

    /**
     * @param Form $form
     */
    public function import(Form $form)
    {
        dd($form);
    }

    /**
     * @param Form $form
     * @param $edit
     * @param array $attributes
     * @return bool
     */
    public function saveResult(Form $form, $edit = null, array $attributes)
    {
        if (!empty($attributes)) {
            $data = ['form_id' => $form->id, 'data' => serialize($attributes)];
            if (Auth::guest()) {
                if (\session()->has('filled-forms')) {
                    $filledForms = \session()->get('filled-forms');
                    $hasForm = array_pluck($filledForms, 'form_id');
                    if (in_array($form->id, $hasForm)) {
                        $key = array_search(array_search($form->id, $hasForm), $filledForms);
                        $filledForms[$key] = $data;
                    }
                    \session()->put('filled-forms', $filledForms);
                    return true;
                };
                \session()->push('filled-forms', $data);
            } else {
                if ($answer = Auth::user()->forms()->find($edit)) {
                    $answer->data = $data['data'];
                    $answer->save();
                    return true;
                }
                Auth::user()->forms()->create($data);
            }
        }

    }

    /**
     * @param Form $form
     * @param array $attributes
     * @param $callback
     * @return bool|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generate(Form $form, array $attributes, $callback = null)
    {
        if ($form->hasMedia('templates')) {

            $template = $form->getFirstMedia('templates');

            $templateProcessor = (new TemplateProcessor($template->getPath(), $attributes))
                ->prepare();

            return response()->download($templateProcessor->save(), $template->file_name);
        }

        return $callback;

    }

}
