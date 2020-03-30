<?php

namespace App\Form;

use App\Model\CallModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Json;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fields = [];

        $fields[] = 'method';
        $fields[] = 'path';
        $fields[] = 'body';

        foreach ($fields as $field) {
            switch ($field) {
                case 'method':
                    $builder->add('method', ChoiceType::class, [
                        'choices' => CallModel::getMethods(),
                        'choice_label' => function ($choice, $key, $value) use ($options) {
                            return $key;
                        },
                        'choice_translation_domain' => false,
                        'label' => 'method',
                        'required' => true,
                        'constraints' => [
                            new NotBlank(),
                        ],
                    ]);
                    break;
                case 'path':
                    $builder->add('path', TextType::class, [
                        'label' => 'path',
                        'required' => false,
                    ]);
                    break;
                case 'body':
                    $builder->add('body', TextareaType::class, [
                        'label' => 'body',
                        'required' => false,
                        'constraints' => [
                            new Json(),
                        ],
                        'attr' => [
                            'style' => 'min-height:200px;'
                        ],
                    ]);
                    break;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CallModel::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'data';
    }
}
