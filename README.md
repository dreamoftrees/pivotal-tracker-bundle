PivotalTrackerBundle
====================

Pivotal Tracker wrapper bundle for Symfony 2.


Configuration
====================

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


