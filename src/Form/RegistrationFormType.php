<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    private $roleHierarchy;

    public function __construct(RoleHierarchyInterface $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                 'placeholder' => 'E-mail'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Las contraseñas deben coincidir',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Ingrese una constraseña'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Repita la contraseña'
                    ]
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'La contraseña debe tener como mínimo {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, array(
                'label'     =>  'Roles',
                'choices' => $this->getRoles(),
                'multiple' => true,
                'required' => true,
            ))
            ->add('nombre', TextType::class, [
                'attr' => [
                 'placeholder' => 'Nombre'
                ]
            ])
            ->add('apellido', TextType::class, [
                'attr' => [
                 'placeholder' => 'Apellido'
                ]
            ])
        ;
    }

    private function getRoles()
    {
        echo "<pre class='d-none'>";
        $roleArray = $this->roleHierarchy->getReachableRoleNames(['ROLE_ADMIN']);
//        dd($roleArray);
//        $roleFormArray = array();
//        foreach ($roleArray as $key => $value) {
//            array_push ( $roleFormArray , $value->getRole() );
//        }
//        $arrayRoles=array_combine($roleFormArray,$roleFormArray);
        echo "</pre>";
//        dd(array_combine($roleArray,$roleArray));
        return array_combine($roleArray,$roleArray);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
