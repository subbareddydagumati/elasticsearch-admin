<?php

namespace App\Form\Type;

use App\Manager\CallManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ElasticsearchTaskFilterType extends AbstractType
{
    public function __construct(CallManager $callManager)
    {
        $this->callManager = $callManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET');

        $fields = [];

        $fields[] = 'node';
        $fields[] = 'page';

        foreach ($fields as $field) {
            switch ($field) {
                case 'node':
                    $builder->add('node', ChoiceType::class, [
                        'multiple' => true,
                        'choices' => $options['node'],
                        'choice_label' => function ($choice, $key, $value) use ($options) {
                            return $options['node'][$key];
                        },
                        'choice_translation_domain' => false,
                        'label' => 'node',
                        'required' => false,
                        'attr' => [
                            'size' => 1,
                        ],
                    ]);
                    break;
                case 'page':
                    $builder->add('page', HiddenType::class, [
                        'label' => 'page',
                        'required' => false,
                    ]);
                    break;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'node' => [],
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
