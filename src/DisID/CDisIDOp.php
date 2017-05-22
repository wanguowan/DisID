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

	const CONST_KEY_STRUCTURE_INFO_LENGTH 	= 'len';		//	结构信息中，长度key
	const CONST_KEY_STRUCTURE_INFO_VALUE	= 'value';		//	结构信息中，值key

	private $m_oCDisID;

	public function __construct( $oCDisID )
	{
		if ( $this->_checkCDisID( $oCDisID ) )
		{
			$this->m_oCDisID = $oCDisID;
		}
	}

	public function getDisID( $nTime = null, $bInMSSNRandom = true )
	{
		if ( $this->m_oCDisID instanceof CDisID )
		{
			//	设置基础时间
			$this->m_oCDisID->setMNMSTime( $nTime );

			if ( $bInMSSNRandom )
			{
				$this->m_oCDisID->getMNRandInMSSN();
			}

			return $this->_getDisID();
		}
		else
		{
			throw new \Exception( 'illegal CDisID instance' );
		}
	}

	public function getDisIDValue()
	{
		$this->_getDisIDValue();
	}

	public function getDisIDInstance()
	{
		return $this->m_oCDisID;
	}

	private function _getDisIDValue()
	{
		if ( $this->m_oCDisID instanceof CDisID )
		{
			$arrStructure = $this->_getDisIDStructure( $this->m_oCDisID, false );
			if ( is_array( $arrStructure ) && count( $arrStructure ) > 0 )
			{
				//	位置偏移量
				$nBitMoveLen = 0;

				//	实际DisID
				$nDisID = $this->m_oCDisID->getMNValue();

				$arrStructure = array_reverse( $arrStructure, false );
				for( $i = 0; $i < count( $arrStructure ); $i ++ )
				{
					$arrInfo = $arrStructure[ $i ];
					if ( is_array( $arrInfo )
						&& array_key_exists( self::CONST_KEY_STRUCTURE_INFO_LENGTH, $arrInfo )
						&& array_key_exists( self::CONST_KEY_STRUCTURE_INFO_VALUE, $arrInfo )
					)
					{
						$nLen 		= $arrInfo[ self::CONST_KEY_STRUCTURE_INFO_LENGTH ];
						$sKeyValue 	= $arrInfo[ self::CONST_KEY_STRUCTURE_INFO_VALUE ];
						if ( is_int( $nLen ) )
						{
							$nOr = 0;
							for( $j = 0; $j < $nLen; $j ++ )
							{
								$nOr |= 1 << $j;
							}
							$nDisIDTmp = $nDisID >> $nBitMoveLen . "\n";

							$nValue = $nDisIDTmp & $nOr;

							//	设置属性值
							call_user_func( [ $this->m_oCDisID, $sKeyValue ], $nValue );

							$nBitMoveLen += $nLen;
						}
						else
						{
							throw new \Exception( 'get disIDValue structure info error' );
						}
					}
					else
					{
						throw new \Exception( 'get disIDValue structure info error' );
					}
				}
			}
			else
			{
				throw new \Exception( 'get DisID structure fail' );
			}
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
			//	获得DisID结构信息
			$arrStructure = $this->_getDisIDStructure( $oDisID );

			if ( is_array( $arrStructure ) && count( $arrStructure ) > 0 )
			{
				//	位移偏量
				$nBitMoveLen = 0;

				//	最终生成的DisID
				$nDisID = 0;

				//	按照结构生成DisID
				for( $i = 0; $i < count( $arrStructure ); $i ++ )
				{
					$arrInfo = $arrStructure[ $i ];
					if ( is_array( $arrInfo )
						&& array_key_exists( self::CONST_KEY_STRUCTURE_INFO_VALUE, $arrInfo )
						&& array_key_exists( self::CONST_KEY_STRUCTURE_INFO_LENGTH, $arrInfo )
					)
					{
						$nLen = $arrInfo[ self::CONST_KEY_STRUCTURE_INFO_LENGTH ];
						$nValue = $arrInfo[ self::CONST_KEY_STRUCTURE_INFO_VALUE ];
						if ( is_int( $nValue ) && is_int( $nLen ) )
						{
							$nBitMoveLen += $arrInfo[ self::CONST_KEY_STRUCTURE_INFO_LENGTH ];
							$nBaseValue = $arrInfo[ self::CONST_KEY_STRUCTURE_INFO_VALUE ] << ( self::CONST_DIS_ID_BIT_LEN - $nBitMoveLen );
							$nDisID |= $nBaseValue;
						}
						else
						{
							throw new \Exception( 'get disID structure info error' );
						}
					}
					else
					{
						throw new \Exception( 'get disID structure info error' );
					}
				}

				return $nDisID;
			}
			else
			{
				throw new \Exception( 'get DisID structure fail' );
			}
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

	private function _getDisIDStructure( $oCDisID, $bNeedValue = true )
	{
		$arrStructure = [];

		if ( $oCDisID instanceof CDisID )
		{
			//	毫秒数
			$arrMSTime = [
				self::CONST_KEY_STRUCTURE_INFO_LENGTH	=> $oCDisID->getMNMSTimeBitLen(),
				self::CONST_KEY_STRUCTURE_INFO_VALUE	=> $bNeedValue ? $oCDisID->getMNMSTime() : 'setMNMSTime'
			];

			//	业务线
			$arrOpID = [
				self::CONST_KEY_STRUCTURE_INFO_LENGTH	=> $oCDisID->getMNOpIDBitLen(),
				self::CONST_KEY_STRUCTURE_INFO_VALUE	=> $bNeedValue ? $oCDisID->getMNOpID() : 'setMNOpID'
			];

			//	机房
			$arrMrID = [
				self::CONST_KEY_STRUCTURE_INFO_LENGTH	=> $oCDisID->getMNMrIDBitLen(),
				self::CONST_KEY_STRUCTURE_INFO_VALUE	=> $bNeedValue ? $oCDisID->getMNMrID() : 'setMNMrID'
			];

			//	机器
			$arrServerID = [
				self::CONST_KEY_STRUCTURE_INFO_LENGTH	=> $oCDisID->getMNServerIDBitLen(),
				self::CONST_KEY_STRUCTURE_INFO_VALUE	=> $bNeedValue ? $oCDisID->getMNServerID() : 'setMNServerID'
			];

			//	预留
			$arrLeft = [
				self::CONST_KEY_STRUCTURE_INFO_LENGTH	=> $oCDisID->getMNLeftBitLen(),
				self::CONST_KEY_STRUCTURE_INFO_VALUE	=> $bNeedValue ? $oCDisID->getMNLeft() : 'setMNLeft'
			];

			//	毫秒內序列号
			$arrInMSSN = [
				self::CONST_KEY_STRUCTURE_INFO_LENGTH	=> $oCDisID->getMNInMSSNBitLen(),
				self::CONST_KEY_STRUCTURE_INFO_VALUE	=> $bNeedValue ? $oCDisID->getMNInMSSN() : 'setMNInMSSN'
			];

			$arrStructure[] = $arrMSTime;
			$arrStructure[] = $arrOpID;
			$arrStructure[] = $arrMrID;
			$arrStructure[] = $arrServerID;
			$arrStructure[] = $arrLeft;
			$arrStructure[] = $arrInMSSN;
		}
		else
		{
			throw new \Exception( 'illegal CDisID instance' );
		}

		return $arrStructure;
	}
}