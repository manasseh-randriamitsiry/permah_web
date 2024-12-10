<?php
namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route('/events', name: 'event_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Check if the user has the admin role
        if (!$this->isGranted('ROLE_USER')) {
            // Redirect to a different route, e.g., the homepage or an error page
            return $this->redirectToRoute('app_login'); // Change this to your desired route
        }

        $events = $entityManager->getRepository(Event::class)->findAll();

        return $this->render('event/list.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/events/join/{id}', name: 'event_join', methods: ['POST'])]
    public function join(Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($event->getTotalPlaces() - $event->getPlacesTaken() > 0) {
            $event->setPlacesTaken($event->getPlacesTaken() + 1);
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'You have successfully joined the event!');
        } else {
            $this->addFlash('error', 'No free places available for this event.');
        }

        return $this->redirectToRoute('event_list');
    }
}
