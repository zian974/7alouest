
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
                    day: "null",
                    dayTitle: "Lundi",
                    daySubTitle: "",
                    dayIndex: 0,
                    dayActive: false,
                    timeSlots: []
                },
                {
                    dayTitle: "Mardi",
                    daySubTitle: "",
                    dayIndex: 1,
                    dayActive: true,

                    timeSlots: [
                        {
                            schedule: '15:00 - 16:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'blanc',
                                label: 'groupe handicapés'
                            }
                        },
                        {
                            schedule: '16:00 - 17:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'orange',
                                label: 'groupe orange'
                            },
                        },
                        {
                            schedule: '17:00 - 18:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'jaune',
                                label: 'groupe jaune'
                            },
                        },
                        {
                            schedule: '17:30 - 19:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'bleu',
                                label: 'groupe bleu'
                            },
                        }
                    ]
                },
                {
                    dayTitle: "Mercredi",
                    dayIndex: 2,
                    dayActive: false,

                    timeSlots: [
//                        {
//                            schedule: '10:00 - 11:30',
//                            place: 'Saint-Leu - Mur de Saint-Leu',
//                            groupe: {
//                                color: 'violet',
//                                label: 'groupe violet'
//                            }
//                        },
                        {
                            schedule: '14:30 - 16:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'violet',
                                label: 'groupe violet'
                            }
                        },
                        {
                            schedule: '15:30 - 17:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'rose',
                                label: 'groupe rose'
                            }
                        },
                        {
                            schedule: '16:30 - 18:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'gris',
                                label: 'groupe gris'
                            }
                        },
                        {
                            schedule: '17:00 - 20:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'beige',
                                label: 'groupe beige'
                            }
                        }
                    ]
                },
                {
                    dayTitle: "Jeudi",
                    dayIndex: 3,
                    dayActive: false,

                    timeSlots: [
                        {
                            schedule: '16:00 - 17:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'rouge',
                                label: 'groupe rouge'
                            },
                        },
                        {
                            schedule: '17:00 - 18:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'marron',
                                label: 'groupe marron'
                            },
                        },
                        {
                            schedule: '17:30 - 19:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'bleu',
                                label: 'groupe bleu'
                            },
                        }
                    ]
                },
                {
                    dayTitle: "Vendredi",
                    dayIndex: 4,
                    dayActive: false,

                    timeSlots: [
                        {
                            schedule: '16:00 - 17:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'safran',
                                label: 'groupe safran'
                            },
                        },
                        {
                            schedule: '17:00 - 18:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'jaune',
                                label: 'groupe jaune'
                            },
                        },
                        {
                            schedule: '17:30 - 19:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'beige',
                                label: 'groupe beige'
                            },
                        }
                    ]
                },
                {
                    dayTitle: "Samedi",
                    dayIndex: 5,
                    dayActive: false,

                    timeSlots: [
                        {
                            schedule: '8:30 - 10:00',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'marron',
                                label: 'groupe marron'
                            }
                        },
                        {
                            schedule: '10:00 - 11:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'orange',
                                label: 'groupe orange'
                            }
                        },
                        {
                            schedule: '14:00 - 15:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'gris',
                                label: 'groupe gris'
                            }
                        },
                        {
                            schedule: '15:00 - 16:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'violet',
                                label: 'groupe violet'
                            }
                        },
                        {
                            schedule: '15:30 - 17:30',
                            place: 'Saint-Leu - Mur de Saint-Leu',
                            groupe: {
                                color: 'rose',
                                label: 'groupe rose'
                            }
                        }
                    ]
                }
                        
                    ]
                }
            };
            const stagesAgenda = new ZnAgenda("ZnAgendaStages");
            stagesAgenda.setData( { data: [data] });
            stagesAgenda.render(0);
            
        }
    });
</script>


