<?php

/*
 * Model/Event.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */

class Event extends Model {

    var $name = 'Event';
    //var $displayField = 'description';
    var $validate = array();
    public $belongsTo = array(
        'Lead' => array(
            'fields' => array('lead_fname', 'lead_lname'),
            'className' => 'Lead',
            'foreignKey' => 'lead_id'
        ),
        'Builder' => array(
            'fields' => array('builder_name'),
            'className' => 'Builder',
            'foreignKey' => 'builder_id'
        ),
        'Project' => array(
            'fields' => array('project_name'),
            'className' => 'Project',
            'foreignKey' => 'project_id'
        ),
        'Agent' => array(
            'fields' => array('agent_name'),
            'className' => 'Agent',
            'foreignKey' => 'agent_id'
        ),
        'City' => array(
            'fields' => array('city_name'),
            'className' => 'City',
            'foreignKey' => 'city_id'
        ),
        'Area' => array(
            'fields' => array('area_name'),
            'className' => 'Area',
            'foreignKey' => 'area_id'
        ),
        'Suburb' => array(
            'fields' => array('suburb_name'),
            'className' => 'Suburb',
            'foreignKey' => 'suburb_id'
        ),
        'Channel' => array(
            'fields' => array('channel_name'),
            'className' => 'Channel',
            'foreignKey' => 'channel_id'
        ),
        'FromCity' => array(
            'fields' => array('city_name'),
            'className' => 'City',
            'foreignKey' => 'from_city_id'
        ),
        'ToCity' => array(
            'fields' => array('city_name'),
            'className' => 'City',
            'foreignKey' => 'to_city_id'
        ),
        'ActivityLevel' => array(
            'className' => 'LookupValueActivityLevel',
            'foreignKey' => 'activity_level'
        ),
        'ActivityType' => array(
            'className' => 'LookupValueActivityType',
            'foreignKey' => 'activity_type'
        ),
        'ActivitySite' => array(
            'fields' => array('project_name'),
            'className' => 'Project',
            'foreignKey' => 'site_visit_project_id'
        ),
        'User' => array(
            'fields' => array('fname', 'lname'),
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Attendes1' => array(
            'fields' => array('fname', 'lname'),
            'className' => 'User',
            'foreignKey' => 'event_attended_by_1'
        ),
        'Attendes2' => array(
            'fields' => array('fname', 'lname'),
            'className' => 'User',
            'foreignKey' => 'event_attended_by_2'
        ),
        'Attendes3' => array(
            'fields' => array('fname', 'lname'),
            'className' => 'User',
            'foreignKey' => 'event_attended_by_3'
        ),
        'CallQuality' => array(
            'className' => 'LookupValueCallQuality',
            'foreignKey' => 'call_type_id'
        ),
        'Details' => array(
            'className' => 'LookupValueActivityDetail',
            'foreignKey' => 'event_type_desc'
        ),
    );

}

?>
