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

require_once(OWA_BASE_DIR.'/owa_lib.php');
require_once(OWA_BASE_DIR.'/owa_controller.php');
require_once(OWA_BASE_DIR.DIRECTORY_SEPARATOR.'owa_coreAPI.php');



/**
 * Embedded Install Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_installEmbeddedController extends owa_controller {
	
	function owa_installEmbeddedController($params) {
		$this->owa_controller($params);
		$this->c->setSettingTemporary('base', 'cache_objects', false);
		$this->setRequiredCapability('edit_modules');
		return;
	}
	
	function action() {
		
	    $service = &owa_coreAPI::serviceSingleton();
	    
	    $this->e->notice('starting Embedded install');
		// install schema
		$base = $service->getModule('base');
		$status = $base->install();
		
		// schema was installed successfully
		if ($status == true):
		    
			// Check to see if default site already exists
			$this->e->notice('Embedded install: checking for existance of default site.');
			$site = owa_coreAPI::entityFactory('base.site');
			$site->getByColumn('site_id', $this->params['site_id']);
			$id = $site->get('id');
		
			if(empty($id)):
		    	// Create default site
				$site->set('site_id', $this->params['site_id']);
				$site->set('name', $this->params['name']);
				$site->set('description', $this->params['description']);
				$site->set('domain', $this->params['domain']);
				$site->set('site_family', $this->params['site_family']);
				$site_status = $site->create();
			
				if ($site_status == true):
					$this->e->notice('Embedded install: created default site.');
				else:
					$this->e->notice('Embedded install: creation of default site failed.');
				endif;
			else:
				$this->e->notice(sprintf("Embedded install:  default site already exists (id = %s). nothing to do here.", $id));
			endif;
			
			// Persist install complete flag. 
			$this->c->setSetting('base', 'install_complete', true);
			$save_status = $this->c->save();
			
			if ($save_status == true):
				$this->e->notice('Install Complete Flag added to configuration');
			else:
				$this->e->notice('Could not persist Install Complete Flag to the Database');
			endif;

			$data = array();
			$data['view'] = 'base.installFinishEmbedded';
			
			return $data;
		
		// schema was not installed successfully
		else:
			$this->e->notice('Aborting embedded install due to errors installing schema. Try dropping all OWA tables and try again.');
			return false;
		endif;		
			
	}
	
	
}

?>