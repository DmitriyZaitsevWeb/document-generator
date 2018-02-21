<?php

namespace App\Transformers;

use App\Storage\Form\Form;
use League\Fractal\TransformerAbstract;

class FormTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['uploaded_template', 'model'];
    protected $availableIncludes = ["steps", "categories"];

    /**
     * A Fractal transformer.
     *
     * @param Form $form
     * @return array
     */
    public function transform(Form $form)
    {
        return [
            'id' => $form->id,
            'title' => $form->title,
            'guid' => $form->guid,
            'description' => $form->description,
            'categories' => $form->categories()->pluck('category_id')
        ];
    }

    /**
     * @param Form $form
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUploadedTemplate(Form $form)
    {
        return $this->collection($form->media, new FormMediaTransformer());
    }

    /**
     * @param Form $form
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSteps(Form $form)
    {
        return $this->collection($form->steps->sortBy('order'), new FormStepTransformer(), false);
    }

    /**
     * @param Form $form
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCategories(Form $form)
    {
        return $this->collection($form->categories->sortBy('order'), new CategoryTransformer(), false);
    }

    /**
     * @param Form $form
     * @return \League\Fractal\Resource\Collection
     */
    public function includeModel(Form $form)
    {
        return $this->collection($form->answers, new UserAnswersTransformer(), false);
    }
}
