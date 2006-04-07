<?php
//
// Created on: <16-Jan-2006 15:14:51 hovik>
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

/*! \file add_approver.php
*/


include_once( 'kernel/common/template.php' );

include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezxapprovestatus.php' );
include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezapprove2event.php' );

$Module =& $Params['Module'];

$approveStatusID = $Params['ApproveStatusID'];
$approveStatus = eZXApproveStatus::fetch( $approveStatusID );

if ( !$approveStatus )
{
    eZDebug::writeError( 'Approve status not found.' );
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( !$approveStatus->isApprover( eZUser::currentUserID() ) )
{
    eZDebug::writeError( 'User is not allowed to add new approvers.' );
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}

$approveEvent = $approveStatus->attribute( 'approve2_event' );
if ( !$approveEvent )
{
    eZDebug::writeDebug( 'Could not find approve event.' );
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}
if ( !$approveEvent->attribute( 'allow_add_approver' ) )
{
    eZDebug::writeError( 'User is not allowed to add new approvers.' );
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}

$hash = md5( eZUser::currentUserID() . '-' . $approveStatusID );

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( 'RemoveApproveUsers' ) )
{
    foreach( $http->postVariable( 'DeleteApproveUserIDArray' ) as $approveUserID )
    {
        $approveStatus->removeUser( $approveUserID, $hash );
    }
}
else if ( $http->hasPostVariable( 'AddApproveUsers' ) )
{
        include_once( 'kernel/classes/ezcontentbrowse.php' );
        eZContentBrowse::browse( array( 'action_name' => 'SelectMultipleUsers',
                                        'class_array' => array ( 'user' ),
                                        'from_page' => 'ezapprove2/add_approver/' . $approveStatus->attribute( 'id' ) ),
                                 $Module );
}
else if ( $http->hasPostVariable( 'SelectedObjectIDArray' ) )
{
    foreach( $http->postVariable( 'SelectedObjectIDArray' ) as $userID )
    {
        $approveStatus->addApproveUser( $userID, $hash );
    }
}
else if ( $http->hasPostVariable( 'SubmitButton' ) )
{
    $collaborationItemID = $approveStatus->createCollaboration( $hash );
    return $Module->redirect( 'collaboration', 'item', array( 'full',
                                                              $approveStatus->attribute( 'collaborationitem_id' ) ) );
}
else if ( $http->hasPostVariable( 'CancelButton' ) )
{
    return $Module->redirect( 'collaboration', 'item', array( 'full',
                                                              $approveStatus->attribute( 'collaborationitem_id' ) ) );
}

$tpl =& templateInit();
$tpl->setVariable( 'approval_status', $approveStatus );
$tpl->setVariable( 'object', $approveStatus->attribute( 'object_version' ) );
$tpl->setVariable( 'approve_user_list', $approveStatus->approveUserList( $hash ) );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:workflow/eventtype/ezapprove2/add_approver.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'ezapprove2', 'Edit Subscription' ) ) );

?>
