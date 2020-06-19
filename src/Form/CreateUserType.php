<?php

namespace App\Form;

use App\Manager\CallManager;
use App\Model\CallRequestModel;
use App\Model\ElasticsearchUserModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateUserType extends AbstractType
{
    public function __construct(CallManager $callManager, TranslatorInterface $translator)
    {
        $this->callManager = $callManager;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fields = [];

        if (false == $options['update']) {
            $fields[] = 'username';
        } else {
            $fields[] = 'change_password';
        }
        $fields[] = 'password';
        $fields[] = 'email';
        $fields[] = 'full_name';
        $fields[] = 'roles';
        $fields[] = 'metadata';

        foreach ($fields as $field) {
            switch ($field) {
                case 'username':
                    $builder->add('username', TextType::class, [
                        'label' => 'username',
                        'required' => true,
                        'constraints' => [
                            new NotBlank(),
                        ],
                        'attr' => [
                            'autocomplete' => 'nope',
                        ],
                    ]);
                    break;
                case 'change_password':
                    $builder->add('change_password', CheckboxType::class, [
                        'label' => 'change_password',
                        'required' => false,
                    ]);
                    break;
                case 'password':
                    if (false == $options['update']) {
                        $builder->add('password', PasswordType::class, [
                            'label' => 'password',
                            'required' => true,
                            'constraints' => [
                                new NotBlank(),
                                new Length([
                                    'min' => 6,
                                ])
                            ],
                            'attr' => [
                                'autocomplete' => 'new-password',
                                'minlength' => 6,
                            ],
                        ]);
                    } else {
                        $builder->add('password', PasswordType::class, [
                            'label' => 'password',
                            'required' => false,
                            'constraints' => [
                                new Length([
                                    'min' => 6,
                                ])
                            ],
                            'attr' => [
                                'disabled' => 'disabled',
                                'autocomplete' => 'new-password',
                                'minlength' => 6,
                            ],
                        ]);
                    }
                    break;
                case 'email':
                    $builder->add('email', EmailType::class, [
                        'label' => 'email',
                        'required' => false,
                    ]);
                    break;
                case 'full_name':
                    $builder->add('full_name', TextType::class, [
                        'label' => 'full_name',
                        'required' => false,
                    ]);
                    break;
                case 'roles':
                    $builder->add('roles', ChoiceType::class, [
                        'multiple' => true,
                        'choices' => $options['roles'],
                        'choice_label' => function ($choice, $key, $value) use ($options) {
                            return $options['roles'][$key];
                        },
                        'choice_translation_domain' => false,
                        'label' => 'roles',
                        'required' => false,
                        'attr' => [
                            'data-break-after' => 'yes',
                        ],
                    ]);
                    break;
                case 'metadata':
                    $builder->add('metadata', TextareaType::class, [
                        'label' => 'metadata',
                        'required' => false,
                        'constraints' => [
                            new Json(),
                        ],
                    ]);
                    break;
            }
        }

        if (false == $options['update']) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
                $form = $event->getForm();

                if ($form->has('username')) {
                    if ($form->get('username')->getData()) {
                        $callRequest = new CallRequestModel();
                        $callRequest->setPath('/_security/user/'.$form->get('username')->getData());
                        $callResponse = $this->callManager->call($callRequest);

                        if (Response::HTTP_OK == $callResponse->getCode()) {
                            $form->get('username')->addError(new FormError(
                                $this->translator->trans('username_already_used')
                            ));
                        }
                    }
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ElasticsearchUserModel::class,
            'roles' => [],
            'update' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'data';
    }
}
