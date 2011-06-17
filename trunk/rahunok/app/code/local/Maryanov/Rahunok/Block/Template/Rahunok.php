<?php
/**
 * Maryanov
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@maryanov.com so we can send you a copy immediately.
 *
 * @category   Maryanov
 * @package    Maryanov_Rahunok
 * @author     Maryanov Anton
 * @copyright  Copyright (c) 2009 Maryanov Anton (http://www.maryanov.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Maryanov_Rahunok_Block_Template_Rahunok extends Mage_Core_Block_Template
{
	protected $order = null;
	protected $ua_monthes = array(
			1  => 'січня',
			2  => 'лютого',
			3  => 'березня',
			4  => 'квітня',
			5  => 'травня',
			6  => 'червня',
			7  => 'липня',
			8  => 'серпня',
			9  => 'вересня',
			10 => 'жовтня',
			11 => 'листопада',
			12 => 'грудня',
			);
	protected function _construct()
	{
		$this->setTemplate('rahunok/rahunok.phtml');
	}
	public function setOrder($order)
	{
		$this->order = $order;
	}
	public function getOrder()
	{
		return $this->order;
	}
	protected function _toHtml()
	{
		if(!is_null($this->order) && $this->order->getId() && $this->order->canInvoice())
		{
			return parent::_toHtml();
		}
		else
		{
			return '';
		}
	}
	public function num2str($inn, $stripkop = 0)
	{
		$str[100]= array('', 'сто', 'двісті', 'триста', 'чотириста', 'п\'ятсот', 'шістсот', 'сімсот', 'вісімсот', 'дев\'ятсот', 'тисяча');
		$str[11] = array(10 => 'десять', 11 => 'одинадцять', 12 => 'дванадцять', 13 => 'тринадцять', 14 => 'чотирнадцять', 15 => 'п\'ятнадцять',
				16 => 'шістнадцять', 17 => 'сімнадцять', 18 => 'вісімнадцять', 19 => 'дев\'ятнадцять');
		$str[10] = array('', '', 'двадцять', 'тридцять', 'сорок', 'п\'ятдесят', 'шістдесят', 'сімдесят', 'вісімдесят', 'дев\'яносто', 'сто');
		$sex[1] = array('', 'один', 'два', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять');
		$sex[2] = array('', 'одна', 'дві', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять');
		$forms = array(
				-1 =>array('копійка',    'копійки',        'копійок',        2),
				0  =>array('гривня',    'гривні',        'гривень',        2), // 10^0
				1  =>array('тисяча',    'тисячі',        'тисяч',        2), // 10^3
				2  =>array('мільйон',    'мільйони',        'мільйонів',    1), // 10^6
				3  =>array('мільярд',    'мільярди',        'мільярдів',    1), // 10^9
				4  =>array('трильйон',    'трильйони',    'трильйонів',    1), // 10^12
				);
		$out = $tmp = array();
		// Поехали!
		$tmp = explode('.', str_replace(',', '.', $inn));
		$rub = number_format($tmp[0], 0, '', '-');
		// нормализация копеек
		$kop = (isset($tmp[1]) ? str_pad(substr($tmp[1], 0, 2), 2, '0', STR_PAD_LEFT) : '00');
		$levels = explode('-', $rub);
		$offset = sizeof($levels) - 1;
		foreach($levels as $k => $lev)
		{
			$lev = str_pad($lev, 3, '0', STR_PAD_LEFT); // нормализация
			$ind = $offset - $k; // индекс для $forms
			if ($lev[0] != '0')
			{
				$out[] = $str[100][$lev[0]]; // сотни
			}
			$lev = $lev[1] . $lev[2];
			$lev = (int)$lev;
			if ($lev > 19)
			{ // больше девятнадцати
				$lev = '' . $lev;
				$out[] = $str[10][$lev[0]];
				$out[] = $sex[$forms[$ind][3]][$lev[1]];
			}
			else if ($lev > 9)
			{
				$out[] = $str[11][$lev];
			}
			else if ($lev > 0)
			{
				$out[] = $sex[$forms[$ind][3]][$lev];
			}
			if ($lev > 0 || $ind == 0)
			{
				$out[] = self::pluralForm($lev, $forms[$ind][0], $forms[$ind][1], $forms[$ind][2]);
			}
		}
		if ($stripkop == 0)
		{
			$out[] = $kop; // копейки
			$out[] = self::pluralForm($kop, $forms[-1][0], $forms[-1][1], $forms[-1][2] );
		}
		$out = array_diff($out, array(''));
		return implode(' ', $out);
	}
	private static function pluralForm($n, $f1, $f2, $f5) {
		$n = abs($n) % 100;
		$n1 = $n % 10;
		if ($n > 10 && $n < 20)
		{
			return $f5;
		}
		if ($n1 > 1 && $n1 < 5)
		{
			return $f2;
		}
		if ($n1 == 1)
		{
			return $f1;
		}
		return $f5;
	}
}
?>
