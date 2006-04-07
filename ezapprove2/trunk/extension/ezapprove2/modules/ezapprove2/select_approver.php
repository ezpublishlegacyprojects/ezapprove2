<?php
//
// Created on: <15-Dec-2005 06:45:23 hovik>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
