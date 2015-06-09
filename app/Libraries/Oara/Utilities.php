<?php
/**
   The goal of the Open Affiliate Report Aggregator (OARA) is to develop a set 
   of PHP classes that can download affiliate reports from a number of affiliate networks, and store the data in a common format.
   
    Copyright (C) 2014  Fubra Limited
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.
    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
	Contact
	------------
	Fubra Limited <support@fubra.com> , +44 (0)1252 367 200
**/	
/**
 * Utilities Class
 *
 * @author     Carlos Morillo Merino
 * @category   Oara
 * @copyright  Fubra Limited
 * @version    Release: 01.00
 *
 */
class Oara_Utilities {
	/**
	 * confirmed status
	 * @var string
	 */
	const STATUS_CONFIRMED = 'confirmed';
	/**
	 * pending status
	 * @var string
	 */
	const STATUS_PENDING = 'pending';
	/**
	 * declined status
	 * @var string
	 */
	const STATUS_DECLINED = 'declined';
	/**
	 * paid status
	 * @var string
	 */
	const STATUS_PAID = 'paid';

	/**
	 * It returns the value's position in the array for a type.
	 * @param array $array
	 * @param $type
	 * @param $value
	 * @return object
	 */
	public static function arrayFetchValue(array $array, $type, $value) {
		$returnValue = null;
		$i = 0;
		$enc = false;
		while ($i < count($array) && !$enc) {
			$element = $array[$i];
			$elementValue = $element[$type];
			if ($value == $elementValue) {
				$enc = true;
				$returnValue = $element;
			}
			$i++;
		}
		return $returnValue;
	}
	/**
	 * Soap results to Data Base results
	 * @param array $soapResults
	 * @param array $converterRules
	 * @return array
	 */
	public static function soapConverter(array $soapResults, array $converterRules) {
		$convertion = array();
		foreach ($soapResults as $soapResult) {
			$objectValue = array();
			foreach ($converterRules as $key => $rule) {
				$groupRule = explode(',', $rule);
				foreach ($groupRule as $individualRule) {
					if (isset($soapResult->$key)) {
						$attribute = $soapResult->$key;
						if ($attribute !== null) {
							$objectValue[$individualRule] = $attribute;
						}
					}
				}

			}
			$convertion[] = $objectValue;
		}
		unset($soapResults);
		return $convertion;
	}
	/**
	 *
	 * Return an array with the different years
	 * @param $starDate
	 * @param $endDate
	 */
	public static function yearsOfDifference(Zend_Date $starDate = null, Zend_Date $endDate = null) {
		if ($starDate->compare($endDate) > 0) {
			throw new Exception('The start date can not be later than the end date');
		}

		$difference = (int) ($endDate->get(Zend_Date::YEAR) - $starDate->get(Zend_Date::YEAR));
		$dateArray = array();
		$dateArray[] = clone $starDate;
		/**If there are more than 1 month of difference ,
		 the next  element starts in the first day of the month**/
		for ($i = 0; $i < $difference; $i++) {
			$auxDate = clone $starDate;
			$auxDate->addYear($i + 1);
			$auxDate->setDay(1);
			$auxDate->setMonth(1);
			$dateArray[] = $auxDate;
		}

		return $dateArray;
	}
	/**
	 * Return an array with the different dates between two dates, one element per month.
	 * @param Zend_Date $starDate
	 * @param Zend_Date $endDate
	 * @return array
	 */
	public static function monthsOfDifference(Zend_Date $startDate = null, Zend_Date $endDate = null, $gap = 1) {
		if ($startDate->compare($endDate) > 0) {
			throw new Exception('The start date can not be later than the end date');
		}

		$monthsOfDifferenceBetweenYears = ($endDate->get(Zend_Date::YEAR) - $startDate->get(Zend_Date::YEAR)) * 12;
		$difference = (int) ((($endDate->get(Zend_Date::MONTH) + $monthsOfDifferenceBetweenYears) - $startDate->get(Zend_Date::MONTH)) / $gap);
		$dateArray = array();
		$dateArray[] = clone $startDate;
		/**If there are more than 1 month of difference ,
		 the next  element starts in the first day of the month**/
		for ($i = 0; $i < $difference; $i++) {
			$auxDate = clone $startDate;
			$auxDate->addMonth(($i + 1) * $gap);
			$auxDate->setDay(1);
			$dateArray[] = $auxDate;
		}

		return $dateArray;
	}
	/**
	 * Return an array with the different dates between two dates, one element per day.
	 * @param Zend_Date $starDate
	 * @param Zend_Date $endDate
	 * @return array
	 */
	public static function daysOfDifference(Zend_Date $starDate = null, Zend_Date $endDate = null) {
		if ($starDate->compare($endDate) > 0) {
			throw new Exception('The start date can not be later than the end date');
		}
		$difference = intval(self::numberOfDaysBetweenTwoDates($starDate, $endDate));
		$dateArray = array();
		$dateArray[] = clone $starDate;
		/**If there are more than 1 month of difference ,
		 the next  element starts in the first day of the month**/
		for ($i = 0; $i < $difference; $i++) {
			$auxDate = clone $starDate;
			$auxDate->addDay($i + 1);
			$dateArray[] = $auxDate;
		}

		return $dateArray;
	}
	/**
	 * Return an array with the different dates between two dates, one element per week.
	 * @param Zend_Date $starDate
	 * @param Zend_Date $endDate
	 * @return array
	 */
	public static function weeksOfDifference(Zend_Date $starDate = null, Zend_Date $endDate = null) {
		if ($starDate->compare($endDate) > 0) {
			throw new Exception('The start date can not be later than the end date');
		}
		$auxStartDate = clone $starDate;
		$weekDay = $starDate->get(Zend_Date::WEEKDAY_DIGIT);
		$subDays = ($weekDay + 6) % 7;
		$auxStartDate->subDay($subDays);

		$difference = intval(self::numberOfDaysBetweenTwoDates($auxStartDate, $endDate) / 7);

		$dateArray = array();
		$dateArray[] = clone $starDate;
		/**If there are more than 1 month of difference ,
		 the next  element starts in the first day of the month**/
		for ($i = 0; $i < $difference; $i++) {
			$auxDate = clone $auxStartDate;
			$auxDate->addWeek($i + 1);
			$dateArray[] = $auxDate;
		}

		return $dateArray;
	}
	/**
	 * Return the number of days between two Dates.
	 * @param Zend_Date $starDate
	 * @param Zend_Date $endDate
	 * @return int
	 */
	public static function numberOfDaysBetweenTwoDates(Zend_Date $starDate = null, Zend_Date $endDate = null) {
		$starDate = clone $starDate;
		$endDate = clone $endDate;
		if ($starDate->compare($endDate) > 0) {
			throw new Exception('The start date can not be later than the end date');
		}
		$diff = $endDate->getTimestamp() - $starDate->getTimestamp();
		return $diff / 60 / 60 / 24;
	}
	/**
	 * Clone the array.
	 * @param array $cloneArray
	 * @return array
	 */
	public static function cloneArray(array $cloneArray) {
		$returnArray = array();
		foreach ($cloneArray as $element) {
			$returnArray[] = clone $element;
		}
		return $returnArray;
	}
	/**
	 *
	 * Compare date by strings
	 * @param unknown_type $a
	 * @param unknown_type $b
	 */
	public static function compareDates($a, $b) {
		return strcmp($a['date'], $b['date']);
	}
	/**
	 * Bubble Sort, order ASC.
	 * @param array $dataArray
	 * @return array
	 */
	public static function registerBubbleSort(array $dataArray) {
		usort($dataArray, array("Oara_Utilities", "compareDates"));

		return $dataArray;
	}
	/**
	 * Returns true if there is some attribute distinct than zero.
	 * @param array $entity
	 * @param $attributes
	 * @return boolean
	 */
	public static function attributeDistinctThanZero($entity, array $attributes) {
		$result = false;
		$i = 0;
		$long = count($attributes);
		while ($i < $long && !$result) {
			if ($entity->$attributes[$i] != 0) {
				$result = true;
			}
			$i++;
		}

		return $result;
	}
	/**
	 * Get the day for this transaction array
	 * @param map $dateArray
	 * @param Zend_Date $date
	 * @return array
	 */
	public static function getDayFromArray($merchantId, &$dateArray, Zend_Date $date, $delete = false) {
		$resultArray = array();
		if (isset($dateArray[$merchantId])) {
			$dateString = $date->toString("yyyy-MM-dd");
			if (isset($dateArray[$merchantId][$dateString])) {
				if ($delete) {
					$resultArray = $dateArray[$merchantId][$dateString];
					unset($dateArray[$merchantId][$dateString]);
				} else {
					$resultArray = $dateArray[$merchantId][$dateString];
				}
			}
		}
		return $resultArray;
	}

	/**
	 * Check If the register has interesting information
	 * @param array $register
	 * @param array $properties
	 * @return boolean
	 */
	public static function checkRegister(array $register) {
		$ok = false;
		$i = 0;
		$properties = array(
			'click_number',
			'impression_number',
			'transaction_number',
			'transaction_confirmed_value',
			'transaction_confirmed_commission',
			'transaction_pending_value',
			'transaction_pending_commission',
			'transaction_declined_value',
			'transaction_declined_commission',
			'transaction_paid_value',
			'transaction_paid_commission'
		);
		while ($i < count($properties) && !$ok) {
			if ($register[$properties[$i]] != 0) {
				$ok = true;
			}
			$i++;
		}
		return $ok;
	}
	/**
	 * Filter the transactionList per day
	 * @param array $transactionList
	 * @return array
	 */
	public static function transactionMapPerDay(array $transactionList) {
		$transactionMap = array();
		foreach ($transactionList as $transaction) {
			if (!isset($transactionMap[$transaction['merchantId']])) {
				$transactionMap[$transaction['merchantId']] = array();
			}
			$dateString = substr($transaction['date'], 0, 10);
			if (!isset($transactionMap[$transaction['merchantId']][$dateString])) {
				$transactionMap[$transaction['merchantId']][$dateString] = array();
			}
			$transactionMap[$transaction['merchantId']][$dateString][] = $transaction;
		}

		return $transactionMap;
	}

	/**
	 * Parse Double, delete odd characters.
	 * @param $data
	 * @return double
	 */
	public static function parseDouble($data) {
		$data = str_replace(" ", "", trim($data));
		$double = 0;
		if ($data != null) {

			$bits = explode(",", trim($data)); // split input value up to allow checking

			$last = strlen($bits[count($bits) - 1]); // gets part after first comma (thousands (or decimals if incorrectly used by user)
			if ($last < 3) { // checks for comma being used as decimal place
				$convertnum = str_replace(",", ".", trim($data));
			} else {
				$convertnum = str_replace(",", "", trim($data));
			}
			$double = number_format((float) $convertnum, 2, '.', '');
		}
		return $double;
	}
	/**
	 * Makes directory, returns TRUE if exists or made
	 *
	 * @param string $pathname The directory path.
	 * @return boolean returns TRUE if exists or made or FALSE on failure.
	 */
	public static function mkdir_recursive($dir, $mode) {
		$return = false;
		if (is_dir($dir) || mkdir($dir, $mode, true)) {
			$return = true;
		}

		return $return;
	}

	/**
	 *
	 * Glue the parsed url and returns the string
	 * @param string $parsed
	 */
	public static function glue_url($parsed) {
		if (!is_array($parsed))
			return false;
		$uri = isset($parsed['scheme']) ? $parsed['scheme'].':'.((strtolower($parsed['scheme']) == 'mailto') ? '' : '//') : '';
		$uri .= isset($parsed['user']) ? $parsed['user'].($parsed['pass'] ? ':'.$parsed['pass'] : '').'@' : '';
		$uri .= isset($parsed['host']) ? $parsed['host'] : '';
		$uri .= isset($parsed['port']) ? ':'.$parsed['port'] : '';
		if (isset($parsed['path'])) {
			$uri .= (substr($parsed['path'], 0, 1) == '/') ? $parsed['path'] : '/'.$parsed['path'];
		}
		$uri .= isset($parsed['query']) ? '?'.$parsed['query'] : '';
		$uri .= isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '';
		return $uri;
	}
	/**
	 *
	 * Get the query and builds an array.
	 * @param array $op
	 */
	public static function parseUrlQuery($str) {
		$op = array();
		$str = str_replace("&amp", "&", $str);
		$pairs = explode("&", $str);
		foreach ($pairs as $pair) {
			$values = explode("=", $pair);
			if (isset($values[0])){
				$op[urldecode($values[0])] = "";
			}
			if (isset($values[1])){
				$op[urldecode($values[0])] = urldecode($values[1]);
			}
		}
		return $op;
	}

	/**
	 * Calculate the number of iterations needed
	 * @param $rowAvailable
	 * @param $rowsReturned
	 */
	public static function calculeIterationNumber($rowAvailable, $rowsReturned) {
		$iterationDouble = (double) ($rowAvailable / $rowsReturned);
		$iterationInt = (int) ($rowAvailable / $rowsReturned);
		if ($iterationDouble > $iterationInt) {
			$iterationInt++;
		}
		return $iterationInt;
	}
	/**
	 *
	 * Clasify the arguments given to the command line
	 * @param array $args
	 */
	public static function arguments($args) {
		$ret = array(
			'exec'		 => '',
			'options'	 => array(),
			'flags'		 => array(),
			'arguments'	 => array(),
		);

		$ret['exec'] = array_shift($args);

		while (($arg = array_shift($args)) != NULL) {
			// Is it a option? (prefixed with --)
			if (substr($arg, 0, 2) === '--') {
				$option = substr($arg, 2);

				// is it the syntax '--option=argument'?
				if (strpos($option, '=') !== FALSE)
					array_push($ret['options'], explode('=', $option, 2));
				else
					array_push($ret['options'], $option);

				continue;
			}

			// Is it a flag or a serial of flags? (prefixed with -)
			if (substr($arg, 0, 1) === '-') {
				for ($i = 1; isset($arg[$i]); $i++)
					$ret['flags'][] = $arg[$i];

				continue;
			}

			// finally, it is not option, nor flag
			$ret['arguments'][] = $arg;
			continue;
		}
		return $ret;
	}

}
