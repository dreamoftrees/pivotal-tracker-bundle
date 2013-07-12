PivotalTrackerBundle
====================

Pivotal Tracker wrapper bundle for Symfony 2.


Installation
--------------

Install with composer using the latest version tag ie. 1.0.5

        php composer.phar require dreamoftrees/pivotal-tracker-bundle


Make sure you register the bundle in the app/AppKernal.php of your Symfony 2 project:

        public function registerBundles()
        {
        ...

        $bundles[] = new Dreamoftrees\PivotalTrackerBundle\DreamoftreesPivotalTrackerBundle();

        ...
        }


You will also need to register the PHP Pivotal Tracker class in app/autoload.php, I know there must be a nicer way to register this class..


        require_once __DIR__.'/../vendor/Dreamoftrees/PivotalTrackerBundle/Lib/PivotalTracker/pivotal.php';



Configuration
--------------

Allows you to get the stories relating to a Pivotal Tracker project and then group them based on labels. As the Pivotal Tracker API doesn't yet support epics, this grouping behaviour allows you to simulate epics.
You can also define extra information for Pivotal Tracker states in the configuration file.


        parameters:
                pivotaltracker_api_token: <API TOKEN>
                pivotaltracker_project_id: <PROJECT ID>

        dreamoftrees_pivotal_tracker:
                epics:
                        - {title: "Planning", label: "planning"}
                        - {title: "Implement database", label: "database"}
                        - {title: "CMS admin area", label: "dotadmin"}
                        - {title: "CMS design integration", label: "block"}
                        - {title: "Website design implementation", label: "ui"}
                        - {title: "Website content", label: "content"}
                        - {title: "Testing", label: "testing"}
                        - {title: "Release website", label: "live"}
                        - {title: "Optimise website", label: "optimisation"}
                states:
                        - {state: "accepted", label: "complete",  color: "#81bd82" }
                        - {state: "started", label: "started", color: "#f1c359" }
                        - {state: "unscheduled", label: "unscheduled", color: "#c4dafe" }
                        - {state: "unstarted", label: "pending", color: "#30a1ec" }
                        


Charting
--------------

With a little imagination you can use Twitter Bootstrap and morris.js to render the progress of your Pivotal Tracker project, based on the configuration values that help to group your stories as epics:
- [Pivotal Tracker charting example](Demo/pivotaltracker_charts.jpg)
- [Morris.js Javascript charting library](http://www.oesmith.co.uk/morris.js/)



Credits
--------------

Uses a fork of the nice PHP Pivotal Tracker API wrapper by Joel Dare:
- [PHP Pivotal Tracker API wrapper](http://www.joeldare.com/wiki/php_pivotal_tracker_class)
- [Fork](https://github.com/dreamoftrees/PHP-Pivotal-Tracker-Class)

LICENSE
Copyright (c) 2011 Joel Dare


