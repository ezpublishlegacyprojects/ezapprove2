<?php
//
// Created on: <15-Dec-2005 06:45:23 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZApprove2
// SOFTWARE RELEASE: 0.1
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file select_approver.php
*/

include_once( 'kernel/common/template.php' );

include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezxapprovestatus.php' );
include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezapprove2event.php' );

$Module =& $Params['Module'];

$approveStatusID = $Params['ApproveStatusID'];

$warning = '';

$approveStatus = eZXApproveStatus::fetch( $approveStatusID );

if ( !$approveStatus )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( 'RemoveApproveUsers' ) )
{
    foreach( $http->postVariable( 'DeleteApproveUserIDArray' ) as $approveUserID )
    {
        $approveStatus->removeUser( $approveUserID );
    }
}
else if ( $http->hasPostVariable( 'AddApproveUsers' ) )
{
        include_once( 'kernel/classes/ezcontentbrowse.php' );
        eZContentBrowse::browse( array( 'action_name' => 'SelectMultipleUsers',
                                        'class_array' => array ( 'user' ),
                                        'from_page' => 'ezapprove2/select_approver/' . $approveStatus->attribute( 'id' ) ),
                                 $Module );
}
else if ( $http->hasPostVariable( 'SelectedObjectIDArray' ) )
{
    foreach( $http->postVariable( 'SelectedObjectIDArray' ) as $userID )
    {
        $approveStatus->addApproveUser( $userID );
    }
}
else if ( $http->hasPostVariable( 'SubmitButton' ) )
{
    $approveEvent = $approveStatus->attribute( 'approve2_event' );
    $approveUserList = $approveStatus->attribute( 'approve_user_list' );

    if ( count( $approveUserList ) < $approveEvent->attribute( 'num_approve_users' ) )
    {
        $warning = ezi18n( 'ezapprove2', 'You need to select at least %num_users users to approve your content.', false, array( '%num_users' => $approveEvent->attribute( 'num_approve_users' ) ) );
    }
    else
    {
        $approveStatus->setAttribute( 'approve_status', eZXApproveStatus_StatusInApproval );
        $approveStatus->store();

        $workflowProcess = $approveStatus->attribute( 'workflow_process' );
        if ( !$workflowProcess )
        {
            $approveStatus->remove();
            return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
        }
        $workflowProcess->setAttribute( 'status', EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON );
        $workflowProcess->setAttribute( 'modified', mktime() );
        $workflowProcess->store();

        $collaborationItemID = $approveStatus->createCollaboration();

        return $Module->redirect( 'collaboration', 'item', array( 'full',
                                                                  $collaborationItemID ) );
    }
}

$tpl =& templateInit();
$tpl->setVariable( 'approval_status', $approveStatus );
$tpl->setVariable( 'object', $approveStatus->attribute( 'object_version' ) );
$tpl->setVariable( 'warning', $warning );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:workflow/eventtype/ezapprove2/select_approver.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'ezapprove2', 'Edit Subscription' ) ) );


?>
