<?php
namespace In2code\Powermail\ViewHelpers\Condition;

/**
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class OrViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * OR viewhelper for if widget in fluid
	 *
	 * @param 	array		Array with strings
	 * @param 	string		String to compare (if empty, just check array if there are values)
	 * @return 	boolean		true/false
	 */
	public function render($array, $string = NULL) {
		foreach ((array) $array as $value) {
			if (!$string && $value) {
				return TRUE;
			}
			if ($string && $value == $string) {
				return TRUE;
			}
		}
		return FALSE;
	}
}