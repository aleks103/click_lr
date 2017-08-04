<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/7/2017
 * Time: 8:58 AM
 */

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class SvgLoadComposer
{
	public function compose(View $view)
	{
		//		Instantiate new DOMDocument object.
		$svg = new \DOMDocument();
		//		Load SVG file from public folder.
		$svg->load(public_path('/landing/images/svg-defs.svg'));
		//		Add CSS class (can omit this line).
		//		$svg->documentElement->setAttribute('class', 'svgmaster');
		//		Get XML without version element.
		$svgMaster = $svg->saveXML($svg->documentElement);
		//		Attach data to view.
		$view->with('svgMaster', $svgMaster);
	}
}