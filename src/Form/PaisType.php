<?php
namespace App\Form;
use App\Entity\Pais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
class PaisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("nombre",TextType::class,[
                "label" => "Nombre (*): ",
                "required" => "true",
                "empty_data" => '',
                "attr" => [
                    "class" =>"form-control"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pais::class,
        ]);
    }
}