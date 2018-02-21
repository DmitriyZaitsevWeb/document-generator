<?php

use App\Storage\Form\Category;
use App\Storage\Form\Form;
use App\Storage\Form\FormQuestion;
use App\Storage\Form\FormQuestionAnswer;
use App\Storage\Form\FormStep;
use Illuminate\Database\Seeder;

class FormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 5)->create()->each(function ($category) {
            $count = rand(2, 5);
            factory(Form::class, $count)->create()->each(function ($form) use ($category) {
                /** @var Form $form */
                $form->categories()->attach($category->id);
                $count = rand(3, 8);
                $steps = factory(FormStep::class, $count)->create(['form_id' => $form->id]);
                $steps->each(function ($step) {
                    $count = rand(1, 3);
                    $questions = factory(FormQuestion::class, $count)->create(['form_step_id' => $step->id]);
                    $questions->each(function ($question) {
                        $count = rand(1, 2);
                        factory(FormQuestionAnswer::class, $count)->create(['form_question_id' => $question->id]);
                    });
                    /** @var FormStep $step */
                    $step->questions()->saveMany($questions);
                });
                $form->steps()->saveMany($steps);

                $this->addTemplate($form);
            });
        });
    }

    /**
     * @param Form $form
     */
    private function addTemplate(Form $form)
    {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();

        $section->addText(
            $form->title,
            ['name' => 'Tahoma', 'size' => 16, 'bold' => true],
            ['alignment' => 'center']
        );

        $section->addText(
            $form->description,
            ['name' => 'Tahoma', 'size' => 10],
            ['alignment' => 'center']
        );

        foreach (array_flatten($form->questions->toArray()) as $item) {
            $schema = @unserialize($item);
            if (!$schema) {
                continue;
            }
            $section->addText(
                "{$schema['label']}: " . '${' . $schema['model'] . '}'
            );
        }

        $filename = storage_path('app/public') . '/' . $form->guid . '.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        try {
            $objWriter->save($filename);
            $form->addMedia($filename)->toMediaCollection('templates');
        } catch (\PhpOffice\PhpWord\Exception\Exception $exception) {
            echo $exception->getMessage();
        }

    }
}
