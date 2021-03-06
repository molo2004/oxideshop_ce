<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
 * @version   OXID eShop CE
 */

namespace OxidEsales\Eshop\Setup;

/**
 * Setup manager class
 */
class Setup extends Core
{
    /**
     * Current setup step title
     *
     * @var string
     */
    protected $_sTitle = null;

    /**
     * Installation process status message
     *
     * @var string
     */
    protected $_sMessage = null;

    /**
     * Current setup step index
     *
     * @var int
     */
    protected $_iCurrStep = null;

    /** @var int Next step index */
    protected $_iNextStep = null;

    /**
     * Setup steps index array
     *
     * @var array
     */
    protected $_aSetupSteps = array(
        'STEP_SYSTEMREQ'   => 100, // 0
        'STEP_WELCOME'     => 200, // 1
        'STEP_LICENSE'     => 300, // 2
        'STEP_DB_INFO'     => 400, // 3
        'STEP_DB_CONNECT'  => 410, // 31
        'STEP_DB_CREATE'   => 420, // 32
        'STEP_DIRS_INFO'   => 500, // 4
        'STEP_DIRS_WRITE'  => 510, // 41
        'STEP_FINISH'      => 700, // 6
    );

    /**
     * Returns current setup step title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_sTitle;
    }

    /**
     * Current setup step title setter
     *
     * @param string $sTitle title
     */
    public function setTitle($sTitle)
    {
        $this->_sTitle = $sTitle;
    }

    /**
     * Returns installation process status message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_sMessage;
    }

    /**
     * Sets installation process status message
     *
     * @param string $sMsg status message
     */
    public function setMessage($sMsg)
    {
        $this->_sMessage = $sMsg;
    }

    /**
     * Returns current setup step index
     *
     * @return int
     */
    public function getCurrentStep()
    {
        if ($this->_iCurrStep === null) {
            if (($this->_iCurrStep = $this->getInstance("Utilities")->getRequestVar("istep")) === null) {
                $this->_iCurrStep = $this->getStep('STEP_SYSTEMREQ');
            }
            $this->_iCurrStep = (int) $this->_iCurrStep;
        }

        return $this->_iCurrStep;
    }

    /**
     * Returns next setup step ident
     *
     * @return int
     */
    public function getNextStep()
    {
        return $this->_iNextStep;
    }

    /**
     * Current setup step setter
     *
     * @param int $iStep current setup step index
     */
    public function setNextStep($iStep)
    {
        $this->_iNextStep = $iStep;
    }

    /**
     * Checks if config file is alleady filled with data
     *
     * @return bool
     */
    public function alreadySetUp()
    {
        $blSetUp = false;
        $sConfig = join("", file(getShopBasePath() . "config.inc.php"));
        if (strpos($sConfig, "<dbHost>") === false) {
            $blSetUp = true;
        }

        return $blSetUp;
    }

    /**
     * Returns default shop id
     *
     * @return mixed
     */
    public function getShopId()
    {
        return 'oxbaseshop';
    }

    /**
     * Returns setup steps index array
     *
     * @return array
     */
    public function getSteps()
    {
        return $this->_aSetupSteps;
    }

    /**
     * Returns setup step index
     *
     * @param string $sStepId setup step identifier
     *
     * @return int
     */
    public function getStep($sStepId)
    {
        $steps = $this->getSteps();
        return isset($steps[$sStepId]) ? $steps[$sStepId] : null;
    }

    /**
     * $iModuleState - module status:
     * -1 - unable to datect, should not block
     *  0 - missing, blocks setup
     *  1 - fits min requirements
     *  2 - exists required or better
     *
     * @param int $iModuleState module state
     *
     * @return string
     */
    public function getModuleClass($iModuleState)
    {
        switch ($iModuleState) {
            case 2:
                $sClass = 'pass';
                break;
            case 1:
                $sClass = 'pmin';
                break;
            case -1:
                $sClass = 'null';
                break;
            default:
                $sClass = 'fail';
                break;
        }
        return $sClass;
    }
}
