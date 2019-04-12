<?php

namespace CrudBundle\Controller;

use CrudBundle\Entity\Data;
use CrudBundle\Form\DataType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\UIBundle\Route\Router;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;



/**
 * Data controller.
 *
 * @Route("data")
 */
class DataController extends Controller
{

    /**
     * @Route("/", name="crud_index")
     * @Template()
     * @AclAncestor("crud_data_view")
     */
    public function indexAction()
    {
        return array(
            'entity' => new Data()
        );
    }

    /**
     * @Route("/create", name="crud_create")
     * @Template("CrudBundle:data:update.html.twig")
     * @AclAncestor("crud_data_create")
     */
    public function createAction(Request $request)
    {
        $data = new Data();
        $form = $this->createForm('CrudBundle\Form\DataType', $data);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->createData($data);

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'crud_update',
                    'parameters' => array('id' => $data->getId()),
                ),
                array('route' => 'crud_index'),
                $data
            );
        }

        return [
            'entity' => $data,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/edit/{id}", name="crud_update", requirements={"id"="\d+"})
     * @Template("CrudBundle:data:update.html.twig")
     * @AclAncestor("crud_data_update")
     */
    public function editAction(Data $data, Request $request)
    {
        $form = $this->createForm('CrudBundle\Form\DataType', $data);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->createData($data);

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'crud_update',
                    'parameters' => array('id' => $data->getId()),
                ),
                array('route' => 'crud_index'),
                $data
            );
        }

        return [
            'entity' => $data,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/quickEdit/{id}", name="crud_quick_edit", requirements={"id"="\d+"}, options= {"expose"= true})
     * @AclAncestor("crud_date_update")
     */
    public function quickEdit(Data $data, Request $request)
    {
        $em = $this->getDoctrine()->getManagerForClass(Data::class);

        $payload = json_decode($request->getContent());
        if(key_exists('name',$payload)){
            $data->setName($payload->name);
            $data->setUpdatedAt(new \DateTime());
            $em->flush();
            return new JsonResponse(['success' => true]);
        }
        return new JsonResponse(['success' => false]);
    }

    /**
     * @Route("/delete/{id}", name="crud_delete", requirements={"id"="\d+"}, options= {"expose"= true})
     * @AclAncestor("crud_date_delete")
     */
    public function deleteData(Data $data, Request $request)
    {
        $em = $this->getDoctrine()->getManagerForClass(Data::class);

        $em->remove($data);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    protected function createData(Data $data){
        $em = $this->getDoctrine()->getManagerForClass(Data::class);

        $data->setCreatedAt(new \DateTime());
        $data->setCreatedBy($this->getUser());
        $em->persist($data);
        $em->flush();
    }
}
