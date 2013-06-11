<?php
/**
 * vshop - phpwebsite module
 *
 * See docs/AUTHORS and docs/COPYRIGHT for relevant info.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @version $Id: uninstall.php 7776 2010-06-11 13:52:58Z jtickle $
 * @author Verdon Vaillancourt <verdonv at gmail dot com>
 */

function vshop_uninstall(&$content) {

    PHPWS_DB::dropTable('vshop_depts');
    PHPWS_DB::dropTable('vshop_items');
    PHPWS_DB::dropTable('vshop_taxes');
    PHPWS_DB::dropTable('vshop_orders');
    $content[] = dgettext('vshop', 'vShop tables dropped.');

    return true;
}
?>