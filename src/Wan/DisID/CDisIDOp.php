<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 5/18/17
 * Time: 6:07 PM
 */

namespace Wan\DisID;

class CDisIDOp {
	const CONST_DIS_ID_BIT_LEN = 64;		//	disID 总位数64位

	private $m_oCDisID;

	public function __construct( $oCDisID )
	{
		if ( $this->_checkCDisID( $oCDisID ) )
		{
			$this->m_oCDisID = $oCDisID;
		}
	}



	private function _checkCDisID( $oCDisID )
	{
		$bRtn = false;

		if ( $oCDisID instanceof CDisID )
		{
			$nMSTimeBitLen 		= $oCDisID->getMNMSTime();
			$nOpIDBitLen		= $oCDisID->getMNOpIDBitLen();
			$nMrIDBitLen		= $oCDisID->getMNMrIDBitLen();
			$nServerIDBitLen	= $oCDisID->getMNServerIDBitLen();
			$nInMSSNBitLen		= $oCDisID->getMNInMSSNBitLen();
			$nLeftBitLen		= $oCDisID->getMNLeftBitLen();

			$nTotalLen = $nMSTimeBitLen + $nOpIDBitLen + $nMrIDBitLen +
				$nServerIDBitLen + $nInMSSNBitLen + $nLeftBitLen;

			if ( $nTotalLen !== self::CONST_DIS_ID_BIT_LEN )
			{
				throw new \Exception( 'illegal CDisID total len' );
			}
			else
			{
				$bRtn = true;
			}
		}
		else
		{
			throw new \Exception( 'illegal CDisID instance' );
		}

		return $bRtn;
	}
}