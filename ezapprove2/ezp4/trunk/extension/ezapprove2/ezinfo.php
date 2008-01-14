<?php
//
// Definition of eZApprove2Info class
//
// Created on: <01-Dec-2006 13:32:48 hovik>
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

/*! \file ezinfo.php
*/

/*!
  \class eZApprove2Info ezinfo.php
  \brief The class eZApprove2Info does

*/

class eZApprove2Info
{
    static function info()
    {
        return array( 'name' => 'eZ Approve 2',
                      'version' => '0.8.0',
                      'copyright' => 'Copyright © 2006 eZ systems',
                      'info_url' => 'http://ez.no/community/contribs/workflow/ezapprove2',
                      'license' => 'GPL version 2' );
    }
}

?>
