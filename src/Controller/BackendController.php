<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\Voucher;
use App\Util\SurveyManager;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/admin/gutscheine", name="backendVoucher")
     * @param ObjectManager $em
     * @return Response
     * @throws Exception
     */
    public function voucher(ObjectManager $em)
    {
        return $this->render(
            'default/voucher.html.twig', array(
            'vouchers' => $em->getRepository(Voucher::class)->findAll(),
        ));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/admin/dashboard", name="backendDashboard")
     * @param ObjectManager $em
     * @return Response
     * @throws Exception
     */
    public function dashboard(ObjectManager $em)
    {

        $sm = new SurveyManager($em);

        try{
            $s = $sm->list();
            $chart = $sm->charts($s);
            $hours = $sm->chart_hours();
        }catch (Exception $e){
            return $this->render(
                'default/error.html.twig', array(
                    'message' => $e->getMessage(),
                )
            );
        }

        return $this->render(
            'default/dashboard.html.twig', array(
                'surveys' => $s,
                'charts' => array(
                    'q1' => array(
                        'sum' => json_encode($chart['q1']),
                        'avg' => json_encode($chart['q1_avg']),
                    ),
                    'q2' => array(
                        'sum' => json_encode($chart['q2']),
                        'avg' => json_encode($chart['q2_avg']),
                    ),
                    'q3' => array(
                        'sum' => json_encode($chart['q3']),
                        'avg' => json_encode($chart['q3_avg']),
                    ),
                    'q4' => array(
                        'sum' => json_encode($chart['q4']),
                        'avg' => json_encode($chart['q4_avg']),
                    ),
                    'q5' => array(
                        'sum' => json_encode($chart['q5']),
                        'avg' => json_encode($chart['q5_avg']),
                    ),
                    'q6' => array(
                        'sum' => json_encode($chart['q6']),
                        'avg' => json_encode($chart['q6_avg']),
                    ),
                    'q7' => array(
                        'sum' => json_encode($chart['q7']),
                        'avg' => json_encode($chart['q7_avg']),
                    ),
                    'q8' => array(
                        'sum' => json_encode($chart['q8']),
                        'avg' => json_encode($chart['q8_avg']),
                    ),
                    'q9' => array(
                        'categories' => json_encode($hours['categories']),
                        'data' => json_encode($hours['data']),
                    )
                )
            )
        );
    }
}
