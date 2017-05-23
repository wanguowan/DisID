<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 5/18/17
 * Time: 4:58 PM
 */
namespace wan\DisID;


class CDisID {

	//	基准时间
	private $m_nMSBaseTime;

	//	距离基准时间毫秒数
	private $m_nMSTime;

	//	基准时间数据长度
	private $m_nMSTimeBitLen;

	//	业务ID
	private $m_nOpID;

	//	业务ID数据长度
	private $m_nOpIDBitLen;

	//	机房ID
	private $m_nMrID;

	//	机房ID数据长度
	private $m_nMrIDBitLen;

	//	服务器ID
	private $m_nServerID;

	//	服务器ID数据长度
	private $m_nServerIDBitLen;

	//	毫秒内序列号
	private $m_nInMSSN;

	//	毫秒内序列号长度
	private $m_nInMSSNBitLen;

	//	预留数据
	private $m_nLeft;

	//	预留数据数据长度
	private $m_nLeftBitLen;

	//	baseTime修改标示
	private $m_bBaseTimeChange;

	//	DisID value
	private $m_nValue;

	/**
	 * @return mixed
	 */
	public function getMNMSBaseTime()
	{
		if ( ! is_int( $this->m_nMSBaseTime ) )
		{
			$this->m_nMSBaseTime = strtotime( '2017-01-01 00:00:00' ) * 1000;
		}

		return $this->m_nMSBaseTime;
	}

	/**
	 * @param string $m_sMSBaseTime  format: YYYY-mm-dd
	 */
	public function setMNMSBaseTime( $m_sMSBaseTime )
	{
		if ( is_string( $m_sMSBaseTime )
			&& strlen( $m_sMSBaseTime ) > 0
			&& 0 === strcasecmp( date( 'Y-m-d', strtotime( $m_sMSBaseTime ) ), $m_sMSBaseTime )
		)
		{
			$this->m_bBaseTimeChange = true;
			$this->m_nMSBaseTime = intval( strtotime( $m_sMSBaseTime ) * 1000 );
			return $this;
		}
		else
		{
			throw new \Exception( 'baseTime params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNMSTime()
	{
		if ( is_int( $this->m_nMSTime ) )
		{
			if ( ! $this->m_bBaseTimeChange )
			{
				return $this->m_nMSTime;
			}
			else
			{
				throw new \Exception( 'baseTime has change, please change baseTime first and set msTime second;' );
			}
		}
		else
		{
			throw new \Exception( 'illegal MSTime' );
		}
	}

	/**
	 * @param mixed $m_nMSTime
	 */
	public function setMNMSTime( $m_nMSTime = null )
	{
		if ( is_null( $m_nMSTime ) )	//	若未设置，使用当前时间
		{
			$fTimestampNow = microtime( true );
			$m_nMSTime = intval( $fTimestampNow * 1000 - $this->getMNMSBaseTime() );
		}
		else if ( ! is_numeric( $m_nMSTime ) || intval( $m_nMSTime ) != $m_nMSTime )
		{
			throw new \Exception( 'MSTime params error' );
		}

		$m_nMSTime = intval( $m_nMSTime );
		if ( $this->_checkValueOutOfLimit( $m_nMSTime, $this->getMNMSTimeBitLen() ) )
		{
			$this->m_nMSTime = intval( $m_nMSTime );
			$this->m_bBaseTimeChange = false;

			return $this;
		}
		else
		{
			throw new \Exception( 'MSTime value out of limit' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNMSTimeBitLen()
	{
		if ( is_int( $this->m_nMSTimeBitLen ) )
		{
			return $this->m_nMSTimeBitLen;
		}
		else
		{
			throw new \Exception( 'illegal MSTimeBitLen' );
		}
	}

	/**
	 * @param mixed $m_nMSTimeBitLen
	 */
	public function setMNMSTimeBitLen( $m_nMSTimeBitLen )
	{
		if ( is_numeric( $m_nMSTimeBitLen ) && intval( $m_nMSTimeBitLen ) == $m_nMSTimeBitLen )
		{
			$this->m_nMSTimeBitLen = intval( $m_nMSTimeBitLen );
			return $this;
		}
		else
		{
			throw new \Exception( 'MSTimeBitLen params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNOpID()
	{
		if ( is_int( $this->m_nOpID ) )
		{
			return $this->m_nOpID;
		}
		else
		{
			throw new \Exception( 'illegal OpID' );
		}
	}

	/**
	 * @param mixed $m_nOpID
	 */
	public function setMNOpID( $m_nOpID )
	{
		if ( is_numeric( $m_nOpID ) && intval( $m_nOpID ) == $m_nOpID )
		{
			$m_nOpID = intval( $m_nOpID );
			if ( $this->_checkValueOutOfLimit( $m_nOpID, $this->getMNOpIDBitLen() ) )
			{
				$this->m_nOpID = $m_nOpID;
				return $this;
			}
			else
			{
				throw new \Exception( 'OpID value out of limit' );
			}
		}
		else
		{
			throw new \Exception( 'OpID params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNOpIDBitLen()
	{
		if ( is_int( $this->m_nOpIDBitLen ) )
		{
			return $this->m_nOpIDBitLen;
		}
		else
		{
			throw new \Exception( 'illegal OpIDBitLen' );
		}
	}

	/**
	 * @param mixed $m_nOpIDBitLen
	 */
	public function setMNOpIDBitLen( $m_nOpIDBitLen )
	{
		if ( is_numeric( $m_nOpIDBitLen ) && intval( $m_nOpIDBitLen ) == $m_nOpIDBitLen )
		{
			$this->m_nOpIDBitLen = intval( $m_nOpIDBitLen );
			return $this;
		}
		else
		{
			throw new \Exception( 'OpIDBitLen params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNMrID()
	{
		if ( is_int( $this->m_nMrID ) )
		{
			return $this->m_nMrID;
		}
		else
		{
			throw new \Exception( 'illegal MrID' );
		}
	}

	/**
	 * @param mixed $m_nMrID
	 */
	public function setMNMrID( $m_nMrID )
	{
		if ( is_numeric( $m_nMrID ) && intval( $m_nMrID ) == $m_nMrID )
		{
			$m_nMrID = intval( $m_nMrID );
			if ( $this->_checkValueOutOfLimit( $m_nMrID, $this->getMNMrIDBitLen() ) )
			{
				$this->m_nMrID = intval( $m_nMrID );
				return $this;
			}
			else
			{
				throw new \Exception( 'MrID value out of limit' );
			}
		}
		else
		{
			throw new \Exception( 'MrID params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNMrIDBitLen()
	{
		if ( is_int( $this->m_nMrIDBitLen ) )
		{
			return $this->m_nMrIDBitLen;
		}
		else
		{
			throw new \Exception( 'illegal MrIDBitLen' );
		}
	}

	/**
	 * @param mixed $m_nMrIDBitLen
	 */
	public function setMNMrIDBitLen( $m_nMrIDBitLen )
	{
		if ( is_numeric( $m_nMrIDBitLen ) && intval( $m_nMrIDBitLen ) == $m_nMrIDBitLen )
		{
			$this->m_nMrIDBitLen = intval( $m_nMrIDBitLen );
			return $this;
		}
		else
		{
			throw new \Exception( 'MrIDBitLen params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNServerID()
	{
		if ( is_int( $this->m_nServerID ) )
		{
			return $this->m_nServerID;
		}
		else
		{
			throw new \Exception( 'illegal ServerID' );
		}
	}

	/**
	 * @param mixed $m_nServerID
	 */
	public function setMNServerID( $m_nServerID )
	{
		if ( is_numeric( $m_nServerID ) && intval( $m_nServerID ) == $m_nServerID )
		{
			$m_nServerID = intval( $m_nServerID );
			if ( $this->_checkValueOutOfLimit( $m_nServerID, $this->getMNServerIDBitLen() ) )
			{
				$this->m_nServerID = intval( $m_nServerID );
				return $this;
			}
			else
			{
				throw new \Exception( 'ServerID value out of limit' );
			}
		}
		else
		{
			throw new \Exception( 'ServerID params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNServerIDBitLen()
	{
		if ( is_int( $this->m_nServerIDBitLen ) )
		{
			return $this->m_nServerIDBitLen;
		}
		else
		{
			throw new \Exception( 'illegal ServerIDBitLen' );
		}
	}

	/**
	 * @param mixed $m_nServerIDBitLen
	 */
	public function setMNServerIDBitLen( $m_nServerIDBitLen )
	{
		if ( is_numeric( $m_nServerIDBitLen ) && intval( $m_nServerIDBitLen ) == $m_nServerIDBitLen )
		{
			$this->m_nServerIDBitLen = intval( $m_nServerIDBitLen );
			return $this;
		}
		else
		{
			throw new \Exception( 'ServerIDBitLen params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNInMSSN()
	{
		if ( is_int( $this->m_nInMSSN ) )
		{
			return $this->m_nInMSSN;
		}
		else
		{
			throw new \Exception( 'illegal InMSSN' );
		}
	}

	public function getMNRandInMSSN()
	{
		if ( is_int( $this->m_nInMSSN ) )
		{
			return $this->m_nInMSSN;
		}
		else
		{
			$nMSSNLen = $this->getMNInMSSNBitLen();
			if ( $nMSSNLen <= 10 )
			{
				$arrTime = explode( ' ', microtime( false ) );
				if ( is_array( $arrTime ) && count( $arrTime ) == 2 )
				{
					$sMs = $arrTime[ 0 ];
					$nMic = $sMs * 1000000;
					$nMicTmp = $nMic % 1000;
					$this->m_nInMSSN = intval( $nMicTmp % pow( 2, $nMSSNLen ) );

					return $this->m_nInMSSN;
				}
				else
				{
					throw new \Exception( 'create random InMSSN get micTime error' );
				}
			}
			else
			{
				throw new \Exception( 'can not create so big random InMSSN' );
			}
		}
	}

	/**
	 * @param mixed $m_nInMSSN
	 */
	public function setMNInMSSN( $m_nInMSSN )
	{
		if ( is_numeric( $m_nInMSSN ) && intval( $m_nInMSSN ) == $m_nInMSSN )
		{
			$m_nInMSSN = intval( $m_nInMSSN );
			if ( $this->_checkValueOutOfLimit( $m_nInMSSN, $this->getMNInMSSNBitLen() ) )
			{
				$this->m_nInMSSN = intval( $m_nInMSSN );
				return $this;
			}
			else
			{
				throw new \Exception( 'InMSSN value out of limit' );
			}
		}
		else
		{
			throw new \Exception( 'InMSSN params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNInMSSNBitLen()
	{
		if ( is_int( $this->m_nInMSSNBitLen ) )
		{
			return $this->m_nInMSSNBitLen;
		}
		else
		{
			throw new \Exception( 'illegal InMSSNBitLen' );
		}
	}

	/**
	 * @param mixed $m_nInMSSNBitLen
	 */
	public function setMNInMSSNBitLen( $m_nInMSSNBitLen )
	{
		if ( is_numeric( $m_nInMSSNBitLen ) && intval( $m_nInMSSNBitLen ) == $m_nInMSSNBitLen )
		{
			$this->m_nInMSSNBitLen = intval( $m_nInMSSNBitLen );
			return $this;
		}
		else
		{
			throw new \Exception( 'InMSSNBitLen params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNLeft()
	{
		if ( is_int( $this->m_nLeft ) )
		{
			return $this->m_nLeft;
		}
		else
		{
			throw new \Exception( 'illegal left' );
		}
	}

	/**
	 * @param mixed $m_nLeft
	 */
	public function setMNLeft( $m_nLeft )
	{
		if ( is_numeric( $m_nLeft ) && intval( $m_nLeft ) == $m_nLeft )
		{
			$m_nLeft = intval( $m_nLeft );
			if ( $this->_checkValueOutOfLimit( $m_nLeft, $this->getMNLeftBitLen() ) )
			{
				$this->m_nLeft = intval( $m_nLeft );
				return $this;
			}
			else
			{
				throw new \Exception( 'left value out of limit' );
			}
		}
		else
		{
			throw new \Exception( 'left params error' );
		}
	}

	/**
	 * @return mixed
	 */
	public function getMNLeftBitLen()
	{
		if ( is_int( $this->m_nLeftBitLen ) )
		{
			return $this->m_nLeftBitLen;
		}
		else
		{
			throw new \Exception( 'illegal leftBitLen' );
		}
	}

	/**
	 * @param mixed $m_nLeftBitLen
	 */
	public function setMNLeftBitLen( $m_nLeftBitLen )
	{
		if ( is_numeric( $m_nLeftBitLen ) && intval( $m_nLeftBitLen ) == $m_nLeftBitLen )
		{
			$this->m_nLeftBitLen = intval( $m_nLeftBitLen );
			return $this;
		}
		else
		{
			throw new \Exception( 'leftBitLen params error' );
		}
	}

	private function _checkValueOutOfLimit( $nValue, $nLen )
	{
		if ( ! is_int( $nValue ) )
		{
			throw new \Exception( 'illegal check value' );
		}

		if ( ! is_int( $nLen ) )
		{
			throw new \Exception( 'illegal check length' );
		}

		$bRtn = false;

		$nMax = pow( 2, $nLen );
		if ( $nValue < $nMax )
		{
			$bRtn = true;
		}

		return $bRtn;
	}

	/**
	 * @return mixed
	 */
	public function getMNValue()
	{
		if ( is_int( $this->m_nValue ) )
		{
			return $this->m_nValue;
		}
		else
		{
			throw new \Exception( 'not init value' );
		}
	}

	/**
	 * @param mixed $m_nValue
	 */
	public function setMNValue( $m_nValue )
	{
		if ( is_numeric( $m_nValue ) && intval( $m_nValue ) == $m_nValue )
		{
			$this->m_nValue = intval( $m_nValue );
		}
		else
		{
			throw new \Exception( 'illegal disid value' );
		}
	}
};