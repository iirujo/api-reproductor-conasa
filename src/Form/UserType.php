<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Usuario;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('nombre')
      ->add('apellidos')
      ->add('username')
      ->add('email')
      ->add('password')
      ->add('fechaNacimiento', DateTimeType::class, array(
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'yyyy-mm-dd'
        ))
    ;
  }
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Usuario::class,
      'csrf_protection' => false
    ));
  }
}