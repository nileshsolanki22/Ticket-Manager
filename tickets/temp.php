<?php

function tickets_list1() {

    $role = $GLOBALS['role'];
    $pid = $_SESSION['pid'];

    if ($role == 'anonymous user') {
        $url = '../user/login';
        drupal_goto($url, array('external' => TRUE));
        //$url = '../';
        // drupal_goto($url, array('external' => TRUE));
    }

    if ($pid == '') {
        drupal_get_messages('error');
        $url = '../project/select';
        drupal_goto($url, array('external' => TRUE));
    }

    $user = $GLOBALS['user']->name;
    $output = '';
    //drupal_set_message('tickets list:- ' . $role);

    /*
     *      To show all tickets to admin
     */

    if ($role == 'administrator') {
        $node_id = '';
        $node = '';
        $query = 'SELECT nid FROM {node} WHERE type =' . " 'ticket' ";
        $result_node_id = db_query($query);
        //drupal_set_message($query);
        $project = '';
        $assignee = '';
        $reporter = '';
        $issue_type = '';
        $summary = '';
        $description = '';
        $priority = '';

        $count = -1;
        foreach ($result_node_id as $record) {
            $node_id[] = $record->nid;
            $count++;
        }

        //height: 600px; overflow:auto;
        $output['div'] = array(
          '#markup' => '<div class= "col-sm-3" style="background-color:lavender; " >',
        );
        //drupal_set_message($node);
        for ($i = $count; $i >= 0; $i--) {
            //drupal_set_message($node_id[$i]);;
            $q1 = "SELECT * FROM {tickets} WHERE nid = " . "'" . $node_id[$i] . "'" . " and status = '1' and pid ='" . $pid . "'";
            //drupal_set_message($q1);
            $result_data = db_query($q1);

            // if (is_numeric($node_id[$i])) {
            foreach ($result_data as $record) {
                $project = $record->project;
                $issue_type = $record->issue_type;
                $summary = $record->summary;
                $assignee = $record->assignee;
                $priority = $record->priority;

                /*
                  $output['project' . $node_id[$i]] = array(
                  '#markup' => $project,
                  '#prefix' => '<b>Project:</b><br/>',
                  //'#weight' => 4,
                  );
                 */
                /*
                  $output['issue_type' . $node_id[$i]] = array(
                  '#markup' => $issue_type,
                  '#prefix' => '<br/><b>Issue Type:</b><br/>',
                  //'#weight' => 4,
                  );
                 */

                $output['summary' . $node_id[$i]] = array(
                  '#markup' => $summary,
                  '#prefix' => '<br/><b>Summary:</b><br/>',
                    // '#weight' => 2,
                );
                /*
                  $output['assignee' . $node_id[$i]] = array(
                  '#markup' => $assignee,
                  '#prefix' => '<br/><b>Assignee:</b><br/>',
                  //'#weight' => 3,
                  '#suffix' => '',
                  );
                 */
                //$nid = $node_id[$i];
                $output['read_more' . $node_id[$i]] = array(
                  '#prefix' => '<br/>',
                  '#markup' => l('Read More', 'ticket/view-all/' . $node_id[$i]),
                  '#suffix' => '</b><br/><br/>',
                );
            }
        }

        $get_nid = arg(2);
        drupal_set_message(arg(3));

        //height:600px; overflow

        $output['div_end'] = array(
          '#markup' => '</div><div class ="col-sm-9" style="background-color:lavenderblush; " >',
        );

        if (arg(3) == 'edit') {
            $edit_ticket = drupal_get_form('tickets_form', 'edit');
            $output['view_ticket'] = array(
              //'#markup' => tickets_view(80),
              '#markup' => drupal_render($edit_ticket),
              '#suffix' => '</div>',
            );
        }
        else {
            if ($get_nid == "") {
                $view_ticket = tickets_view(0);
            }
            else {
                $view_ticket = tickets_view($get_nid);
            }

            $output['view_ticket'] = array(
              //'#markup' => tickets_view(80),
              '#markup' => drupal_render($view_ticket),
              '#suffix' => '</div>',
            );
        }
    }
    else {
        //When user is not an administrator
        $qu = "SELECT distinct(tickets.assignee) from tickets,project WHERE "
            . "project.project = tickets.project and status = '1' and tickets.assignee='" . $user . "'";
        //drupal_set_message($qu)
        $result_db_username = db_query($qu);
        $db_user = '';
        foreach ($result_db_username as $val) {
            $db_user = $val->assignee;
        }

        $user = $db_user;
        //drupal_set_message($db_user);

        $node_id = '';
        $node = '';
        $i = 0;
        $output = '';
        $project = '';
        $assignee = '';
        $reporter = '';
        $issue_type = '';
        $summary = '';
        $description = '';
        $priority = '';

        /*
         *  To show assigned users their tickets..!
         */
        //"' or reporter='".$user.
        $ticket_info = "SELECT * from tickets WHERE status = '1' and assignee = '" . $user . "'" . "and pid ='" . $pid . "'";

        $result_data = db_query($ticket_info)->fetchAll();

        $output['div'] = array(
          '#markup' => '<div class= "col-sm-3" style="background-color:lavender; height: 600px; overflow:auto;" >',
        );
        foreach ($result_data as $record) {
            $project = $record->project;
            $issue_type = $record->issue_type;
            $summary = $record->summary;
            $reporter = $record->reporter;
            $assignee = $record->assignee;
            $description = $record->description;
            $priority = $record->priority;
            $nid = $record->nid;
            //}
            // drupal_set_message($i);

            $output['project' . $i] = array(
              '#markup' => $project,
              '#prefix' => '<b>Project:</b><br/>',
                // '#weight' => 1,
            );
            $output['issue_type' . $i] = array(
              '#markup' => $issue_type,
              '#prefix' => '<br/><b>Issue Type:</b><br/>',
                //'#weight' => 4,
            );
            $output['summary' . $i] = array(
              '#markup' => $summary,
              '#prefix' => '<br/><b>Summary:</b><br/>',
                // '#weight' => 2,
            );

            $output['assignee' . $i] = array(
              '#markup' => $assignee,
              '#prefix' => '<br/><b>Assignee:</b><br/>',
                //'#weight' => 3,
            );

            $output['read_more' . $i] = array(
              //'#markup' => 'read more',
              '#prefix' => '<br/>',
              '#markup' => l('Read More', 'ticket/view-all/' . $nid),
              '#suffix' => '<br/><br/>',
                //'#weight' => 5,
            );
            $i++;
        }


        $get_nid = arg(2);
        drupal_set_message(arg(3));

        $output['div_end'] = array(
          '#markup' => '</div><div class ="col-sm-9" style="background-color:lavenderblush; height:600px; overflow:auto;" >',
        );

        if (arg(3) == 'edit') {
            $edit_ticket = drupal_get_form('tickets_form', 'edit');
            $output['view_ticket'] = array(
              //'#markup' => tickets_view(80),
              '#markup' => drupal_render($edit_ticket),
              '#suffix' => '</div>',
            );
        }
        else {
            if ($get_nid == "") {
                $view_ticket = tickets_view(0);
            }
            else {
                $view_ticket = tickets_view($get_nid);
            }

            $output['view_ticket'] = array(
              //'#markup' => tickets_view(80),
              '#markup' => drupal_render($view_ticket),
              '#suffix' => '</div>',
            );
        }
    }

    //When no ticket is assigned to a user
    if ($output == '') {
        $output['display'] = array(
          '#markup' => 'No tickets assigned',
        );
        return $output;
    }
    else {
        //print_r($output);
        return $output;
    }
}


/*
  function ticket_update($form_state){
  //$node_id = arg(1);
  //drupal_set_message(arg(1));

  $node_id = arg(2);
  drupal_set_message("inside ticket_update node = ".arg(2));



  $result = db_query('SELECT * FROM {tickets} WHERE nid = :nodeid', array(':nodeid' => $node_id))->fetchAll();
  foreach($result as $record){
  drupal_set_message('Assignee:- '.$record->assignee);
  drupal_set_message('Reporter:- '.$record->reporter);
  //drupal_set_message('cid:- '.$record->cid);
  }



  db_update('tickets')
  ->fields(array(
  'status' => 0,
  ))
  ->condition('nid', $node_id)
  ->execute();

  db_insert('tickets')
  ->fields(array(
  'nid' => $node_id,
  'project' => $_POST['project'],
  'issue_type' => $_POST['issue_type'],
  'summary' => $_POST['ticket_summary'],
  'reporter' => $_POST['reporter'],
  'description' => $_POST['ticket_description'],
  'priority' => $_POST['priority'],
  'assignee' => $_POST['assignee'],
  'status' => 1,
  ))
  ->execute();

  drupal_set_message(t('Ticket has been updated...!'));

  //return 'hi';
  }
 */
/*
 * Comment
 *                 
  $comment->hostname = '127.0.01'; // OPTIONAL. You can log poster's ip here
  $comment->created = time(); // OPTIONAL. You can set any time you want here. Useful for backdated comments creation.
  $comment->is_anonymous = 0; // leave it as is
  $comment->homepage = ''; // you can add homepage URL here
  $comment->status = COMMENT_PUBLISHED; // We auto-publish this comment
  $comment->language = LANGUAGE_NONE; // The same as for a node
  $comment->subject = 'Comment subject';
  $comment->comment_body[$comment->language][0]['value'] = 'Comment body text'; // Everything here is pretty much like with a node
  $comment->comment_body[$comment->language][0]['format'] = 'filtered_html';
  //$comment->field_custom_field_name[LANGUAGE_NONE][0]['value'] = &lsquo;Some value&rsquo;; // OPTIONAL. If your comment has a custom field attached it can added as simple as this // preparing a comment for a save
  comment_submit($comment); // saving a comment
  comment_save($comment);
 */

/*
  function tickets_list_view($nid) {
  //drupal_set_message('jQ:- ' . $nid);
  //window.open('http://localhost/drupal7/ticket/view/$node');
  $pre = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script><script>';
  $suf = '</script>';
  $body = '<p>ghi</p>';
  //$('.col-sm-8').click(function(){ tickets_view($node); });
  //alert($('.read_more').attr('value'));
  $output = $pre . "$(document).ready(function () {
  $('button').click(function () {
  $('.col-sm-9').hide();
  });
  });" . $suf;
  //$_SESSION['nid'] = ;
  return $output;
  }
 */

 
    /*
      $result = explode(" ", $roles);
      $query = "select distinct(role.name),users_roles.rid from users_roles,role,users where "
      . "users.uid = users_roles.uid and users_roles.rid = role.rid and role.name = ";

      //drupal_set_message($query);
      foreach ($result as $value) {
      //drupal_set_message($value);
      $roles_rid = db_query($query . "'" . $value . "'");
      //drupal_set_message($query."'".$value."'");


      foreach ($roles_rid as $role => $role_id) {
      //drupal_set_message($value->name);
      //drupal_set_message($role_id->rid);
      $rid_output[$value] = $role_id->rid;
      }
      /*
      }

      // foreach ($rid_output as $role => $role_id) {
      // drupal_set_message('Role = ' . $role);
      // drupal_set_message('Role ID = ' . $role_id);
     */
	 
	     /**
     *   Loads Taxonomy Project from database
     */
    /*
      $result = db_query("SELECT taxonomy_term_data.name from {taxonomy_term_data,taxonomy_vocabulary} "
      . "where taxonomy_vocabulary.vid = taxonomy_term_data.vid and taxonomy_vocabulary.name='Project' ORDER BY NAME");
     */

