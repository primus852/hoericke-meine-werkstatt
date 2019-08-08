<?php


namespace App\Util;


use App\Entity\Survey;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class SurveyManager
{

    protected $em;
    private $limit;
    private $opening_hours;

    /**
     * SurveyManager constructor.
     * @param ObjectManager $em
     * @param int $limit
     */
    public function __construct(ObjectManager $em, int $limit = 0)
    {
        $this->em = $em;
        $this->limit = $limit;
        $this->opening_hours = $this->opening_hours();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function list()
    {

        $today = new DateTime();
        $last3 = $today->modify('-' . $this->limit . ' months');
        $surveys = $this->em->getRepository(Survey::class)->findAll();

        $s = array();
        foreach ($surveys as $survey) {

            if ($this->limit > 0) {
                if ($survey->getEnteredOn() > $last3) {
                    $s[] = $survey;
                }
            } else {
                $s[] = $survey;
            }
        }

        return $s;
    }

    public function charts(array $results)
    {
        $values = array();
        for ($i = 1; $i <= 8; $i++) {
            $values['q' . $i . '_1'] = 0;
            $values['q' . $i . '_2'] = 0;
            $values['q' . $i . '_3'] = 0;
            $values['q' . $i . '_4'] = 0;
            $values['q' . $i . '_5'] = 0;
            $values['q' . $i . '_6'] = 0;
            $values['q' . $i . '_7'] = 0;
            $values['q' . $i . '_8'] = 0;
            $values['q' . $i . '_9'] = 0;
            $values['q' . $i . '_10'] = 0;
            $values['q' . $i . '_sum'] = 0;
        }

        foreach ($results as $result) {

            $cols = $this->em->getClassMetadata(Survey::class)->getColumnNames();

            foreach ($cols as $col) {

                $getter = 'get'.ucfirst($col);

                if (strpos($col, 'q') !== FALSE) {
                    if (strpos($col, '9') === FALSE) {
                        $values[$col . '_' . $result->$getter()]++;
                        $values[$col . '_sum'] += $result->$getter();
                    } else {
                        $t = explode('-', $result->$getter());
                        $this->opening_hours[$t[0] . '-' . $t[1]]++;
                    }
                }
            }
        }

        $a = array();
        for ($i = 1; $i <= 8; $i++) {
            $a['q' . $i] = array(
                $values['q' . $i . '_1'],
                $values['q' . $i . '_2'],
                $values['q' . $i . '_3'],
                $values['q' . $i . '_4'],
                $values['q' . $i . '_5'],
                $values['q' . $i . '_6'],
                $values['q' . $i . '_7'],
                $values['q' . $i . '_8'],
                $values['q' . $i . '_9'],
                $values['q' . $i . '_10'],
            );
            $avg = round($values['q' . $i . '_sum'] / count($results), 2);

            $a['q' . $i . '_avg'] = array(
                $avg,
                $avg,
                $avg,
                $avg,
                $avg,
                $avg,
                $avg,
                $avg,
                $avg,
                $avg,
            );
        }

        return $a;
    }

    /**
     * @return array
     */
    public function chart_hours()
    {
        $labelTime = array();
        $valuesTime = array();
        foreach($this->opening_hours as $key => $value){
            $labelTime[] = $key;
            $valuesTime[] = $value;
        }

        return array(
            'categories' => $labelTime,
            'data' => $valuesTime,
        );
    }

    /**
     * @return array
     */
    private function opening_hours()
    {
        $hours = array();
        for ($i = 0; $i <= 12; $i++) {
            for ($x = 13; $x <= 24; $x++) {
                $hours[$i . '-' . $x] = 0;
            }
        }

        return $hours;
    }


}