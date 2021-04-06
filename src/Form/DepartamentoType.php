<?php
namespace App\Form;
use App\Entity\Pais;
use App\Entity\Departamento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Select2Type;
class DepartamentoType extends AbstractType
{
    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $builder
            ->add('nombre',TextType::class,[
                "label" => "Nombre (*): ",
                "required" => "true",
                "empty_data" => '',
                "attr" => [
                    "class" =>"form-control"
                ]
            ])
            ->add('pais', Select2Type::class,[
                'label' => 'País (*):', 
                'required' => true,
                'attr' => ['data-autocomplete-url' => $this->router->generate('pais_search')],
                'choices' => [$data->getNombre() => $data->getId()]
            ]);
        /*
        $formModifier = function (FormInterface $form, ?int $Id = 0) {
            $choices = empty($Id) ? null : [$Id => $Id];
            $form->add('pais', Select2Type::class, [
                'label' => 'País (*):', 
                'required' => true,
                'attr' => ['data-autocomplete-url' => $this->router->generate('pais_search')],
                'choices' => $choices,
                'data' => $Id
            ]);
        };
        $builder->addEventListener(
           FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
               $data = $event->getData();
               $Id = empty($data) ? 0 : $data->getId();
               $Name = empty($data) ? '' : $data->getNombre();
               $formModifier($event->getForm(), $Id, $Name);
            }
        );
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), empty($data['pais']) ? 0 : $data['pais']);
            }
        );*/
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Departamento::class,
        ]);
    }
}