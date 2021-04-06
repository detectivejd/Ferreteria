<?php
namespace App\Controller;
use App\Entity\Pais;
use App\Form\PaisType;
use App\Form\BuscadorType;
use App\Form\BorrarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/pais")
 */
class PaisController extends AbstractController
{
    /**
     * @Route("/", name="pais_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator) {
        $paises = [];
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(BuscadorType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dato = $form->get('dato')->getData();
            $paises = $em->getRepository(Pais::class)->findByFilter($dato);
        }        
        $paginas = $paginator->paginate(
            $paises,
            $request->query->getInt('page', 1),
            12
        );
        $paginas->setTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig');
        $paginas->setSortableTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_font_awesome_sortable_link.html.twig');
        $paginas->setCustomParameters(['align' => 'center']);
        return $this->render('pais/index.html.twig', [
            'paises' => $paginas,
            'form' => $form->createView() 
        ]);
    }

    /**
     * @Route("/new", name="pais_new", methods={"GET","POST"})
     */
    public function new(Request $request, ValidatorInterface $validator) {
        $pais = new Pais();
        $form = $this->createForm(PaisType::class, $pais);
        $form->handleRequest($request);
        if ($this->isChecked($form, $request, $validator, $pais)){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pais);
            $entityManager->flush();
            $request->getSession()->getFlashBag()->add('success','País creado');
            return $this->redirectToRoute('pais_index');
        }
        return $this->render('pais/new.html.twig', [
            'pai' => $pais,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/edit/{id}/", name="pais_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ValidatorInterface $validator, $id) {
        $em = $this->getDoctrine()->getManager();
        $pais = $em->getRepository(Pais::class)->find($id);
        $form = $this->createForm(PaisType::class, $pais);
        $form->handleRequest($request);
        if (isChecked($form, $request, $validator, $pais)){
            $em->persist($pais);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success','País editado');
            return $this->redirectToRoute('pais_index');                        
        }
        return $this->render('pais/edit.html.twig', [
            'pais' => $pais,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}/", name="pais_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, ValidatorInterface $validator, $id) {
        $em = $this->getDoctrine()->getManager();
        $pais = $em->getRepository(Pais::class)->find($id);        
        $form = $this->createForm(BorrarType::class, null);
        $form->handleRequest($request);        
        if($this->isDeleted($em, $form, $request, $validator, $pais)){
            if(!$request->request->get("sip")) {
                $em->remove($pais);
                $em->flush();
                $this->addFlash('success','País eliminado');
                return $this->redirectToRoute('pais_index');                
            }
        }        
        return $this->render('pais/delete.html.twig', [
            'pais' => $pais,
            'mensaje' => "¿Desea seleccionar el país seleccionado?",
            'form' => $form->createView(),
        ]);
    }
        
    /**
     * @Route("/search", name="pais_search", methods={"GET"})
    */
    public function search(Request $request){
        $items = [];
        $em = $this->getDoctrine()->getManager();
        $criterio = $request->request->get('q');
        $paises = $em->getRepository(Pais::class)->findByFilter($criterio);
        foreach($paises as $pais){
            $items[] = [
                'id' => $pais->getId(),
                'text' => $pais->getNombre()
            ];  
        }
        return new JsonResponse($items);
    }
    
    public function isChecked($form, $request, $validator, $pais){
        if ($form->isSubmitted()){
            if($form->isValid()){
                return true;
            } else {
                $this->showValidations($request, $validator, $pais); 
            }
        }
        return false;
    }
    
    public function isDeleted($em, $form, $request, $validator, $pais){
        $bandera = $em->getRepository(Pais::class)->countDepartamentos($pais);
        if ($form->isSubmitted()){
            if(!$bandera){
                if($form->isValid()){
                    return true;
                } else {
                    $this->showValidations($request, $validator, $pais); 
                }            
            } else {
                $request->getSession()->getFlashBag()->add('error', 
                    "Hay departamentos usando este país");
            }
        }
        return false;
    }
    
    /**
     * Método para mostrar las validaciones realizadas a la entidad
     * 
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param type $pais
     */
    public function showValidations(Request $request, ValidatorInterface $validator, $pais){
        $errores = $validator->validate($pais);
        if(count($errores)){
            $request->getSession()->getFlashBag()->add('error', $errores[0]->getMessage());
        }
    }
}
