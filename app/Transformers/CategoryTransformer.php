<?php

namespace App\Transformers;

use App\Storage\Form\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    protected $availableIncludes = ['forms'];

    /**
     * A Fractal transformer.
     *
     * @param Category $category
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'title' => $category->title,
            'guid' => $category->guid,
            'description' => $category->description,
        ];
    }

    /**
     * @param Category $category
     * @return \League\Fractal\Resource\Collection
     */
    public function includeForms(Category $category)
    {
        return $this->collection($category->forms->sortBy('order'), new FormTransformer(), false);
    }
}
