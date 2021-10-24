<?php

namespace App\Transformers;

use App\Models\Slide;
use Flugg\Responder\Transformers\Transformer;

class SlideTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\Slide $slide
     * @return array
     */
    public function transform(Slide $slide)
    {
        foreach ($slide->files as $file){

        }
        return [
            'id' => (int) $slide->id,
            'img_path' => (string) $file->file_path,
        ];
    }
}
