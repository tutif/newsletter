<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Survey;
use App\Service\CategoryProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyType extends AbstractType
{
    private CategoryProvider $categoryProvider;

    public function __construct(CategoryProvider $categoryProvider)
    {
        $this->categoryProvider = $categoryProvider;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', ChoiceType::class, [
                'choices' => $this->createChoicesList(),
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('Subscribe', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }

    /**
     * @return bool[]
     */
    private function createChoicesList(): array
    {
        $choices = [];
        foreach ($this->categoryProvider->getCategories() as $category) {
            $choices[$category] = $category;
        }

        return $choices;
    }
}
