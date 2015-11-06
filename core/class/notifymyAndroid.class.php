<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

define('NOTIFYMYANDROIDADDR', 'https://www.notifymyandroid.com/publicapi/notify');

class notifymyAndroid extends eqLogic {

    
}

class notifymyAndroidcmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function preSave() {
        $notifymyAndroid = $this->getEqLogic();
        if ($this->getConfiguration('priority') == '') {
            throw new Exception('La priorité ne peut pas être null');
        }
		if ($notifymyAndroid->getConfiguration('key') == '') {
            throw new Exception('La clé API ne peut pas être null');
        }
    }

    public function execute($_options = null) {
        $notifymyAndroid = $this->getEqLogic();
        if ($_options === null) {
            throw new Exception(__('Les options de la fonction ne peuvent etre null', __FILE__));
        }
        if ($_options['message'] == '' && $_options['title'] == '') {
            throw new Exception(__('Le message et le sujet ne peuvent être vide', __FILE__));
        }
        if ($_options['title'] == '') {
            $_options['title'] = __('[Jeedom] - Notification', __FILE__);
         }
		
		log::add('notifymyAndroid','event','Envoi: Priorité : ' . $this->getConfiguration('priority') . ' Titre : ' . $_options['title'] . ' Message : ' . $_options['message']);
        $url = NOTIFYMYANDROIDADDR . '?apikey=' . ($notifymyAndroid->getConfiguration('key')) . '&application=Jeedom&event=' . urlencode($_options['title']) . '&description=' . urlencode($_options['message']) . '&priority=' . ($this->getConfiguration('priority'));
        log::add('notifymyAndroid','debug','Url d\'envoi:' . $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result=curl_exec($ch);
        curl_close($ch);
        if(strlen($result)!=102) {
            throw new Exception(__($result, __FILE__));
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>