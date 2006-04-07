<?php
//
// Definition of eZXApproveStatusUserLink class
//
// Created on: <12-Dec-2005 22:19:00 hovik>
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

/*! \file ezxapprovestatususerlink.php
*/

/*!
  \class eZXApproveStatusUserLink ezxapprovestatususerlink.php
  \brief The class eZXApproveStatusUserLink does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezxapprovestatus.php' );

define( 'eZXApproveStatusUserLink_RoleCreator', 0 );
define( 'eZXApproveStatusUserLink_RoleApprover', 1 );

define( 'eZXApproveStatusUserLink_StatusNone', 0 );
define( 'eZXApproveStatusUserLink_StatusApproved', 1 );
define( 'eZXApproveStatusUserLink_StatusDiscarded', 2 );
define( 'eZXApproveStatusUserLink_StatusNewDraft', 3 );

define( 'eZXApproveStatusUserLink_MessageMissing', 0 );
define( 'eZXApproveStatusUserLink_MessageCreated', 1 );

class eZXApproveStatusUserLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZXApproveStatusUserLink( $rows = array() )
    {
        $this->eZPersistentObject( $rows );
    }

    /*!
     \reimp
    */
    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "approve_id" => array( 'name' => 'ApproveID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "approve_status" => array( 'name' => 'ApproveStep',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         "approve_role" => array( 'name' => 'ApproveRole',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'hash' => array( 'name' => 'Hash',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "user_id" => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "message_link_created" => array( 'name' => 'MessageLinkCreated',
                                                                          'datatype' => 'integer',
                                                                          'default' => 0,
                                                                          'required' => true ),
                                         "action" => array( 'name' => 'Action',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ) ),
                      "keys" => array( "id" ),
                      'increment_key' => 'id',
                      'function_attributes' => array( 'user' => 'user'),
                      "increment_key" => "id",
                      'sort' => array( 'id' => 'asc' ),
                      "class_name" => "eZXApproveStatusUserLink",
                      "name" => "ezx_approve_status_user_link" );

    }

    function &user()
    {
        $retVal = eZUser::fetch( $this->attribute( 'user_id' ) );
        return $retVal;
    }

    function fetchByUserID( $userID, $approveID, $approveStatus, $hash = false, $asObject = true )
    {
        $cond = array( 'user_id' => $userID,
                       'approve_id' => $approveID );
        if ( $approveStatus !== false )
        {
            $cond['approve_role'] = $approveStatus;
        }
        if ( $hash !== false )
        {
            $cond['hash'] = $hash;
        }

        return eZPersistentObject::fetchObject( eZXApproveStatusUserLink::definition(),
                                                null,
                                                $cond,
                                                $asObject );
    }

    function fetchByCollaborationID( $userID, $collaborationID, $approveStatus, $asObject = true )
    {
        $db = eZDB::instance();
        $sql = "SELECT ezx_approve_status_user_link.*
                FROM ezx_approve_status_user_link, ezx_approve_status
                WHERE ezx_approve_status_user_link.approve_id = ezx_approve_status.id AND
                      ezx_approve_status.collaborationitem_id = '" . $db->escapeString( $collaborationID ) . "' AND
                      ezx_approve_status_user_link.user_id = '" . $db->escapeString( $userID ) . "'";
        if ( $approveStatus !== false )
        {
            $sql .= " AND ezx_approve_status_user_link.approve_role = '" . $db->escapeString( $approveStatus ) . "'";
        }

        $resultSet = $db->arrayQuery( $sql );

        if ( count( $resultSet ) == 1 )
        {
            return new eZXApproveStatusUserLink( $resultSet[0] );
        }

        return false;
    }

    function create( $userID, $approveID, $approveRole, $hash = '' )
    {
        return new eZXApproveStatusUserLink( array( 'approve_id' => $approveID,
                                                    'approve_status' => eZXApproveStatusUserLink_StatusNone,
                                                    'approve_role' => $approveRole,
                                                    'user_id' => $userID,
                                                    'hash' => $hash ) );
    }

    /*!
     \static
     Approve status name map
    */
    function statusNameMap()
    {
        return array( eZXApproveStatusUserLink_StatusNone => ezi18n( 'ezapprove2', 'None' ),
                      eZXApproveStatusUserLink_StatusApproved => ezi18n( 'ezapprove2', 'Approve' ),
                      eZXApproveStatusUserLink_StatusDiscarded => ezi18n( 'ezapprove2', 'Discard' ),
                      eZXApproveStatusUserLink_StatusNewDraft => ezi18n( 'ezapprove2', 'New Draft' ) );
    }
}

?>
