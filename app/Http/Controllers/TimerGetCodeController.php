<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/30/2017
 * Time: 4:50 AM
 */

namespace App\Http\Controllers;

use App\Http\Repositories\Accounts\TimersRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TimerGetCodeController extends Controller
{
	protected $timerRepo;
	
	public function __construct(TimersRepository $timersRepository)
	{
		$this->timerRepo = $timersRepository;
	}
	
	public function index($sub_domain, $timer_id)
	{
		if ( $sub_domain != '' ) {
			session([ 'sub_domain' => $sub_domain ]);
		} else {
			throw new NotFoundHttpException(sprintf('Not found sub domain for %s', $timer_id));
		}
		
		$timer = $this->timerRepo->fetchTimerDetailsById($timer_id);
		
		return view('timer', compact('timer'));
	}
}