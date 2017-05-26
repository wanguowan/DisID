<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 5/20/17
 * Time: 2:36 PM
 */
use Wan\DisID\CDisID;

Class CDisIDTest extends TestBase
{
	public function testSetConfig()
	{
		$oDisID = new CDisID();

		$oDisID->setMNMSTimeBitLen( 39 )
			->setMNMSTime()
			->setMNOpIDBitLen( 4 )
			->setMNMrIDBitLen( 2 )
			->setMNServerIDBitLen( 7 )
			->setMNLeftBitLen( 5 )
			->setMNInMSSNBitLen( 7 )
			->setMNOpID( 1 )
			->setMNMrID( 1 )
			->setMNServerID( 127 )
			->setMNLeft( 31 )
			->setMNInMSSN( 127 );

		$this->assertEquals( 39, $oDisID->getMNMSTimeBitLen() );
		$this->assertEquals( 4, $oDisID->getMNOpIDBitLen() );
		$this->assertEquals( 2, $oDisID->getMNMrIDBitLen() );
		$this->assertEquals( 7, $oDisID->getMNServerIDBitLen() );
		$this->assertEquals( 5, $oDisID->getMNLeftBitLen() );
		$this->assertEquals( 7, $oDisID->getMNInMSSNBitLen() );

		$this->assertEquals( 1, $oDisID->getMNOpID() );
		$this->assertEquals( 1, $oDisID->getMNMrID() );
		$this->assertEquals( 127, $oDisID->getMNServerID() );
		$this->assertEquals( 31, $oDisID->getMNLeft() );
		$this->assertEquals( 127, $oDisID->getMNInMSSN() );
	}

	public function testSetException()
	{
		$oDisID = new CDisID();
		try
		{
			$oDisID->setMNMSTime();
		}
		catch ( \Exception $e )
		{
			$sMsg = $e->getMessage();
			$this->assertEquals( 0, strcasecmp( 'illegal MSTimeBitLen', $sMsg ) );
		}

		$oDisID->setMNOpIDBitLen( 2 );
		$oDisID->setMNOpID( 3 );

		try
		{
			$oDisID->setMNOpID( 4 );
		}
		catch ( \Exception $e )
		{
			$sMsg = $e->getMessage();
			$this->assertEquals( 0, strcasecmp( 'OpID value out of limit', $sMsg ) );
		}

		try
		{
			$oDisID->setMNOpID( 5 );
		}
		catch ( \Exception $e )
		{
			$sMsg = $e->getMessage();
			$this->assertEquals( 0, strcasecmp( 'OpID value out of limit', $sMsg ) );
		}

		try
		{
			$oDisID->setMNMSTimeBitLen( 39 );
			$oDisID->setMNMSTime();
			$oDisID->setMNMSBaseTime( '2017-01-02' );
			$oDisID->getMNMSTime();
		}
		catch ( \Exception $e )
		{
			$sMsg = $e->getMessage();
			$this->assertEquals( 0, strcasecmp( 'baseTime has change, please change baseTime first and set msTime second;', $sMsg ) );
		}
	}

	public function testGetRandomInMSSN()
	{
		$oDisID = new CDisID();

		try
		{
			$oDisID->getMNRandInMSSN();
		}
		catch ( \Exception $e )
		{
			$sMsg = $e->getMessage();
			$this->assertEquals( 0, strcasecmp( 'illegal InMSSNBitLen', $sMsg ) );
		}

		$oDisID->setMNInMSSNBitLen( 7 );
		$oDisID->getMNRandInMSSN();

		$this->assertTrue( true );
	}
}