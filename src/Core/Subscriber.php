<?php declare(strict_types=1);

namespace ChangeSetBug\Core;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Command\UpdateCommand;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Validation\PostWriteValidationEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Validation\PreWriteValidationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            PreWriteValidationEvent::class => 'preValidate',
            PostWriteValidationEvent::class => 'postValidate',
        ];
    }

    public function preValidate(PreWriteValidationEvent $event): void
    {
        foreach ($event->getCommands() as $command) {
            if ($command instanceof UpdateCommand && $command->getDefinition()->getEntityName() === ProductDefinition::ENTITY_NAME) {
                $command->requestChangeSet();
            }
        }
    }

    public function postValidate(PostWriteValidationEvent $event): void
    {
        foreach ($event->getCommands() as $command) {
            if ($command instanceof UpdateCommand && $command->getDefinition()->getEntityName() === ProductDefinition::ENTITY_NAME) {
                echo "\nentity primary key: ";
                echo bin2hex($command->getPrimaryKey()['id']);
                echo "\nchangeset primary key: ";
                echo bin2hex($command->getChangeSet()->getBefore('id'));
                echo "\n\n";
            }
        }
        die();
    }
}
