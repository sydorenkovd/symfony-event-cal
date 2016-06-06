<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Tests\Extension\Core\Type\SubmitTypeTest;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="category_list")
     */
    public function indexAction(Request $request)
    {
$categories = $this->getDoctrine()
    ->getRepository('AppBundle:Category')
    ->findAll();
        return $this->render('category/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/category/create", name="category_create")
     */
    public function createAction(Request $request)
    {

        $category = new Category();
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class,
                ['label' => 'Create Category', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();
        //handle request
        $form->handleRequest($request);
        //check submit
        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();
            //Get current date time
            $now = new \DateTime('now');
            $category->setName($name);
            $category->setCreateDate($now);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash(
                'notice',
                'Category Saved'
            );
            return $this->redirect('/category');
        }
        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function editAction($id, Request $request)
    {

        $category = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->find($id);
        if (!$category){
            throw $this->createNotFoundException(
                'No category found for id' . $id
            );
        }
        $category->setName($category->getName());
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class,
                ['label' => 'Update Category', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();
        //handle request
        $form->handleRequest($request);
        //check submit
        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();
            //Get current date time

            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('AppBundle:Category')->find($id);
            $category->setName($name);
            $em->flush();
            $this->addFlash(
                'notice',
                'Category Updated'
            );
            return $this->redirect('/category');
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function deleteAction(Request $request)
    {

    }
}
