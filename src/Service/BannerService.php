<?php

namespace App\Service;

use App\Entity\SeasonSettings;
use Doctrine\ORM\EntityManagerInterface;

class BannerService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getBanner()
    {
        $settings = $this->em->getRepository(SeasonSettings::class)->find(1);

        if($settings === null){
            return 'her';
        }

        return $settings->getCurrentBanner();

    }

}
