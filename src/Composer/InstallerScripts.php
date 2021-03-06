<?php
declare(strict_types=1);
namespace Helhum\TYPO3\ConfigHandling\Composer;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Helmut Hummel <info@helhum.io>
 *  All rights reserved
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the text file GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Composer\Script\Event;
use Helhum\TYPO3\ConfigHandling\Composer\InstallerScript\DumpSettings;
use Helhum\TYPO3\ConfigHandling\Composer\InstallerScript\SetupConfiguration;
use Helhum\TYPO3\ConfigHandling\RootConfig;
use Helhum\Typo3Console\Mvc\Cli\CommandDispatcher;
use TYPO3\CMS\Composer\Plugin\Core\InstallerScriptsRegistration;
use TYPO3\CMS\Composer\Plugin\Core\ScriptDispatcher;

class InstallerScripts implements InstallerScriptsRegistration
{
    public static function register(Event $event, ScriptDispatcher $scriptDispatcher)
    {
        $arguments = [];
        if (!$event->isDevMode()) {
            $arguments[] = '--no-dev';
        }
        $additionalInstallStepsFile = dirname(RootConfig::getMainConfigFile()) . '/install.settings.yaml';
        if (file_exists($additionalInstallStepsFile)) {
            putenv('TYPO3_INSTALL_SETUP_STEPS=' . $additionalInstallStepsFile);
            $_ENV['TYPO3_INSTALL_SETUP_STEPS'] = $additionalInstallStepsFile;
            $_SERVER['TYPO3_INSTALL_SETUP_STEPS'] = $additionalInstallStepsFile;
        }

        $scriptDispatcher->addInstallerScript(
            new DumpSettings(),
            61
        );
    }
}
