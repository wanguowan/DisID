<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 5/20/17
 * Time: 2:36 PM
 */
use wan\DisID\CDisID;
use wan\DisID\CDisIDOp;

Class CDisIDOpTest extends TestBase
{
	public function testDisIDOp()
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

		$oDisIDOp = new CDisIDOp( $oDisID );

		$nDisID = $oDisIDOp->getDisID();
		$this->assertTrue( true );
	}

	public function testDisIDOpException()
	{
		$oDisID = new CDisID();

		try
		{
			$oDisID->setMNMSTimeBitLen( 39 )
				->setMNMSTime()
				->setMNOpIDBitLen( 5 )
				->setMNMrIDBitLen( 2 )
				->setMNServerIDBitLen( 7 )
				->setMNLeftBitLen( 5 )
				->setMNInMSSNBitLen( 7 )
				->setMNOpID( 1 )
				->setMNMrID( 1 )
				->setMNServerID( 127 )
				->setMNLeft( 31 )
				->setMNInMSSN( 127 );

			$oDisIDOp = new CDisIDOp( $oDisID );

			$nDisID = $oDisIDOp->getDisID();
		}
		catch ( \Exception $e )
		{
			$sMsg = $e->getMessage();
			$this->assertEquals( 0, strcasecmp( 'illegal CDisID total len', $sMsg ) );
		}
	}
}