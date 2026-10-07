<?php

namespace App\State\Ticket;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Ticket\TicketListOutput;
use App\Entity\User;
use App\Service\TicketService;
use Symfony\Bundle\SecurityBundle\Security;

class TicketCollectionProvider implements ProviderInterface
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly Security $security
    ){}

     /**
     * Serves the city collection, already mapped onto its output payload.
     *
     * @return TicketListOutput[]
     * @impelements ProviderInterface<TicketListOutput[]>
     */
    public function provide(
        Operation $operation, 
        array $uriVariables = [], 
        array $context = []
    ): array {

        $user = $this->security->getUser();
        
        if(!$user instanceof User){
            return [];
        }

        $tickets = $this->ticketService->findFor($user);
        
        return array_map(
            $this->ticketService->toList(...),
            $tickets
        );
    }
}
