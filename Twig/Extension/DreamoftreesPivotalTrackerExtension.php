<?php
/**
 * Created by JetBrains PhpStorm.
 * User: charlie
 * Date: 3/07/13
 * Time: 11:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Dreamoftrees\PivotalTrackerBundle\Twig\Extension;

use Doctrine\Common\Util\ClassUtils;
use Dreamoftrees\PivotalTrackerBundle\Services\PivotalTrackerService;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;


class DreamoftreesPivotalTrackerExtension extends \Twig_Extension {

    protected $pt;

    public function __construct(PivotalTrackerService $pt)
    {
        $this->pt = $pt;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'pivotal_tracker_extension';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('date_interval', array($this, 'getDateInterval')),
            new \Twig_SimpleFilter('state_label', array($this, 'getStateLabel'))
        );
    }

    public function getDateInterval($date)
    {
        $res = $this->dates_interval_text($date, new \DateTime());
        return $res;
    }

    public function getStateLabel($name)
    {
        $res = '';
        $state = $this->pt->getState($name);
        if(isset($state)) $res = $state["label"];

        return $res;
    }

    protected function do_plurals($nb, $str)
    {
        return $nb > 1 ? $str . 's' : $str;
    }

    protected function dates_interval_text(\DateTime $start, \DateTime $end)
    {
        $interval = $end->diff($start);

        $format = array();
        if ($interval->y !== 0)
        {
            $format[] = "%y " . $this->do_plurals($interval->y, "year");
        }
        if ($interval->m !== 0)
        {
            $format[] = "%m " . $this->do_plurals($interval->m, "month");
        }
        if ($interval->d !== 0)
        {
            $format[] = "%d " . $this->do_plurals($interval->d, "day");
        }
        if ($interval->h !== 0)
        {
            $format[] = "%h " . $this->do_plurals($interval->h, "hour");
        }
        if ($interval->i !== 0)
        {
            $format[] = "%i " . $this->do_plurals($interval->i, "minute");
        }
        if (!count($format))
        {
            return "less than a minute ago";
        }

        // We use the two biggest parts
        if (count($format) > 1)
        {
            $format = array_shift($format) . " and " . array_shift($format);
        }
        else
        {
            $format = array_pop($format);
        }

        // Prepend 'since ' or whatever you like
        return $interval->format($format) . ' ago';
    }
}