<?php
namespace App\Controller;
use App\Entity\Departamento;
use App\Form\DepartamentoType;
use App\Form\BuscadorType;
use App\Form\BorrarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/departamento")
 */
class DepartamentoController extends AbstractController
{
    /**
     * @Route("/", name="departamento_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator) {
        $departamentos = [];
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(BuscadorType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dato = $form->get('dato')->getData();
            $departamentos = $em->getRepository(Departamento::class)->findByFilter($dato);
        }
        $paginas = $paginator->paginate(
            $departamentos,
            $request->query->getInt('page', 1),
            12
        );
        $paginas->setTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig');
        $paginas->setSortableTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_font_awesome_sortable_link.html.twig');
        $paginas->setCustomParameters(['align' => 'center']);
        return $this->render('departamento/index.html.twig', [
            'departamentos' => $paginas,
            'form' => $form->createView() 
        ]);
    }

    /**
     * @Route("/new", name="departamento_new", methods={"GET","POST"})
     */
    public function new(Request $request, ValidatorInterface $validator) {
        $departamento = new Departamento();
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($departamento);
                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('success','Departamento creado');
                return $this->redirectToRoute('departamento_index');
            } else {
                $this->showValidations($request, $validator, $departamento);               
            }
        }
        return $this->render('departamento/new.html.twig', [
            'departamento' => $departamento,
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/edit/{id}/", name="departamento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ValidatorInterface $validator, $id) {
        $em = $this->getDoctrine()->getManager();
        $dep = $em->getRepository(Departamento::class)->find($id);
        $form = $this->createForm(DepartamentoType::class, $dep);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if($form->isValid()) {
                $em->persist($dep);
                $em->flush();
                $request->getSession()->getFlashBag()->add('success','Departamento editado');
                return $this->redirectToRoute('departamento_index');
            } else {
                $this->showValidations($request, $validator, $dep);
            }            
        }
        return $this->render('departamento/edit.html.twig', [
            'dep' => $dep,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}/", name="departamento_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, ValidatorInterface $validator, $id) {
        $em = $this->getDoctrine()->getManager();
        $dep = $em->getRepository(Departamento::class)->find($id);
        $form = $this->createForm(BorrarType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if($form->isValid()){ 
                if(!$request->request->get("sip")) {
                    $em->remove($dep);
                    $em->flush();
                    $this->addFlash('success','Departamento eliminado');
                    return $this->redirectToRoute('departamento_index');                
                }            
            } else {
                $this->showValidations($request, $validator, $dep); 
            }            
        }        
        return $this->render('departamento/delete.html.twig', [
            'dep' => $dep,
            'mensaje' => "¿Desea seleccionar el departamento seleccionado?",
            'form' => $form->createView(),
        ]);
    }
        
    /**
     * Método para mostrar las validaciones realizadas a la entidad
     * 
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param type $departamento
     */
    public function showValidations(Request $request, ValidatorInterface $validator, $departamento){
        $errores = $validator->validate($departamento);
        if(count($errores)){
            $request->getSession()->getFlashBag()->add('error', $errores[0]->getMessage());
        }
    }
}
