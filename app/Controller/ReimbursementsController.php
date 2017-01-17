<?php

/**
 * Event controller.
 *
 * This file will render views from views/events/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Events controller
 *
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
App::uses('CakeEmail', 'Network/Email');

class ReimbursementsController extends AppController {

    public $uses = array('Reimbursement', 'LookupValueReimbursementType_1', 'LookupValueReimbursementType_2', 'LookupValueActivityLevel', 'LookupValueBillStatus', 'LookupValueEventCourrency', 'LookupValueBillType', 'Channel', 'City', 'LookupValueStatus', 'Event', 'ActionItem', 'Payment', 'User');

    public function index() {

        $dummy_status = $this->Auth->user('dummy_status');
        $user_id = $this->Auth->user('id');
        $channel_id = $this->Session->read("channel_id");
        $role_id = $this->Session->read("role_id");
        $channels = $this->Channel->findById($channel_id);
        $city_id = $channels['Channel']['city_id'];
        $search_condition = array();
        $created_by = '';
        $reimbursement_status = '';
        $cur_date = date('Y-m-d');
        $start_date = date('Y-m') . '-01';
        $end_date = date('Y-m') . '-15';
        $from_day = '';
        $from_month = '';
        $from_year = '';
        $to_day = '';
        $to_month = '';
        $to_year = '';
        $primary_cond = array();

        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->data['Builder']['builder_name'])) {
                $builder_name = $this->data['Builder']['builder_name'];
                array_push($search_condition, array('Builder.builder_name' . ' LIKE' => mysql_escape_string(trim(strip_tags($builder_name))) . "%"));
            }
            if (!empty($this->data['Reimbursement']['created_by'])) {
                $created_by = $this->data['Reimbursement']['created_by'];
                array_push($search_condition, array('Reimbursement.created_by' => $created_by));
            }

            if (!empty($this->data['Reimbursement']['reimbursement_status'])) {
                $reimbursement_status = $this->data['Reimbursement']['reimbursement_status'];
                array_push($search_condition, array('Reimbursement.reimbursement_status' => $reimbursement_status));
            }

            if (!empty($this->data['Reimbursement']['from_day']) && !empty($this->data['Reimbursement']['from_month']) && !empty($this->data['Reimbursement']['from_year'])) {
                $from_day = $this->data['Reimbursement']['from_day'];
                $from_month = $this->data['Reimbursement']['from_month'];
                $from_year = $this->data['Reimbursement']['from_year'];
                $from_date = $from_year . '-' . $from_month . '-' . $from_day;
                array_push($search_condition, array('Reimbursement.reimbursement_bill_submission_date >=' => $from_date));
            }

            if (!empty($this->data['Reimbursement']['to_day']) && !empty($this->data['Reimbursement']['to_month']) && !empty($this->data['Reimbursement']['to_year'])) {
                $to_day = $this->data['Reimbursement']['to_day'];
                $to_month = $this->data['Reimbursement']['to_month'];
                $to_year = $this->data['Reimbursement']['to_year'];
                $to_date = $to_year . '-' . $to_month . '-' . $to_day;
                array_push($search_condition, array('Reimbursement.reimbursement_bill_submission_date <=' => $to_date));
            }
        } elseif ($this->request->is('get')) {

            if (!empty($this->request->params['named']['builder_name'])) {
                $builder_name = $this->request->params['named']['builder_name'];
                array_push($search_condition, array("Builder.builder_name LIKE '%$builder_name%'"));
            }

            if (!empty($this->request->params['named']['created_by'])) {
                $created_by = $this->request->params['named']['created_by'];
                array_push($search_condition, array('Reimbursement.created_by' => $created_by));
            }

            if (!empty($this->request->params['named']['reimbursement_status'])) {
                $reimbursement_status = $this->request->params['named']['reimbursement_status'];
                array_push($search_condition, array('Reimbursement.reimbursement_status' => $reimbursement_status));
            }
            if (!empty($this->request->params['named']['from_day']) && !empty($this->request->params['named']['from_month']) && !empty($this->request->params['named']['from_year'])) {

                $from_day = $this->data['Reimbursement']['from_day'];
                $from_month = $this->data['Reimbursement']['from_month'];
                $from_year = $this->data['Reimbursement']['from_year'];
                $from_date = $from_year . '-' . $from_month . '-' . $from_day;
                array_push($search_condition, array('Reimbursement.reimbursement_bill_submission_date >=' => $from_date));
            }
            if (!empty($this->request->params['named']['to_day']) && !empty($this->request->params['named']['to_month']) && !empty($this->request->params['named']['to_year'])) {

                $to_day = $this->data['Reimbursement']['to_day'];
                $to_month = $this->data['Reimbursement']['to_month'];
                $to_year = $this->data['Reimbursement']['to_year'];
                $to_date = $to_year . '-' . $to_month . '-' . $to_day;
                array_push($search_condition, array('Reimbursement.reimbursement_bill_submission_date <=' => $to_date));
            }
        }

        if ($dummy_status)
            array_push($search_condition, array('ActionItem.dummy_status' => $dummy_status));

        if (count($this->params['pass'])) {
            $aaray = explode(':', $this->params['pass'][0]);
            $field = $aaray[0];
            $value = $aaray[1];
            array_push($search_condition, array('Reimbursement.' . $field => $value)); // when builder is approve/pending
        }

        $this->paginate['conditions'][0] = "ActionItem.next_action_by = " . $user_id . " AND ActionItem.action_item_level_id=9";
        $this->paginate['conditions'][1] = $search_condition;
        $this->paginate['order'] = array('ActionItem.id' => 'desc');
        $this->set('actionitems', $this->paginate("ActionItem"));

        /* if($cur_date >= $start_date && $cur_date <= $end_date)
          $this->paginate['conditions'][2] = "Reimbursement.reimbursement_bill_submission_date <= '".$end_date."'";
          else
          $this->paginate['conditions'][2] = "Reimbursement.reimbursement_bill_submission_date >= '".$end_date."'"; */


        //$this->paginate['conditions'][0] = "ActionItem.action_item_active='Yes' AND ActionItem.next_action_by = ".$user_id." AND ActionItem.action_item_level_id=9";
        //$this->paginate['conditions'][1] = $search_condition;
        //$this->paginate['order'] = array('ActionItem.id' => 'desc');	
        //$this->set('actionitems', $this->paginate("ActionItem", $search_condition));
        //$this->set('actionitems',$this->paginate("ActionItem"));
        //$log = $this->Reimbursement->getDataSource()->getLog(false, false);       
        // debug($log);	

        $all_count = $this->Reimbursement->find('count', array('conditions' => array('Reimbursement.dummy_status' => $dummy_status))); // approve
        $this->set(compact('all_count'));

        $paid_count = $this->Reimbursement->find('count', array('conditions' => array('Reimbursement.dummy_status' => $dummy_status, 'Reimbursement.reimbursement_status' => 3)));
        $this->set(compact('paid_count'));

        $pending_count = $this->Reimbursement->find('count', array('conditions' => array('Reimbursement.dummy_status' => $dummy_status, 'Reimbursement.reimbursement_status' => 2)));
        $this->set(compact('pending_count'));

        $today = array('15' => '15', '28' => '28', '29' => '29', '30' => '30', '31' => '31');
        $this->set(compact('today'));

        $fromday = array('01' => '01', '15' => '15');
        $this->set(compact('fromday'));



        for ($i = 1; $i <= 12; $i++) {
            $month[date("m", strtotime(date('Y-m-01') . " -$i months"))] = date("m", strtotime(date('Y-m-01') . " -$i months"));
        }
        asort($month);
        $this->set(compact('month'));

        /* 	for($j=date('Y');$j<=date('Y')+10;$j++){
          $year[$j] = $j;
          } */
        $year = array(date('Y', strtotime('-1 years')) => date('Y', strtotime('-1 years')),date('Y') => date('Y'));
        $this->set(compact('year'));

        array_push($primary_cond, array('NOT' => array('User.city_id' => 2), 'User.dummy_status' => $dummy_status, 'User.exu_role_id' => '7'));
        $primary_manager = $this->User->find('all', array('fields' => array('User.id', 'User.fname', 'User.lname'),
            'conditions' => $primary_cond, 'order' => 'User.fname asc'));

        $primary_manager = Set::combine($primary_manager, '{n}.User.id', array('%s %s', '{n}.User.fname', '{n}.User.lname'));
        $this->set(compact('primary_manager'));

        $reimburse_type = $this->LookupValueReimbursementType_1->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set(compact('reimburse_type'));

        $activity_levels = $this->LookupValueActivityLevel->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set('activity_levels', $activity_levels);

        $reimburse_for = $this->LookupValueReimbursementType_2->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set(compact('reimburse_for'));

        if (!isset($this->passedArgs['created_by']) && empty($this->passedArgs['created_by'])) {
            $this->passedArgs['created_by'] = (isset($this->data['Reimbursement']['created_by'])) ? $this->data['Reimbursement']['created_by'] : '';
        }
        if (!isset($this->passedArgs['reimbursement_status']) && empty($this->passedArgs['reimbursement_status'])) {
            $this->passedArgs['reimbursement_status'] = (isset($this->data['Reimbursement']['reimbursement_status'])) ? $this->data['Reimbursement']['reimbursement_status'] : '';
        }
        if (!isset($this->passedArgs['from_day']) && !isset($this->passedArgs['from_month']) && !isset($this->passedArgs['from_year'])) {
            $this->passedArgs['from_day'] = (isset($this->data['Reimbursement']['from_day'])) ? $this->data['Reimbursement']['from_day'] : '';
            $this->passedArgs['from_month'] = (isset($this->data['Reimbursement']['from_month'])) ? $this->data['Reimbursement']['from_month'] : '';
            $this->passedArgs['from_year'] = (isset($this->data['Reimbursement']['from_year'])) ? $this->data['Reimbursement']['from_year'] : '';
        }
        if (!isset($this->passedArgs['to_day']) && !isset($this->passedArgs['to_month']) && !isset($this->passedArgs['to_year'])) {
            $this->passedArgs['to_day'] = (isset($this->data['Reimbursement']['to_day'])) ? $this->data['Reimbursement']['to_day'] : '';
            $this->passedArgs['to_month'] = (isset($this->data['Reimbursement']['to_month'])) ? $this->data['Reimbursement']['to_month'] : '';
            $this->passedArgs['to_year'] = (isset($this->data['Reimbursement']['to_year'])) ? $this->data['Reimbursement']['to_year'] : '';
        }


        if (!isset($this->data) && empty($this->data)) {
            $this->data['Reimbursement']['created_by'] = $this->passedArgs['created_by'];
            $this->data['Reimbursement']['reimbursement_status'] = $this->passedArgs['reimbursement_status'];
            $this->data['Reimbursement']['from_day'] = $this->passedArgs['from_day'];
            $this->data['Reimbursement']['from_month'] = $this->passedArgs['from_month'];
            $this->data['Reimbursement']['from_year'] = $this->passedArgs['from_year'];
            $this->data['Reimbursement']['to_day'] = $this->passedArgs['to_day'];
            $this->data['Reimbursement']['to_month'] = $this->passedArgs['to_month'];
            $this->data['Reimbursement']['to_year'] = $this->passedArgs['to_year'];
        }

        $this->set(compact('created_by'));
        $this->set(compact('reimbursement_status'));
        $this->set(compact('from_day'));
        $this->set(compact('from_month'));
        $this->set(compact('from_year'));
        $this->set(compact('to_day'));
        $this->set(compact('to_month'));
        $this->set(compact('to_year'));
    }

    public function add($event_id = null) {

        $this->layout = 'ajax';
        $dummy_status = $this->Auth->user('dummy_status');
        $user_id = $this->Auth->user('id');
        $role_id = $this->Session->read("role_id");
        $channel_id = $this->Session->read("channel_id");
        $channels = $this->Channel->findById($channel_id);
        $channel_role = $channels['Channel']['channel_role'];
        $city_id = $channels['Channel']['city_id'];

        if ($this->request->is('post')) {


            $this->request->data['Reimbursement']['dummy_status'] = $dummy_status;
            $this->request->data['Reimbursement']['reimbursement_city_id'] = $city_id;
            $this->request->data['Reimbursement']['created_by'] = $user_id;
            $this->request->data['Reimbursement']['reimbursement_closed'] = 'No';
            $this->request->data['Reimbursement']['reimbursement_status'] = '1';  // Submitted of lookup_value_reimbursement_status
            $this->request->data['Reimbursement']['reimbursement_industry'] = '1'; //  for Real Estate
            $this->request->data['Reimbursement']['reimbursement_bill_submission_date'] = date('Y-m-d'); //  current date
            if ($this->data['Reimbursement']['expense_attached'] == '1') {
                $this->request->data['Reimbursement']['reimbursement_level'] = $this->data['Reimbursement']['reimbursement_level2'];
                $this->request->data['Reimbursement']['reimbursement_with'] = $this->data['Reimbursement']['reimbursement_with2'];
                $this->request->data['Reimbursement']['reimbursement_type_1'] = $this->data['Reimbursement']['reimbursement_type_12'];
                $this->request->data['Reimbursement']['reimbursement_type_2'] = $this->data['Reimbursement']['reimbursement_type_22'];
            } else {
                $this->request->data['Reimbursement']['expense_date'] = date('Y-m-d', strtotime($this->request->data['Reimbursement']['exp_date']));
            }

            $this->Reimbursement->create();
            if ($this->Reimbursement->save($this->request->data)) {
                //$log = $this->Reimbursement->getDataSource()->getLog(false, false);       
                //debug($log);
                $reimbursement_id = $this->Reimbursement->getLastInsertId();
                $action_item['ActionItem']['event_id'] = $reimbursement_id;
                $action_item['ActionItem']['action_item_level_id'] = '9'; //  for Event 
                $action_item['ActionItem']['type_id'] = '7'; // 7 for Submission For Approval
                // $actionitem['ActionItem']['next_action_by'] = $business_admin['Channel']['id'];
                $action_item['ActionItem']['action_item_active'] = 'Yes';
                $action_item['ActionItem']['action_item_status'] = '7'; //7 for Submitted For Approval table - lookup_value_action_item_statuses
                $action_item['ActionItem']['description'] = 'Reimbursement - Submission For Approval';
                $action_item['ActionItem']['action_item_source'] = $role_id;
                $action_item['ActionItem']['created_by_id'] = $user_id;
                $action_item['ActionItem']['created_by'] = $user_id;
                $action_item['ActionItem']['dummy_status'] = $dummy_status;
                $action_item['ActionItem']['action_industry'] = '1'; // for realestate of lookup_value_activity_industry
                $action_item['ActionItem']['next_action_by'] = '147';
                $this->ActionItem->save($action_item);
                $ActionId = $this->ActionItem->getLastInsertId();

                $this->ActionItem->id = $ActionId;
                $this->ActionItem->saveField('parent_action_item_id', $ActionId);
                //$last_action_id = $this->ActionItem->getLastInsertId();
                $actionArry = $this->ActionItem->findById($ActionId);
                $this->Session->setFlash('Reimbursement has been saved.', 'success');
                $Reimbursements = $this->Reimbursement->findById($reimbursement_id);

                $Email = new CakeEmail();

                $Email->viewVars(array(
                    'SubmittedBy' => $actionArry['SubmittedBy']['fname'] . ' ' . $actionArry['SubmittedBy']['mname'] . ' ' . $actionArry['SubmittedBy']['lname'],
                    'CreatedBy' => $actionArry['CreatedBy']['fname'] . ' ' . $actionArry['CreatedBy']['lname'],
                    'NextActionBy' => $actionArry['NextActionBy']['fname'] . ' ' . $actionArry['NextActionBy']['lname'],
                    'LookupReturn' => strtoupper($actionArry['LookupReturn']['value']),
                    'LookupReject' => strtoupper($actionArry['LookupReject']['value']),
                    'Return' => strtoupper($actionArry['ActionItem']['other_return']),
                    'Reject' => strtoupper($actionArry['ActionItem']['other_rejection']),
                    'ApprovalAmount' => $Reimbursements['Reimbursement']['reimbursement_amount'],
                    'SumittedDate' => date("j M, Y", strtotime($Reimbursements['Reimbursement']['created'])),
                    'ActivityLevel' => $Reimbursements['Level']['value'],
                    'ActivityWith' => $Reimbursements['Reimbursement']['reimbursement_with'],
                    'ExpenceType' => $Reimbursements['Type']['value'],
                    'ExpenceFor' => $Reimbursements['For']['value'],
                ));

                $to = 'infra@sumanus.com';
                //$to = 'biswajit@wtbglobal.com';
                $Email->template('reimbursement', 'default')->emailFormat('html')->to($to)->from('admin@silkrouters.com')->subject($Reimbursements['Level']['value'] . ' REIMBURSEMENT SUBMITTED BY - ' . strtoupper($actionArry['CreatedBy']['fname'] . ' ' . $actionArry['CreatedBy']['lname']))->send();

                echo '<script>
				 			var objP=parent.document.getElementsByClassName("mfp-bg");
							var objC=parent.document.getElementsByClassName("mfp-wrap");
							objP[0].style.display="none";
							objC[0].style.display="none";
							parent.location.reload(true);</script>';
                // $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add Reimbursement.', 'failure');
            }
        }

        $activities = $this->Event->find('all', array('conditions' => array('Event.activity_completed' => 1, 'Event.activity_past' => 'Yes', 'Event.activity_type' => array(4, 6), 'Event.creator_id' => $user_id),
            'order' => 'Event.created asc'));
        //pr($activities);
        $activities = Set::combine($activities, '{n}.Event.id', array('%s / %s %s %s %s / %s - %s / %s / %s / %s / %s%s', '{n}.ActivityLevel.value', '{n}.Lead.lead_fname', '{n}.Lead.lead_lname', '{n}.Builder.builder_name', '{n}.Project.project_name', '{n}.Event.start_date', '{n}.Event.end_date', '{n}.ActivityType.value', '{n}.Details.value', '{n}.ActivitySite.project_name', '{n}.Attendes1.fname', '{n}.Attendes1.lname'));
        //pr($activities);
        $this->set(compact('activities'));

        $reimburse_type = $this->LookupValueReimbursementType_1->find('list', array('fields' => 'id,value', 'conditions' => array('id' => array('12', '13')), 'order' => 'id ASC'));
        $this->set(compact('reimburse_type'));

        $activity_levels = $this->LookupValueActivityLevel->find('list', array('fields' => 'id,value', 'conditions' => array('id' => array('4', '5')), 'order' => 'id ASC'));
        $this->set('activity_levels', $activity_levels);

        $bill_type = $this->LookupValueBillStatus->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set(compact('bill_type'));

        $bill_status = $this->LookupValueBillType->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set(compact('bill_status'));
    }

    public function edit($id = null) {

        $this->layout = 'ajax';
        $dummy_status = $this->Auth->user('dummy_status');
        $user_id = $this->Auth->user('id');
        $role_id = $this->Session->read("role_id");
        $channel_id = $this->Session->read("channel_id");
        $channels = $this->Channel->findById($channel_id);
        $channel_role = $channels['Channel']['channel_role'];
        $city_id = $channels['Channel']['city_id'];

        $Reimbursements = $this->Reimbursement->findById($id);

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->Reimbursement->id = $id;
            $this->Reimbursement->set($this->data);
            if ($this->Reimbursement->save($this->request->data)) {
                $this->Session->setFlash('Reimbursement has been saved.', 'success');


                echo '<script>
				 			var objP=parent.document.getElementsByClassName("mfp-bg");
							var objC=parent.document.getElementsByClassName("mfp-wrap");
							objP[0].style.display="none";
							objC[0].style.display="none";
							parent.location.reload(true);</script>';
                // $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add Reimbursement.', 'failure');
            }
        }

        $reimburse_type = $this->LookupValueReimbursementType_1->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set(compact('reimburse_type'));

        $activity_levels = $this->LookupValueActivityLevel->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set('activity_levels', $activity_levels);

        $bill_type = $this->LookupValueBillStatus->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set(compact('bill_type'));

        $bill_status = $this->LookupValueBillType->find('list', array('fields' => 'id,value', 'order' => 'id ASC'));
        $this->set(compact('bill_status'));

        $reimburse_for = $this->LookupValueReimbursementType_2->find('list', array('fields' => 'id,value', 'conditions' => array('type_id' => $Reimbursements['Reimbursement']['reimbursement_type_1']), 'order' => 'id ASC'));
        $this->set(compact('reimburse_for'));

        if (!$this->request->data) {
            $this->request->data = $Reimbursements;
        }
    }

    public function view($id = null) {

        $this->layout = 'ajax';
        $Reimbursements = $this->Reimbursement->findById($id);
        if (!$this->request->data) {
            $this->request->data = $Reimbursements;
        }
    }

    public function add_payment() {

        $dummy_status = $this->Auth->user('dummy_status');
        $user_id = $this->Auth->user('id');
        if (!isset($this->data['Payment']['submit']))
            $this->redirect(array('action' => 'index'));


        if ($this->data['Payment']['submit'] == 'submit') {

            $reimbursement_created_by = $this->data['Payment']['reimbursement_created_by'];
            $reimbursement_from_date = $this->data['Payment']['reimbursement_from_date'];
            $reimbursement_to_date = $this->data['Payment']['reimbursement_to_date'];

            if (count($this->data['Payment']['msg_sel']) > 0) {
                foreach ($this->data['Payment']['msg_sel'] as $val) {
                    $arr = explode('_', $val);
                    $action_id[] = $arr[0];
                    $event_id[] = $arr[1];
                }
            }

            $Reimbursements = $this->Reimbursement->find('all', array('conditions' => array('Reimbursement.id' => $event_id)));
            $this->set(compact('Reimbursements'));
            $this->set(compact('reimbursement_created_by'));
            $this->set(compact('reimbursement_from_date'));
            $this->set(compact('reimbursement_to_date'));

            //	$log = $this->Reimbursement->getDataSource()->getLog(false, false);       
            //  debug($log);

            $this->set(compact('action_id'));
            $this->set(compact('event_id'));
            $this->set(compact('user_id'));
        }
        //$action_arr[] = $action_id;
        //pr($action_arr);
        //echo 'ev='.$event_id.'act='.$action_id;


        if ($this->data['Payment']['submit'] == 'add') {

            $this->request->data['Payment']['created_by'] = $user_id;
            $this->request->data['Payment']['dummy_status'] = $dummy_status;
            $this->request->data['Event']['activity_status'] = '1'; // for approved
            $this->request->data['Event']['activity_closed'] = '"Yes"'; // for approved


            $this->Payment->create();
            if ($this->Payment->save($this->request->data)) {

                $payment_id = $this->Payment->getLastInsertId();
                $this->ActionItem->updateAll(array('ActionItem.action_item_active' => "'No'"), array('ActionItem.id' => $this->data['Payment']['action_id']));
                //$this->Event->updateAll($this->data['Event'],array('Event.id' => $this->data['Payment']['event_id']));
                foreach ($this->data['Reimbursement'] as $val => $key) {

                    $this->request->data['Reimbursement'][$val]['payment_id'] = $payment_id;
                }
                $this->Reimbursement->saveMany($this->request->data['Reimbursement']);


                $this->Session->setFlash('Payment has been saved.', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add payment.', 'failure');
            }
        }
    }

}