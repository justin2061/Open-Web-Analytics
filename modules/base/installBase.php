<?php

//
// Open Web Analytics - An Open Source Web Analytics Framework
//
// Copyright 2006 Peter Adams. All rights reserved.
//
// Licensed under GPL v2.0 http://www.gnu.org/copyleft/gpl.html
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
// $Id$
//

require_once(OWA_BASE_CLASS_DIR.'installController.php');

/**
 * base Schema Installation Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_installBaseController extends owa_installController {
	
	function owa_installBaseController($params) {
				
		return owa_installBaseController::__construct($params);
	}
	
	function __construct($params) {
		
		return parent::__construct($params);
	}
	
	function action() {
		
		$service = &owa_coreAPI::serviceSingleton();
		$base = $service->getModule('base');
		$status = $base->install();
		
		if ($status == true) {
			$this->set('status_code', 3305);
		} else {
			$this->set('error_code', 3302);
		}
		
		$this->setView('base.install');
		$this->setSubView('base.installDefaultSiteProfileEntry');
		
		return;
	}
	

}


?>