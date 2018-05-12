<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * VoIP Domain exceptions filter module. This module add the filter calls related
 * to exceptions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Exceptions
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add exception's filters
 */
framework_add_filter ( "page_menu_registers", "exceptions_menu");
framework_add_filter ( "get_exceptions", "get_exceptions");

/**
 * Function to add entry to registers menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function exceptions_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "type" => "entry", "icon" => "thumbs-up", "href" => "/exceptions", "text" => __ ( "Exceptions"))));
}

/**
 * Function to get exceptions filtered by ID, name or number.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function get_exceptions ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( array_key_exists ( "id", $parameters))
  {
    $where .= " AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"]);
  }
  if ( array_key_exists ( "text", $parameters))
  {
    $where .= " AND `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"])))) . "%'";
  }
  if ( array_key_exists ( "number", $parameters))
  {
    $where .= " AND `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "'";
  }

  /**
   * Check into database if exceptions exists
   */
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    while ( $group = $result->fetch_assoc ())
    {
      $buffer = array_merge ( ( is_array ( $buffer) ? $buffer : array ()), array ( $data));
    }
  }
  return $buffer;
}
?>
