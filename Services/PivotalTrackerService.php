<?php
/**
 * Created by JetBrains PhpStorm.
 * User: charlie
 * Date: 2/07/13
 * Time: 10:10 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Dreamoftrees\PivotalTrackerBundle\Services;

use Monolog\Logger;

class PivotalTrackerService extends \pivotal {

    protected $logger;
    protected $epics;
    protected $cache;

    public function __construct($apiToken, $projectId, $epics, $states, Logger $logger = null)
    {
        $this->token = $apiToken;
        $this->project = $projectId;
        $this->epics = $epics;
        $this->states = $states;
        $this->logger = $logger;
        $this->cache = array();
    }

    /**
     * Wrapper for getStories to pass in default project id.
     *
     */
    public function getDefaultStories()
    {
        // Get an existing story
        $data = $this->getData('stories');
        return $data;
    }

    /**
     * Wrapper for getProjectActivity to pass in default project id.
     *
     * @return \SimpleXMLElement
     */
    public function getDefaultProjectActivity()
    {
        $data = $this->getData('activity');
        return $data;
    }

    public function getActivity()
    {
        $activity = array();
        $data = $this->getData('activity');
        //$this->logger->info('activity: '.json_encode($data));

        $activities = $data->xpath('/activities/activity');
        while(list( , $node) = each($activities)) {
            $author = (string) $node->author;
            $desc = (string) $node->description;
            $occurred = (string) $node->occurred_at;

            $time = strtotime($occurred);
            $occurred_date = new \DateTime(date('Y-m-d H:i:s', $time));
            $activity[] = array('author' => $author, 'desc' => $desc, 'occurred' => $occurred_date);
        }

        return $activity;
    }


    /**
     * Returns an array of epics.
     *
     */
    public function getEpics()
    {
        $data = $this->getData('stories');
        $epic_states = array();
        $formatted = array();
        foreach( $this->epics as $epic ) {
            $epic_state = array();
            $formatted[] = array('label' => $epic['title'], 'states'=>$epic_state, 'labels'=>$epic['label'], 'total'=>0);
        }

        // Parse story API data for epics state breakdown
        $stories = $data->xpath('/stories/story');
        while(list( , $node) = each($stories)) {
            $state = (string) $node->current_state;
            $labels = (string) $node->labels;

            foreach( $formatted as &$epic ) {
                if(strpos($labels, $epic['labels']) !== false) {

                    // This story label is tagged as an epic so append epic states
                    $epic_states = &$epic['states'];
                    if(array_key_exists($state, $epic_states)) {
                        $epic_states[$state] = $epic_states[$state]+1;
                    } else {
                        $epic_states[$state] = 1;
                    }
                    $epic['total'] = $epic['total']+1;
                    break;
                }
            }
        }

        // Create Totals for the epic states
        foreach( $formatted as &$epic ) {

            $epic_states = &$epic['states'];
            foreach( $epic_states as $state => $state_total) {

                $epic_states[$state] = number_format(($state_total/$epic['total'])*100, 0);
                //$this->logger->info('epic state:'.$state.' total:'.$epic['total'].' value:'.$state_total);
            }
        }

        return $formatted;
    }

    /**
     * Returns an array of story states and totals.
     *
     */
    public function getStoryStates()
    {
        $states = array();
        $data = $this->getData('stories');
        $total = (int) $data->attributes()->total;

        // Parse story API data for state count
        $stories = $data->xpath('/stories/story');
        while(list( , $node) = each($stories)) {
            $state = (string) $node->current_state;
            if(array_key_exists($state, $states)) {
                $states[$state]++;
            } else {
                $states[$state] = 1;
            }
        }

        // Compile into label/value format
        $formatted = array();
        foreach( $states as $state_name => $state_count ) {
            $state = $this->getState( $state_name );
            $formatted[] = array('label' => $state["label"], 'value'=> $state_count, 'color'=> $state['color'], 'state' => $state_name);
        }
        return $formatted;
    }

    /**
     * Returns a state config object.
     *
     * @param $name
     * @return null
     */
    public function getState( $name )
    {
        $res = null;
        foreach( $this->states as $state) {
            if($state['state'] == $name) {
                $res = $state;
                break;
            }
        }
        return $res;
    }

    /**
     * Used to cache the API response.
     *
     * @param $data_id
     * @return \SimpleXMLElement
     */
    protected function getData( $data_id )
    {
        if(array_key_exists($data_id, $this->cache)) {
            $res = $this->cache[$data_id];
        } else {
            switch($data_id) {
                case 'stories':
                    $res = $this->getStories($this->project);
                    $this->cache['stories'] = $res;
                    break;

                case 'activity':
                    $res = $this->getProjectActivity($this->project, 10);
                    $this->cache['activity'] = $res;
                    break;
            }
        }

        return $res;
    }

}