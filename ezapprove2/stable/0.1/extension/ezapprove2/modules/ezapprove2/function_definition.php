<?php
//
// Created on: <05-Jan-2006 13:04:38 hovik>
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

/*! \file function_definition.php
*/

$FunctionList = array();

$FunctionList['is_approver'] = array( 'name' => 'is_approver',
                                      'operation_types' => array( 'read' ),
                                      'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/ezapprove2/modules/ezapprove2/ezapprovefunctioncollection.php',
                                                              'class' => 'eZApproveFunctionCollection',
                                                              'method' => 'isApprover' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'approve_status_id',
                                                                    'type' => 'integer',
                                                                    'required' => true ),
                                                             array( 'name' => 'user_id',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => eZUser::currentUserID() ) ) );

$FunctionList['have_set_status'] = array( 'name' => 'have_set_status',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/ezapprove2/modules/ezapprove2/ezapprovefunctioncollection.php',
                                                                  'class' => 'eZApproveFunctionCollection',
                                                                  'method' => 'haveSetStatus' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array( array( 'name' => 'collabitem_id',
                                                                        'type' => 'integer',
                                                                        'required' => false,
                                                                        'default' => false ),
                                                                 array( 'name' => 'approve_status_id',
                                                                        'type' => 'integer',
                                                                        'required' => false,
                                                                        'default' => false ),
                                                                 array( 'name' => 'user_id',
                                                                        'type' => 'integer',
                                                                        'required' => false,
                                                                        'default' => eZUser::currentUserID() ) ) );

$FunctionList['approve_status'] = array( 'name' => 'approve_status',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/ezapprove2/modules/ezapprove2/ezapprovefunctioncollection.php',
                                                                 'class' => 'eZApproveFunctionCollection',
                                                                 'method' => 'approveStatus' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( array( 'name' => 'collaboration_id',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => false ),
                                                                array( 'name' => 'contentobject_id',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => false ),
                                                                array( 'name' => 'contentobject_version',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => false ) ) );


$FunctionList['approve_status_map'] = array( 'name' => 'approve_status_map',
                                             'operation_types' => array( 'read' ),
                                             'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/ezapprove2/modules/ezapprove2/ezapprovefunctioncollection.php',
                                                                     'class' => 'eZApproveFunctionCollection',
                                                                     'method' => 'approveStatusMap' ),
                                             'parameter_type' => 'standard',
                                             'parameters' => array( ) );

?>
