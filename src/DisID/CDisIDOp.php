<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 5/18/17
 * Time: 6:07 PM
 */

namespace wan\DisID;

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

	public function getDisID( $nTime = null )
	{
		if ( $this->m_oCDisID instanceof CDisID )
		{
			//	设置基础时间
			$this->m_oCDisID->setMNMSTime( $nTime );

			return $this->_getDisID();
		}
		else
		{
			throw new \Exception( 'illegal CDisID instance' );
		}
	}

	private function _getDisID()
	{
		$oDisID = $this->m_oCDisID;

		if ( $oDisID instanceof  CDisID )
		{
			//	获得距离baseTime毫秒时间
			$nMSTime = $oDisID->getMNMSTime();

			//	获得baseTime毫秒时间bit长度
			$nMSTimeBitLen = $oDisID->getMNMSTimeBitLen();

			//	获得业务线编号
			$nOpID = $oDisID->getMNOpID();

			//	获得业务编号bit长度
			$nOpIDBitLen = $oDisID->getMNOpIDBitLen();

			//	获得机房编号
			$nMrID = $oDisID->getMNMrID();

			//	获得机房编号bit长度
			$nMrIDBitLen = $oDisID->getMNMrIDBitLen();

			//	获得预留值
			$nLeft = $oDisID->getMNLeft();

			//	获得预留址bit长度
			$nLeftBitLen = $oDisID->getMNLeftBitLen();

			//	获得毫秒内序列号
			$nInMSSN = $oDisID->getMNInMSSN();

			//	获得毫秒内序列号bit长度
			$nInMSSNBitLen = $oDisID->getMNInMSSNBitLen();

			//	位移偏量
			$nBitMoveLen = 0;

			//	最终生成的DisID
			$nDidID = 0;

			//	计算baseTime
			$nBitMoveLen = $nMSTimeBitLen;
			$nBaseTimeInt = $nMSTime << ( self::CONST_DIS_ID_BIT_LEN - $nBitMoveLen );
			$nDidID = $nBaseTimeInt;

			//	计算业务线编号
			$nBitMoveLen += $nOpIDBitLen;
			$nOpIDInt = $nOpID << ( self::CONST_DIS_ID_BIT_LEN - $nBitMoveLen );
			$nDidID |= $nOpIDInt;

			//	计算机房编号
			$nBitMoveLen += $nMrIDBitLen;
			$nMrIDInt = $nMrID << ( self::CONST_DIS_ID_BIT_LEN - $nBitMoveLen );
			$nDidID |= $nMrIDInt;

			//	计算预留值
			$nBitMoveLen += $nLeftBitLen;
			$nLeftInt = $nLeft << ( self::CONST_DIS_ID_BIT_LEN - $nBitMoveLen );
			$nDidID |= $nLeftInt;

			//	计算毫秒内序列号
			$nBitMoveLen += $nInMSSNBitLen;
			$nInMSSNInt = $nInMSSN << ( self::CONST_DIS_ID_BIT_LEN - $nBitMoveLen );
			$nDidID |= $nInMSSNInt;

			return $nDidID;
		}
		else
		{
			throw new \Exception( 'illegal CDisID instance' );
		}
	}

	private function _checkCDisID( $oCDisID )
	{
		$bRtn = false;

		if ( $oCDisID instanceof CDisID )
		{
			$nMSTimeBitLen 		= $oCDisID->getMNMSTimeBitLen();
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