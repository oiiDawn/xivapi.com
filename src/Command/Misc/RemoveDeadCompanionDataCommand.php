<?php

namespace App\Command\Misc;

use App\Common\Game\GameServers;
use App\Service\Companion\CompanionItemManager;
use App\Service\Companion\CompanionMarket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveDeadCompanionDataCommand extends Command
{
    /** @var CompanionMarket */
    private $cm;
    private $cim;
    private $em;

    public function __construct(
        CompanionMarket $cm,
        CompanionItemManager $cim,
        EntityManagerInterface $em,
        ?string $name = null
    ) {
        $this->cm  = $cm;
        $this->cim = $cim;
        $this->em  = $em;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('RemoveDeadCompanionDataCommand')
            ->setDescription('')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $console = new ConsoleOutput();
        $console = $console->section();

        // grab sellable ids
        $sellableItems = $this->cim->getMarketItemIds();

        // offline servers ( japan dc )
        $offlineServers = array_merge(
            GameServers::LIST_DC['Elemental'],
            GameServers::LIST_DC['Gaia'],
            GameServers::LIST_DC['Mana']
        );

        $onlineServers = array_merge(
            GameServers::LIST_DC['Aether'],
            GameServers::LIST_DC['Primal'],
            GameServers::LIST_DC['Crystal'],
            GameServers::LIST_DC['Chaos'],
            GameServers::LIST_DC['Light']
        );

        // delete everything from JP Servers
        foreach ($offlineServers as $server) {
            $serverId = GameServers::getServerId($server);

            foreach ($sellableItems as $itemId) {
                // delete!!!
                $console->overwrite("Server: ({$serverId}) {$server} - ItemID: {$itemId}");
                # $this->cm->delete($serverId, $itemId);
            }
        }

        // new section
        $console->write("Cleaned out JP Servers");
        $console = new ConsoleOutput();
        $console = $console->section();

        // delete all items which have shop data
        foreach ($onlineServers as $server) {
            $serverId = GameServers::getServerId($server);

            foreach ($sellableItems as $itemId) {
                // get market item entry
                $conn = $this->em->getConnection();
                $stmt = $conn->prepare("SELECT * FROM companion_market_item_source WHERE item = {$itemId}");
                $stmt->execute();
                $shopData = $stmt->fetch();

                if ($shopData) {
                    $shopData = json_decode($shopData['data']);
                    $shopData = array_unique($shopData);


                    // delete!!!
                    if ($shopData) {
                        $console->overwrite("Server: ({$serverId}) {$server} - ItemID: {$itemId}");
                        # $this->cm->delete($serverId, $itemId);
                    }
                }
            }
        }


        $console->write("Finished with NPC Items");
    }
}
