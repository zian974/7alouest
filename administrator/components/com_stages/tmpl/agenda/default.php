<?php
    /**
     * @package     7aw
     * @subpackage  modules.mod_7aw_horaires_je
     * 
     * @author     Yann "Zian" CUIDET <zian.cuidet@protonMail.com>
     * @link       https://zian.re
     *
     * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.7alouest.re/>
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     */

    use \Joomla\CMS\HTML\HTMLHelper;
    use Joomla\CMS\Language\Text;
    // use Joomla\CMS\Layout\LayoutHelper;

    use Joomla\CMS\Factory;

    // No direct access to this file
    defined('_JEXEC') or die;

    $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
    $wr = $wa->getRegistry();
    $wr->addRegistryFile('media/com_stages/joomla.asset.json');
    $wa->useScript("com_stages.scheduler");
    $wa->useStyle('com_stages.scheduler');
    
?>

<div class="com-content-article">
    <div class="page-header">
		<h1 itemprop="headline">
            Inscription au stage d'escalade
        </h1>
        <h4>
            <?php echo HTMLHelper::_('date', $this->item->date_start, Text::_('l d F Y')); ?> 
                au <?php echo HTMLHelper::_('date', $this->item->date_end, Text::_('l d F Y')); ?>
        </h4>
	</div>
    <div class="com-content-article__body">
        <p>Les inscriptions sont ouvertes.</p>
        <p>
            Pour inscrire les enfants, il vous suffit de cliquer sur l'un des boutons 
            <button type="button"class="btn-open-day mat-raised-button inverse">S'inscrire</button> dans l'agenda ci-dessous.
        </p>
<!--        <p class="text-warning">
        	Veuillez respecter un maximum de 2 inscriptions par enfant.
        </p>-->
    </div>
</div>

<p>
	Sélectionner la semaine de stage dans la liste ci-dessous :
</p>

<div id="ZnAgendaStages" class="znAgenda">
    <div class="mb-3 d-flex flex-row">
        <div class="week-select-container"></div>

        <div class="navigation ms-auto">
            <button class="navigation-left border text-muted" type="button" data-offset="-1"> 
                <i class="material-icons">chevron_left</i> 
            </button>
            <button class="navigation-right border text-muted" type="button"  data-offset="1">
                <i class="material-icons">chevron_right</i> 
            </button>
        </div>
    </div>

    <div class="week-container">
        <ul class="znWeek">
        </ul>
    </div>
</div>

<script type="text/javascript">
    ( (Joomla, document) => {
        document.addEventListener("readystatechange", function () {

            if (document.readyState === 'complete') {

                let url = window.location.origin;
                if ( window.location.host === "localhost" ) {
                    url += "/7aw-dev";
                }
            
                const stagesAgenda = new ZnAgenda("ZnAgendaStages", { inscription: true });

                getRequest = async ( url ) => {
                    let post;
                    await fetch(url + '/api/index.php/v1/stage/agenda?filter[stage_id]=<?php echo $this->state->get('stage.id')?>')
                        .then(res => {
                            return res.json()
                        })
                        .then( data => {
                            stagesAgenda.setData(data);
                            stagesAgenda.render(0);
                        })
                        .catch(err => {
                            console.log('Error: ', err)
                        });           
                };
                
                getRequest(url);
                
            }

        });
    })(Joomla, document)
</script>
