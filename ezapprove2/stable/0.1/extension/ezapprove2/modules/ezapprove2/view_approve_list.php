<?php
//
// Created on: <04-Jan-2006 13:18:42 hovik>
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

/*! \file view_approve_list.php
*/

include_once( 'kernel/common/template.php' );

include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezxapprovestatus.php' );
include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezapprove2event.php' );

$Module =& $Params['Module'];

$http =& eZHTTPTool::instance();

$userParameters = $Params['UserParameters'];
$statusFilter = isset( $userParameters['statusFilter'] ) ? explode( ',', $userParameters['statusFilter'] ) : array( -1 );
$offset = isset( $userParameters['offset'] ) ? $userParameters['offset'] : 0;
$limitKey = isset( $userParameters['limit'] ) ? $userParameters['limit'] : '1';
$limitList = array ( '1' => 10,
                     '2' => 25,
                     '3' => 50 );

$limit = $limitList[(string)$limitKey];

$viewParameters = array( 'offset' => $offset,
                         'limitkey' => $limitKey );

$userID = eZUser::currentUserID();
$approveStatusList = eZXApproveStatus::fetchListByUserID( $userID, $offset, $limit );
$approveStatusCount = eZXApproveStatus::fetchCountByUserID( $userID );

$allowedApproveStatusList = array( eZXApproveStatusUserLink_StatusApproved,
                                   eZXApproveStatusUserLink_StatusDiscarded );

if ( $http->hasPostVariable( 'UpdateApproveStatusList' ) )
{
    foreach( $approveStatusList as $approveStatus )
    {
        if ( $http->hasPostVariable( 'ApproveStatus_' . $approveStatus->attribute( 'id' ) ) )
        {
            if ( in_array( $http->postVariable( 'ApproveStatus_' . $approveStatus->attribute( 'id' ) ),
                           $allowedApproveStatusList ) )
            {
                $userApproveStatus = $approveStatus->attribute( 'user_approve_status' );
                $userApproveStatus->setAttribute( 'approve_status', $http->postVariable( 'ApproveStatus_' . $approveStatus->attribute( 'id' ) ) );
                $userApproveStatus->sync();
            }
        }
    }
}

$tpl =& templateInit();
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'approve_status_list', $approveStatusList );
$tpl->setVariable( 'approve_status_count', $approveStatusCount );
$tpl->setVariable( 'status_name_map', eZXApproveStatusUserLink::statusNameMap() );
$tpl->setVariable( 'limit', $limit );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:workflow/eventtype/ezapprove2/view_approve_list.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'ezxnewsletter', 'Approve List' ) ) );


?>
