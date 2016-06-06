<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Event;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/event", name="event_list")
     */
    public function indexAction(Request $request)
    {

        $events = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->findAll();
        return $this->render('event/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'events' => $events
        ]);
    }
    /**
     * @Route("/event/create", name="event_create")
     */
    public function createAction(Request $request)
    {

        $event = new Event();
        $form = $this->createFormBuilder($event)
            ->add('name', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('category', EntityType::class,
                ['class'=> 'AppBundle:Category', 'choice_label'=>'name','attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('details', TextareaType::class,
                ['attr' => ['class' => 'form-control-day', 'style' => 'margin-bottom:15px']])
            ->add('day', DateTimeType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('street_address', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('city', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('zip_code', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class,
                ['label' => 'Create Event', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();
        //handle request
        $form->handleRequest($request);
        //check submit
        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $details = $form['details']->getData();
            $day = $form['day']->getData();
            $street_address = $form['street_address']->getData();
            $city = $form['city']->getData();
            $zip_code = $form['zip_code']->getData();
            //Get current date time
            $now = new \DateTime('now');
            $event->setName($name);
            $event->setCategory($category);
            $event->setDetails($details);
            $event->setDay($day);
            $event->setStreetAddress($street_address);
            $event->setCity($city);
            $event->setZipCode($zip_code );
            $event->setCreateDate($now);
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash(
                'notice',
                'Event Saved'
            );
            return $this->redirect('/event');
        }
        return $this->render('event/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/event/edit/{id}", name="event_edit")
     */
    public function editAction($id, Request $request)
    {

        $event = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->find($id);
        if(!$event){
            throw $this->createNotFoundException(
                'No event found for id' . $id
            );
        }
        $event->setName($event->getName());
        $event->setCategory($event->getCategory());
        $event->setDetails($event->getDetails());
        $event->setDay($event->getDay());
        $event->setStreetAddress($event->getStreetAddress());
        $event->setCity($event->getCity());
        $event->setZipCode($event->getZipCode());

        $form = $this->createFormBuilder($event)
            ->add('name', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('category', EntityType::class,
                ['class'=> 'AppBundle:Category', 'choice_label'=>'name','attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('details', TextareaType::class,
                ['attr' => ['class' => 'form-control-day', 'style' => 'margin-bottom:15px']])
            ->add('day', DateTimeType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('street_address', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('city', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('zip_code', TextType::class,
                ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class,
                ['label' => 'Create Event', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();
        //handle request
        $form->handleRequest($request);
        //check submit
        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $details = $form['details']->getData();
            $day = $form['day']->getData();
            $street_address = $form['street_address']->getData();
            $city = $form['city']->getData();
            $zip_code = $form['zip_code']->getData();
            //Get current date time

            $em = $this->getDoctrine()->getManager();
           $event = $em->getRepository('AppBundle:Event')->find($id);
            $event->setName($name);
            $event->setCategory($category);
            $event->setDetails($details);
            $event->setDay($day);
            $event->setStreetAddress($street_address);
            $event->setCity($city);
            $event->setZipCode($zip_code );
            $em->flush();
            $this->addFlash(
                'notice',
                'Event Updated'
            );
            return $this->redirect('/event');
        }
        return $this->render('event/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/event/delete/{id}", name="event_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        if(!$event){
            throw $this->createNotFoundException('No event for delete with id ' . $id);
        }
        $em->remove($event);
        $em->flush();
        $this->addFlash(
            'notice',
            'Event Deleted'
        );
        return $this->redirect('/event');
    }
}
