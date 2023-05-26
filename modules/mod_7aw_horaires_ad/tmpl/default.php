
<?php
/**
 * @package     7aw
 * @subpackage  modules.mod_7aw_horaires_ad
 * 
 * @author     Yann "Zian" CUIDET <zian.cuidet@protonMail.com>
 * @link       https://zian.re
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.7alouest.re/>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wr = $wa->getRegistry();
$wr->addRegistryFile('media/com_stages/joomla.asset.json');
$wa->useScript("com_stages.scheduler");
$wa->useStyle('com_stages.scheduler');
?>

<h3>Saison 2022-2023</h3>
<div id="ZnAgendaStages" class="znAgenda">
    <div class="navigation mb-2">
        <div>
            <button class="navigation-left border text-muted" type="button" data-offset="-1"> 
                <i class="material-icons">chevron_left</i> 
            </button>

            <button class="navigation-right border text-muted" type="button"  data-offset="1">
                <i class="material-icons">chevron_right</i> 
            </button>
        </div>
    </div>
    <div class="week-container">
        <ul class="znWeek"></ul>
    </div>
</div>

<script type="text/javascript">
document.addEventListener("readystatechange", function () {
    if (document.readyState === 'complete') {
        let data = {
            attributes: {
                data: [
                {
                    dayTitle: "Lundi",
                    daySubTitle: "",
                    dayIndex: 0,
                    dayActive: true,
                    timeSlots: [
                        {
                            schedule: '18:30 - 20:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            encadrants: 'Cricri',
                            groupe: {
                                color: 'rouge',
                                label: 'Adultes débutants et autonomes'
                            }
                        },
                    ]
                },
                {
                    dayTitle: "Mardi",
                    daySubTitle: "",
                    dayIndex: 1,
                    dayActive: false,

                    timeSlots: [
                        {
                            schedule: '19:00 - 21:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            encadrants: 'Bernard et Carole',
                            groupe: {
                                color: 'vert',
                                label: 'Adultes autonomes'
                            }
                        },
                    ]
                },
                {
                    dayTitle: "Mercredi",
                    dayIndex: 2,
                    dayActive: false,

                    timeSlots: [
                    ]
                },
                {
                    dayTitle: "Jeudi",
                    dayIndex: 3,
                    dayActive: false,

                    timeSlots: [
                        {
                            schedule: '19:00 - 21:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            encadrants: 'Greg',
                            groupe: {
                                color: 'orange',
                                label: 'Adultes débutants'
                            }
                        },
                        {
                            schedule: '18:30 - 20:30',
                            place: 'Saint-Louis - Lycée Victor Schoelcher',
                            encadrants: 'Thierry',
                            groupe: {
                                color: 'vert',
                                label: 'Adultes autonomes'
                            }
                        },
                    ]
                },
                {
                    dayTitle: "Vendredi",
                    dayIndex: 4,
                    dayActive: false,

                    timeSlots: [
                        {
                            schedule: '19:00 - 21:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            encadrants: 'Manu',
                            groupe: {
                                color: 'vert',
                                label: 'Créneau famille'
                            }
                        },
                    ]
                },
                {
                    dayTitle: "Samedi",
                    dayIndex: 5,
                    dayActive: false,

                    timeSlots: [
                        {
                            schedule: 'Toute la journée',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            encadrants: 'Greg',
                            groupe: {
                                color: 'rouge',
                                label: 'Mur réservé à l\'encadrement de l\'école d\'escalade'
                            }
                        },
                    ]
                }]
            }
        };
        const stagesAgenda = new ZnAgenda("ZnAgendaStages");
        stagesAgenda.setData( { data: [data] });
        stagesAgenda.render(0);
    }
});
</script>

