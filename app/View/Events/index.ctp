<?php $this->Html->addCrumb('My Events','javascript:void(0);', array('class' => 'breadcrumblast'));
//pr($events);
?>
<script>
	$(document).ready(function(){

		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		 
		 /************************Present event ***************************/
		 
		var checkin = $('#dpd1').datepicker({
		startDate: '-0m',
		onRender: function(date) {
		return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
		}).on('changeDate', function(ev) {
		if (ev.date.valueOf() > checkout.date.valueOf()) {
		var newDate = new Date(ev.date)
		newDate.setDate(newDate.getDate() + 1);
		checkout.setValue(newDate);
		}
		checkin.hide();
		$('#dpd2')[0].focus();
		}).data('datepicker');
		var checkout = $('#dpd2').datepicker({
		startDate: '-0m',
		onRender: function(date) {
		return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		}
		}).on('changeDate', function(ev) {
		checkout.hide();
		}).data('datepicker');
		
		 /************************past event ***************************/
		
		
		var start_date_past = $('#start_date_past').datepicker({
		endDate: '-0m',
		onRender: function(date) {
		return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
		}).on('changeDate', function(ev) {
		if (ev.date.valueOf() > end_date_past.date.valueOf()) {
		var newDate = new Date(ev.date)
		newDate.setDate(newDate.getDate() + 1);
		end_date_past.setValue(newDate);
		}
		start_date_past.hide();
		$('#end_date_past')[0].focus();
		}).data('datepicker');
		var end_date_past = $('#end_date_past').datepicker({
		endDate: '-0m',
		onRender: function(date) {
		return date.valueOf() <= start_date_past.date.valueOf() ? 'disabled' : '';
		}
		}).on('changeDate', function(ev) {
		end_date_past.hide();
		}).data('datepicker');


	  $('#activity_past').change(function(){
			var value  = $(this).val();
			if(value == 'Yes')
				{
					
					$('.event_date_present_div').css('display','none');
					$('.event_date_past_div').css('display','inline-table');
					
					
				}
			else{
				$('.event_date_past_div').css('display','none');
				$('.event_date_present_div').css('display','inline-table');
		
			}		
		});
	//	$('#dpStart').datepicker({ minDate: -1});
		$('#calender_view').addClass('active');
		$('#calender_view').click(function(){
			$('#calender_view').addClass('active');
			$('#table_view').removeClass('active');
			$('#calender_display').css('display','block');
			$('#table_display').removeClass('open');
			$('#event_date_div').css('display','none');
		});
		
		$('#table_view').click(function(){
			$('#table_view').addClass('active');
			$('#calender_view').removeClass('active');
			$('#calender_display').css('display','none');
			$('#table_display').addClass('open');
			$('#event_date_div').css('display','block');
		});
	  $('.EventActivityType').change(function(){
	  		var type = $(this).val();
			if(type=='1' || type=='2' || type=='3'){
				$('.attended1').css('display','block');
				$('.activity_completed').css('display','block');
				$('.attended23').css('display','none');
			
			}
			else if(type=='4' || type=='6' || type=='10'){
				$('.attended1').css('display','block');
				$('.activity_completed').css('display','block');
				$('.attended23').css('display','block');
			}
			else{
				$('.attended1').css('display','none');
				$('.activity_completed').css('display','none');
				$('.attended23').css('display','none');
			}
	  });	
		
	});
</script>
<div class="view-icons">
<span class="glyphicon glyphicon-calendar" id="calender_view"></span><span class="glyphicon glyphicon-align-justify" id="table_view"></span>
                                                        
                                                  
                                    </div>
<style>
.inline1 {
  position: relative;
  background: #FFF;
  padding: 20px;
  width: auto;
  max-width: 700px;
  margin: 20px auto;
}

</style>
<div class="row"> 
<div class="col-sm-12">

</div>
<div class="col-sm-12" id="table_display">
                            	<div class="table-heading">
										<h4 class="table-heading-title"><span class="badge badge-circle badge-success"> <?php
                echo $this->Paginator->counter(array('format' => '{:count}'));
                ?></span> My Events</h4>
                                        <span class="badge badge-circle add-client nomrgn">
                                        <i class="icon-plus" ></i>
                                    
                                         <?php echo $this->Html->link('Add Event', '/events/add',array('class' => 'act-ico open-popup-link add-btn','escape' => false)); ?></span>
                                        <span class="search_panel_icon"><i class="icon-plus" id="toggle_search_panel"></i></span>
									</div>
								<div class="panel panel-default">
									
									<div class="panel_controls hideform">
                                    
                                    <?php            
                    echo $this->Form->create('Event', array('controller' => 'events', 'class' => 'quick_search', 'id' => 'SearchForm','novalidate'=>true,'inputDefaults' => array(
																	'label' => false,
																	'div' => false,
																	'class' => 'form-control',
																)));
                 
                    ?> 
										<div class="row" id="search_panel_controls">
											
											<div class="col-sm-3 col-xs-6">
												<label for="un_member">Activity By:</label>
												<?php  echo $this->Form->input('user_id',array('options' => $activity_by,'empty' => '--Select--','value' => $user_id)); ?>
											</div>
                                            <div class="col-sm-3 col-xs-6">
												<label for="un_member">Activity Level:</label>
												<?php  echo $this->Form->input('activity_level',array('options' => $activity_levels,'empty' => '--Select--','value' =>$activity_level )); ?>
											</div>
                                            <div class="col-sm-3 col-xs-6">
												<label for="un_member">Activity With:</label>
												<?php  echo $this->Form->input('activity_with',array('options' => array(),'empty' => '--Select--')); ?>
											</div>
                                            <div class="col-sm-3 col-xs-6">
												<label for="un_member">Activity Type:</label>
												<?php  echo $this->Form->input('activity_type',array('options' => $activity_types,'empty' => '--Select--','value' =>$activity_type )); ?>
											</div>
                                            <div class="col-sm-3 col-xs-6 site_visit_project_id" style="display:none;">
												<label for="un_member">Activity Project Site:</label>
												<?php  echo $this->Form->input('site_visit_project_id',array('options'=> $projects,'empty' => '--Select--','value' => $site_visit_project_id)); ?>
											</div>
                                            <div class="col-sm-3 col-xs-6">
												<label for="un_member">Completed:</label>
												<?php  echo $this->Form->input('activity_completed',array('options'=> array('1' => 'Yes', '2' => 'No'),'empty' => '--Select--','value' => $activity_completed)); ?>
											</div>
                                       
                                            
                                            
											<div class="col-sm-3 col-xs-6">
												<label>&nbsp;</label>
                                                <?php
											   echo $this->Form->submit('Filter', array('div' => false, 'class' => 'btn btn-default btn-sm"'));            
											  // echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-default btn-sm"'));
					   
					   							?>
												
											</div>
										</div>
                                         <?php echo $this->Form->end(); ?>
									</div>
                                    
									<table border="0" cellpadding="0" cellspacing="0" id="resp_table" class="table toggle-square myclitb" data-filter="#table_search" data-page-size="100">
										<thead>
											<tr>
												<th data-toggle="true">Activity id</th>
												<th data-hide="phone">Submitted Date</th>
                                                <th data-hide="phone">Activity By</th>
                                                <th data-hide="phone">Activity Level</th>
                                                <th data-hide="phone">Activity With</th>
                                                <th data-hide="phone">Activity Type</th>
                                                <th data-hide="phone">For Project Site</th>
                                                <th data-hide="phone">Activity Date</th>
                                                <th data-hide="phone">Activity Duration</th>
                                                <th data-sort-ignore="true" data-hide="all" align="left">Activity Details</th>
                                                <th data-sort-ignore="true" data-hide="all" align="left">Activity Remarks</th>
                                                <th data-sort-ignore="true" data-hide="all" align="left">Attended By</th>
                                                <th data-hide="phone">Action</th>        
											</tr>
										</thead>
										<tbody>
                                        	<?php
											$attended = '';
										  if (isset($events) && count($events) > 0):
											foreach ($events as $event):
												$id = $event['Event']['id'];
												$activity_level = $event['Event']['activity_level'];
												if($activity_level == 1)
													$activity_with = $event['Lead']['lead_fname'].' '.$event['Lead']['lead_lname'];
												else if($activity_level == 2)	
													$activity_with = $event['Builder']['builder_name'];
												else if($activity_level == 3)	
													$activity_with = $event['Project']['project_name'];
													
												if($event['Event']['event_attended_by_1']<>'')	
													$attended = $event['Attendes1']['fname'].' '.$event['Attendes1']['lname'];	
												if($event['Event']['event_attended_by_2']<>'')	
													$attended .= ','.$event['Attendes2']['fname'].' '.$event['Attendes2']['lname'];	
												if($event['Event']['event_attended_by_3']<>'')	
													$attended .= ','.$event['Attendes3']['fname'].' '.$event['Attendes3']['lname'];			
													
												$start_date = strtotime($event['Event']['start_date']);	
												$end_date = strtotime($event['Event']['end_date']);
												$duration = round(abs($start_date - $end_date) / 60,2). " minute";
										?>	
										<tr>
											<td><?php echo $id;   ?></td> 
                                            <td><?php echo date("F j, Y, g:i a",strtotime($event['Event']['created'])); ?></td> 
                                            <td><?php  echo $event['User']['fname'].' '.$event['User']['lname'];  ?></td> 
                                            <td><?php  echo $event['ActivityLevel']['value'];   ?></td>                     
										   <td><?php echo $activity_with; ?></td>
                                           <td><?php echo $event['ActivityType']['value']; ?></td>
                                           <td><?php echo $event['ActivitySite']['project_name']; ?></td>
                                            <td><?php echo date("F j, Y, g:i a",strtotime($event['Event']['created'])); ?></td> 
                                           <td><?php echo $duration; ?></td>
                                            <td data-value="yes_UN" class="sub-tablebody"><?php echo $event['Event']['event_level_desc']; ?></td>
            								<td data-value="yes_UN" class="sub-tablebody"><?php echo $event['Event']['event_remark']; ?></td>
                                            <td data-value="yes_UN" class="sub-tablebody"><?php echo $attended; ?></td>
										  
																			
																			
																				
												<td width="10%" valign="middle" align="center">
                                                	
												<?php 
												echo $this->Html->link('<span class="icon-pencil"></span>', '/events/edit/'.$id, array('escape' => false));
												
                                         ?>
                                                 </td>
                                   
											  </tr>
                                        <?php
                                        endforeach; ?>
                                       
                                         <?php echo $this->element('paginate'); ?>
                                 <?php   endif; ?>
                                        </tbody>
									</table>
                                    <span class="badge badge-circle add-client"><i class="icon-plus"></i> <?php  echo $this->Html->link('Add Event', '/events/add',array('class' => 'act-ico open-popup-link add-btn','escape' => false));?></span>
                                    
								</div>
							</div>
 <div class="col-sm-12" id="calender_display">
        <div class="panel panel-default">
           <div id="calendar"></div>
        </div>
    </div>
 </div>
<script type="text/javascript">

   $(document).ready(function() {
   
  
   
   
    var _baseUrl=$('#hidden_site_baseurl').val();
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
	
	$('#EventActivityType').change(function(){
		var type = $(this).val();
		if(type == '6')
			$('.site_visit_project_id').css('display', 'block');
		else
			$('.site_visit_project_id').css('display', 'none');	
	});
	
	 $('#EventActivityLevel').change(function(){
			var level_id = $(this).val();
			var model = 'Event';
			
			
			var dataString = 'level_id=' + level_id + '&model='+model;
			$('#EventActivityWith').attr('disabled', 'disabled');
			 $.ajax({
				type: "POST",
				data: dataString,
				url: _baseUrl + '/all_functions/get_activity_with_by_activity_level',
				 beforeSend: function() {
					 $('#EventActivityWith').attr('disabled', 'disabled');
					//return false;
				},
				success: function(return_data) {
				   $('#EventActivityWith').removeAttr('disabled');
				   $('#EventActivityWith').html(return_data);
				}
			});  
			
		});	

    var calendar = $('#calendar').fullCalendar({
	
    editable: false,
    
    header: {
        left: 'prev,next',
        center: 'title',
       // right: 'agendaWeek,month,agendaDay'
    },
    defaultView: 'month',


    // Getting allbooking and allholidays
    eventSources: [
    {url:_baseUrl+"/all_functions/getAllEventRecords"}
    ],

    firstDay:0,

    // Convert the allDay from string to boolean
    eventRender: function(event, element, view)
    {
        element.find('.fc-event-time').show();
        if (event.allDay === 'true') {
        event.allDay = true;
		
        } else {
        event.allDay = false;
    }
    

    },
    editable: false,
    disableDragging:true,
    eventDrop: function(event,delta,minuteDelta,allDay,revertFunc) {

    start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
    end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");

    var today=$.fullCalendar.formatDate(new Date(), "yyyy-MM-dd HH:mm:ss");
    // If want to drag booking event before currentdate
    if(today> start)
    {
         revertFunc();
         return false;
    }
    // Confirm message when draging
   if (!confirm("Are you sure about this change?")) {
             revertFunc();
             return false;
    }


    $.ajax({
    url: _baseUrl+"calendar/updateBooking",
    data:{bookingDate:start, bookingId:event.id},
    //data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
    type: "POST",
    success: function(json) {
    $(".message").html('Data has been updated successfuly.');
    $(".message").show();
    $(".message").fadeOut(2500);
    calendar.fullCalendar('refetchEvents');
    }
    });
    },

    disableResizing:true,
    dayClick: function(date, allDay, jsEvent, view) {
        var getDate = $.fullCalendar.formatDate(date, "dd-MM-yyyy");
		var _getUrl=_baseUrl+"/events/add/"+getDate;
        $('#event_date').val(getDate);
		$.magnificPopup.open({
		  items: {
			src: _getUrl
		  },
		  type: 'iframe',
		  iframe: {
       				markup: '<div class="mfp-iframe-scaler your-special-css-class">'+
                        '<div class="mfp-close"></div>'+
                        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                    '</div>'
   		},callbacks: {
				open: function() {
					
				  var $obj=$(".mfp-iframe-scaler").find("iframe");
				  $obj.bind("load",function(){
        				var paddTop=$(this).contents().find(".pop-outer").height();
						$(".your-special-css-class").css({"padding-top":paddTop});
						/*$(this).contents().find(".close_bt").bind('click',function(){
								$.magnificPopup.close();
						});*/
    			  });
				  
				},
				close: function() {
					$.magnificPopup.close();				 
				}
  		}  
		 
		  
		});
		
		
		
        /*  $.magnificPopup.open({
			  items: {
				  src: '#inline1',
				  type: 'inline',
				  midClick: true
			  }
			});*/

                return false;
            
    },
    eventClick: function(calEvent, jsEvent, view ){

    var getId = calEvent.id;
    $.post(_baseUrl+"/all_functions/countDetailRecord", {recordId: getId},function(data){
    if(data > 0)
    {
        var _getUrl=_baseUrl+"/events/edit/"+getId;
/*
		$('.open-popup-link').magnificPopup({type:'iframe',
   		iframe: {
       				markup: '<div class="mfp-iframe-scaler your-special-css-class">'+
                        '<div class="mfp-close"></div>'+
                        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                    '</div>'
   		},callbacks: {
				open: function() {
					
				  var $obj=$(".mfp-iframe-scaler").find("iframe");
				  $obj.bind("load",function(){
        				var paddTop=$(this).contents().find(".pop-outer").height();
						$(".your-special-css-class").css({"padding-top":paddTop});
					
    			  });
				  
				},
				close: function() {
					$.magnificPopup.close();				 
				}
  		}  
    });*/
	$.magnificPopup.open({
		  items: {
			src: _getUrl
		  },
		  type: 'iframe',
		  iframe: {
       				markup: '<div class="mfp-iframe-scaler your-special-css-class">'+
                        '<div class="mfp-close"></div>'+
                        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                    '</div>'
   		},callbacks: {
				open: function() {
					
				  var $obj=$(".mfp-iframe-scaler").find("iframe");
				  $obj.bind("load",function(){
        				var paddTop=$(this).contents().find(".pop-outer").height();
						$(".your-special-css-class").css({"padding-top":paddTop});
						/*$(this).contents().find(".close_bt").bind('click',function(){
								$.magnificPopup.close();
						});*/
    			  });
				  
				},
				close: function() {
					$.magnificPopup.close();				 
				}
  		}  
		 
		  
		});
		
		/*$.magnificPopup.open({
		  items: {
			src: _getUrl
		  },
		  type: 'iframe',
		 
		  
		});
		magnificPopup.close();*/
		
		
/*
        $.magnificPopup.open({
        'type': 'ajax',
        'src': _getUrl,
        'onClosed': function() {
        //parent.location.reload(true);
        $('#calendar').fullCalendar('refetchEvents');
        }
        });
		*/
         }
    });

    },
    eventMouseover: function(event, domEvent) {

    var layer =event.tooltip;
    $(this).append(layer);
    $("#delbut"+event.id).hide();
    $("#delbut"+event.id).fadeIn(300);
    $("#delbut"+event.id).click(function() {

    if (confirm("Are you want to delete event?"))
    {
        $.post(_baseUrl+"calendar/deleteEvent", {eventId: event.id},function(data){
            $(".message").html('Data has been deleted successfuly.');
            $(".message").show();
            $(".message").fadeOut(2500);
            calendar.fullCalendar('refetchEvents');
        });
    }
    else
    {
        return false;
    }
    return false;
    

    });
    $("#edbut"+event.id).hide();
    $("#edbut"+event.id).fadeIn(300);
    $("#edbut"+event.id).click(function() {

    var getId = event.id; 
    var _getUrl=_baseUrl+"calendar/getForm/edit/"+getId;

    $.fancybox({
    'transitionIn': 'none',
    'transitionOut': 'none',
    'type': 'ajax',
    'href': _getUrl,
    'onClosed': function() {
    //parent.location.reload(true);
    $('#calendar').fullCalendar('refetchEvents');
    }
    });
    return false;
    });
    },

    eventMouseout: function(calEvent, domEvent) {

    $("#events-layer").remove();
    }

    });
	
	

    });


</script>