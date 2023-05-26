
<?php
/**
 * @package     7aw
 * @subpackage  templates.7aw
 * 
 * @author     Yann "Zian" CUIDET <zian.cuidet@protonMail.com>
 * @link       https://zian.re
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.7alouest.re/>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;


// Template path
$templatePath = 'templates/' . $this->template;


$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

$wa->useStyle('bootstrap.css');
$wa->useScript('template.wa7.map');
$wa->useScript("template.wa7.mainnav");
$wa->useScript('bootstrap.offcanvas');
$wa->useStyle('template.wa7.indexcss');
$wa->useScript('template.wa7.index');

HTMLHelper::_('bootstrap.collapse');

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

?>


<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

    <head>

		<jdoc:include type="metas" />

		 <meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title>7aw</title>

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?
						family=Caveat&family=Work+Sans&
						family=Noto+Sans&
						family=Raleway&
						family=Montserrat&
						family=Roboto+Condensed:wght@300;400;500&
						family=Solway:wght@300;400&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
              rel="stylesheet">

        <jdoc:include type="styles"/>
        <jdoc:include type="scripts"/>
        
    </head>


    <body class="<?php echo $app->getMenu()->getActive()->home?"home":""; ?>">
		
        <header class="mainnav-wrapper d-flex">
       
            

			<nav class=" container mainnav d-flex px-3 navbar-expand-md">

                <a href="" class="mainnav-brand d-flex text-decoration-none me-md-auto" >
                        <span class="fs-4 py-3"> <?php include($templatePath.'/html/logo_svg.php'); ?> </span>
                </a>
                

                <button class="navbar-toggler btn btn-light" 
                        type="button" data-bs-toggle="offcanvas" 
                        data-bs-target="#navbarTogglerDemo02" 
                        aria-controls="navbarTogglerDemo02" a
                        aria-expanded="false" 
                        aria-label="Toggle navigation">                   
                    <span class="icon icon-menu"></span>                  
                </button>

                
                <div class="offcanvas offcanvas-end" tabindex="-1" id="navbarTogglerDemo02" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        
                        <div class="offcanvas-topbar pb-3">
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                      
                        <span class="fs-4"> <?php include($templatePath.'/html/logo_svg.php'); ?> </span>
                        
                    </div>
                    <div class="offcanvas-body">
                            <jdoc:include type="modules" name="topbar" style="none" />
                    </div>
                  </div>
            </nav>
            
		</header>
        <jdoc:include type="modules" name="top-a" style="none" />
        <?php 
        
        $uri = Uri::getInstance();
        $url = $uri->getPath();

        if ( $url === '/7aw-dev/' || $url === "" || $url === "/" ): ?>
        
        <div class="parallax-wrapper">
                     
            <section class="parallax parallax-top parallax-home"></section>
            
            
            <section class="parallax parallax-top-text">
                <?php include($templatePath.'/html/logo_svg.php'); ?>
                <h1  class="display-6"> Club d'escalade </h1>
                <h1  class="display-6"> Saint Leu [ La Réunion ] </h1>
            </section>
            
            
            <section class="no-parallax"> 
                
                
        <?php endif; ?>   
                
                
                <div class="main-section">
                    
                    <div class="container small">
                        <jdoc:include type="modules" name="breadcrumbs" style="none" />
                    </div>
                    
                    
                    
                    <div class="main-grid container">
                        
                        <div class="grid-top"></div>
                        
                        <main class="grid-left">     
                            <jdoc:include type="message" />
                            <jdoc:include type="component" />
                            <jdoc:include type="modules" name="main-bottom" style="none" /> 
                        </main>   

                        <div class="grid-right-top"">
                            <jdoc:include type="modules" name="sidebar-right-top" style="none" />
                        </div>

                        <div class="grid-right"">
                            <jdoc:include type="modules" name="sidebar-right" style="none" />
                        </div>
                        
                        <div class="grid-bottom"></div>
                        
                    </div>               
                </div>
                
        <?php  if ( $app->getMenu()->getActive()->home ): ?>
            </section> 
        <?php endif; ?>   
 
            <footer class="footer-bs">

                   <div class="footer-brand animated fadeInLeft">
                      <div class="mb-4"> <?php include($templatePath.'/html/logo_svg.php'); ?> </div>

                      <p>© 2021, 7 à l'ouest - All rights reserved</p>

                   </div>

                   <div class="footer-nav animated fadeInUp">
                      <div class="">
                         <ul class="list">
                            <li><a href="index.php?option=com_content&view=article&id=12"> Contacts </a></li>
                            <li><a href="index.php?option=com_content&view=article&id=2"> Politique de confidentialité </a></li>
                         </ul>
                      </div>
                   </div>

                   <div class="footer-map animated fadeInUp">
                      
                       
                       <div class="map-wrapper">
                           <div id="ol-map"></div>
                           
                           <div class="map-buttons-wrapper">
                               
                               <div class="map-button">
                                    <button class="mat-button btn-layer-toggle">
                                        <span class="mat-button-wrapper">
                                            <i class="material-icons">satellite_alt</i>
                                        </span>
                                        <span class="mat-button-focus-overlay"></span>
                                    </button>
                               </div>
                               
                               <div class="separator"></div>
                               
                               <div class="map-button">
                                    <button class="mat-button btn-zoom-in">
                                        <span class="mat-button-wrapper">
                                            <i class="material-icons">add</i>
                                        </span>
                                        <span class="mat-button-focus-overlay"></span>
                                    </button>
                                    
                               </div>
                               
                               <div class="map-button">
                                    <button class="mat-button btn-zoom-out">
                                        <span class="mat-button-wrapper">
                                            <i class="material-icons">remove</i>
                                        </span>
                                        <span class="mat-button-focus-overlay"></span>
                                    </button>
                                    
                               </div>
                               
                           </div>
                       </div>
                       
                       
                   </div>
            </footer>
                  
        <?php if ( $app->getMenu()->getActive()->home ): ?>
        </div>
        <?php endif; ?>
        
    </body>

</html>
