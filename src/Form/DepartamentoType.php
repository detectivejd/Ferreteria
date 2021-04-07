<?php
namespace App\Form;
use App\Entity\Pais;
use App\Entity\Departamento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
class DepartamentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',TextType::class,[
                "label" => "Nombre (*): ",
                "required" => "true",
                "empty_data" => '',
                "attr" => [
                    "class" =>"form-control"
                ]
            ])
            ->add("pais", Select2EntityType::class, [
                "remote_route" => "pais_search",
                'class' => Pais::class,
                "allow_clear" => true,
                "placeholder" => "Seleccione País"
            ]);
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Departamento::class,
        ]);
    }
}