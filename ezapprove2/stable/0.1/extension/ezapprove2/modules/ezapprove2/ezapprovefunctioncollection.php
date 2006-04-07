<?php
//
// Definition of eZApproveFunctionCollection class
//
// Created on: <05-Jan-2006 15:04:29 hovik>
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

/*! \file ezapprovefunctioncollection.php
*/

/*!
  \class eZApproveFunctionCollection ezapprovefunctioncollection.php
  \brief The class eZApproveFunctionCollection does

*/
include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezxapprovestatus.php' );

class eZApproveFunctionCollection
{
    /*!
     \static

     Check if a user is an approver in the specified eZXApproveStatus
     */
    function isApprover( $approveStatusID, $userID )
    {
        $result = false;
        if ( eZXApproveStatusUserLink::fetchByUserID( $userID,
                                                      $approveStatusID,
                                                      eZXApproveStatusUserLink_RoleApprover ) )
        {
            $result = true;
        }

        return array( 'result' => $result );
    }

    /*!
     \static

     Check if the user has set the approve status
    */
    function haveSetStatus( $collabItemID, $approveStatusID, $userID )
    {
        $result = false;

        if ( $collabItemID !== false )
        {
            $approveUserLink = eZXApproveStatusUserLink::fetchByCollaborationID( $userID, $collabItemID, false );
        }

        if ( $approveStatusID !== false )
        {
            $approveUserLink = eZXApproveStatusUserLink::fetchByUserID( $userID,
                                                                        $approveStatusID,
                                                                        false );
        }

        if ( $approveUserLink )
        {
            if ( $approveUserLink->attribute( 'approve_status' ) != eZXApproveStatusUserLink_StatusNone )
            {
                $result = true;
            }
        }

        return array( 'result' => $result );

    }

    /*!
    Fetch eZXApproveStatus object by collaboration ID

    \param collaboration ID

    \return eZXApproveStatus object
    */
    function approveStatus( $collaborationItemID = false, $contentObjectID = false, $contentObjectVersion = false )
    {
        include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezxapprovestatus.php' );

        $result = false;
        if ( $collaborationItemID !== false )
        {
            $result = eZXApproveStatus::fetchByCollaborationItem( $collaborationItemID );
        }
        else if ( $contentObjectID !== false )
        {
            $result = eZXApproveStatus::fetchByContentObjectID( $contentObjectID, $contentObjectVersion );
        }

        return array( 'result' => $result );
    }

    /*!
     Get Approve status name map
    */
    function approveStatusMap()
    {
        include_once( eZExtension::baseDirectory() . '/ezapprove2/classes/ezxapprovestatus.php' );
        return array( 'result' => eZXApproveStatus::statusNameMap() );
    }
}

?>
