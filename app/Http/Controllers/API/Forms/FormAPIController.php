<?php

namespace App\Http\Controllers\API\Forms;

use App\Http\Controllers\ApiController;
use App\Http\Requests\API\CreateFormAPIRequest;
use App\Http\Requests\API\UpdateFormAPIRequest;
use App\Storage\Form\Form;
use App\Repositories\FormRepository;
use App\Transformers\FormTransformer;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Spatie\MediaLibrary\Media;

/**
 * Class FormController
 * @package App\Http\Controllers\API\Forms
 */
class FormAPIController extends ApiController
{
    /** @var  FormRepository */
    private $formRepository;

    public function __construct(FormRepository $formRepo)
    {
        $this->formRepository = $formRepo;
    }

    /**
     * Display a listing of the Form.
     * GET|HEAD /forms
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->formRepository->pushCriteria(new RequestCriteria($request));

        if ($request->has('limit')) {
            $forms = $this->formRepository->paginate($request->input('limit', 10));

            return $this->respondWithPaginatedCollection($forms, new FormTransformer());
        }

        return $this->respondWithCollection($this->formRepository->all(), new FormTransformer());
    }

    /**
     * Store a newly created Form in storage.
     * POST /forms
     *
     * @param CreateFormAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateFormAPIRequest $request)
    {
        $input = $request->all();

        /** @var Form $forms */
        $forms = $this->formRepository->create($input);

        return $this->respondWithItem($forms, new FormTransformer());
    }

    /**
     * Display the specified Form.
     * GET|HEAD /forms/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var Form $form */
        $form = $this->formRepository->findWithoutFail($id);

        if (!$form && is_string($id)) {
            $form = $this->formRepository->findByField('guid', $id)->first();
        }

        if (empty($form)) {
            return $this->errorNotFound('Form not found');
        }

        return $this->respondWithItem($form, new FormTransformer(), request()->input('includes', 'steps'));
    }

    /**
     * Store a newly created Result in storage.
     * POST /forms
     *
     * @param $id
     * @param null $edit
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function save($id, $edit = null, Request $request)
    {
        /** @var Form $form */
        $form = $this->formRepository->findWithoutFail($id);

        if (!$form && is_string($id)) {
            $form = $this->formRepository->findByField('guid', $id)->first();
        }

        if (empty($form)) {
            return $this->errorNotFound('Form not found');
        }

        $this->formRepository->saveResult($form, $edit, $request->all());
    }

    /**
     * Display the specified Form.
     * GET|HEAD /forms/{id}
     *
     * @param  int $id
     *
     * @return bool|\Illuminate\Http\Response
     */
    public function result($id)
    {
        /** @var Form $form */
        $form = $this->formRepository->findWithoutFail($id);

        if (!$form && is_string($id)) {
            $form = $this->formRepository->findByField('guid', $id)->first();
        }

        if (empty($form)) {
            return $this->errorNotFound('Form not found');
        }

        return $this->formRepository->generate($form, \request()->all(), $this->errorNotFound('Result not found'));
    }

    /**
     * @param Media $media
     * @return \Illuminate\Http\Response
     */
    public function template(Media $media)
    {
        return response()->download($media->getPath());
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function import($id)
    {
        /** @var Form $form */
        $form = $this->formRepository->findWithoutFail($id);

        if (!$form && is_string($id)) {
            $form = $this->formRepository->findByField('guid', $id)->first();
        }

        return $this->formRepository->import($form);
    }

    /**
     * Update the specified Form in storage.
     * PUT/PATCH /forms/{id}
     *
     * @param  int $id
     * @param UpdateFormAPIRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateFormAPIRequest $request)
    {
        $input = $request->all();

        /** @var Form $form */
        $form = $this->formRepository->findWithoutFail($id);

        if (empty($form)) {
            return $this->errorNotFound('Form not found');
        }

        $form = $this->formRepository->update($input, $id);

        return $this->respondWithItem($form, new FormTransformer());
    }

    /**
     * Remove the specified Form from storage.
     * DELETE /forms/{id}
     *
     * @param  int $id
     *
     * @return string
     */
    public function destroy($id)
    {
        /** @var Form $form */
        $form = $this->formRepository->findWithoutFail($id);

        if (empty($form)) {
            return $this->errorNotFound('Form not found');
        }

        $form->delete();

        return '';
    }
}
