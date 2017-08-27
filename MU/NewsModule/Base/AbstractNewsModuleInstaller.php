<?php
/**
 * News.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\NewsModule\Base;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Zikula\Core\AbstractExtensionInstaller;
use Zikula\CategoriesModule\Entity\CategoryRegistryEntity;

/**
 * Installer base class.
 */
abstract class AbstractNewsModuleInstaller extends AbstractExtensionInstaller
{
    /**
     * Install the MUNewsModule application.
     *
     * @return boolean True on success, or false
     *
     * @throws RuntimeException Thrown if database tables can not be created or another error occurs
     */
    public function install()
    {
        $logger = $this->container->get('logger');
        $userName = $this->container->get('zikula_users_module.current_user')->get('uname');
    
        // Check if upload directories exist and if needed create them
        try {
            $container = $this->container;
            $uploadHelper = new \MU\NewsModule\Helper\UploadHelper($container->get('translator.default'), $container->get('session'), $container->get('logger'), $container->get('zikula_users_module.current_user'), $container->get('zikula_extensions_module.api.variable'), $container->getParameter('datadir'));
            $uploadHelper->checkAndCreateAllUploadFolders();
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            $logger->error('{app}: User {user} could not create upload folders during installation. Error details: {errorMessage}.', ['app' => 'MUNewsModule', 'user' => $userName, 'errorMessage' => $exception->getMessage()]);
        
            return false;
        }
        // create all tables from according entity definitions
        try {
            $this->schemaTool->create($this->listEntityClasses());
        } catch (\Exception $exception) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
            $logger->error('{app}: Could not create the database tables during installation. Error details: {errorMessage}.', ['app' => 'MUNewsModule', 'errorMessage' => $exception->getMessage()]);
    
            return false;
        }
    
        // set up all our vars with initial values
        $this->setVar('enableAttribution', false);
        $this->setVar('enableMultiLanguage', false);
        $this->setVar('showAuthor', false);
        $this->setVar('showDate', false);
        $this->setVar('enableCategorization', false);
        $this->setVar('defaultMessageSorting',  'descending' );
        $this->setVar('checkRefererOnPrint', false);
        $this->setVar('enableAjaxEditing', false);
        $this->setVar('enableMoreMessagesInCategory', false);
        $this->setVar('displayPdfLink', false);
        $this->setVar('pdfLinkDisplayAccessLevel', 0);
        $this->setVar('pdfLinkHeaderLogo', '');
        $this->setVar('pdfLinkHeaderLogoWidth', 0);
        $this->setVar('pdfLinkEnableCache', false);
        $this->setVar('enablePictureUpload', false);
        $this->setVar('imageFloatOnViewPage', '');
        $this->setVar('imageFloatOnDisplayPage', '');
        $this->setVar('maxSize', '200k');
        $this->setVar('moderationGroupForMessages', '2');
        $this->setVar('messageEntriesPerPage', '10');
        $this->setVar('linkOwnMessagesOnAccountPage', true);
        $this->setVar('filterDataByLocale', false);
        $this->setVar('enableShrinkingForMessageImageUpload1', false);
        $this->setVar('shrinkWidthMessageImageUpload1', '800');
        $this->setVar('shrinkHeightMessageImageUpload1', '600');
        $this->setVar('thumbnailModeMessageImageUpload1',  'inset' );
        $this->setVar('thumbnailWidthMessageImageUpload1View', '32');
        $this->setVar('thumbnailHeightMessageImageUpload1View', '24');
        $this->setVar('thumbnailWidthMessageImageUpload1Display', '240');
        $this->setVar('thumbnailHeightMessageImageUpload1Display', '180');
        $this->setVar('thumbnailWidthMessageImageUpload1Edit', '240');
        $this->setVar('thumbnailHeightMessageImageUpload1Edit', '180');
        $this->setVar('enableShrinkingForMessageImageUpload2', false);
        $this->setVar('shrinkWidthMessageImageUpload2', '800');
        $this->setVar('shrinkHeightMessageImageUpload2', '600');
        $this->setVar('thumbnailModeMessageImageUpload2',  'inset' );
        $this->setVar('thumbnailWidthMessageImageUpload2View', '32');
        $this->setVar('thumbnailHeightMessageImageUpload2View', '24');
        $this->setVar('thumbnailWidthMessageImageUpload2Display', '240');
        $this->setVar('thumbnailHeightMessageImageUpload2Display', '180');
        $this->setVar('thumbnailWidthMessageImageUpload2Edit', '240');
        $this->setVar('thumbnailHeightMessageImageUpload2Edit', '180');
        $this->setVar('enableShrinkingForMessageImageUpload3', false);
        $this->setVar('shrinkWidthMessageImageUpload3', '800');
        $this->setVar('shrinkHeightMessageImageUpload3', '600');
        $this->setVar('thumbnailModeMessageImageUpload3',  'inset' );
        $this->setVar('thumbnailWidthMessageImageUpload3View', '32');
        $this->setVar('thumbnailHeightMessageImageUpload3View', '24');
        $this->setVar('thumbnailWidthMessageImageUpload3Display', '240');
        $this->setVar('thumbnailHeightMessageImageUpload3Display', '180');
        $this->setVar('thumbnailWidthMessageImageUpload3Edit', '240');
        $this->setVar('thumbnailHeightMessageImageUpload3Edit', '180');
        $this->setVar('enableShrinkingForMessageImageUpload4', false);
        $this->setVar('shrinkWidthMessageImageUpload4', '800');
        $this->setVar('shrinkHeightMessageImageUpload4', '600');
        $this->setVar('thumbnailModeMessageImageUpload4',  'inset' );
        $this->setVar('thumbnailWidthMessageImageUpload4View', '32');
        $this->setVar('thumbnailHeightMessageImageUpload4View', '24');
        $this->setVar('thumbnailWidthMessageImageUpload4Display', '240');
        $this->setVar('thumbnailHeightMessageImageUpload4Display', '180');
        $this->setVar('thumbnailWidthMessageImageUpload4Edit', '240');
        $this->setVar('thumbnailHeightMessageImageUpload4Edit', '180');
        $this->setVar('enabledFinderTypes', [ 'message' ]);
    
        $categoryRegistryIdsPerEntity = [];
    
        // add default entry for category registry (property named Main)
        $categoryHelper = new \MU\NewsModule\Helper\CategoryHelper(
            $this->container->get('translator.default'),
            $this->container->get('request_stack'),
            $logger,
            $this->container->get('zikula_users_module.current_user'),
            $this->container->get('zikula_categories_module.category_registry_repository'),
            $this->container->get('zikula_categories_module.api.category_permission')
        );
        $categoryGlobal = $this->container->get('zikula_categories_module.category_repository')->findOneBy(['name' => 'Global']);
    
        $registry = new CategoryRegistryEntity();
        $registry->setModname('MUNewsModule');
        $registry->setEntityname('MessageEntity');
        $registry->setProperty($categoryHelper->getPrimaryProperty('Message'));
        $registry->setCategory($categoryGlobal);
    
        try {
            $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
            $entityManager->persist($registry);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash('error', $this->__f('Error! Could not create a category registry for the %entity% entity.', ['%entity%' => 'message']));
            $logger->error('{app}: User {user} could not create a category registry for {entities} during installation. Error details: {errorMessage}.', ['app' => 'MUNewsModule', 'user' => $userName, 'entities' => 'messages', 'errorMessage' => $exception->getMessage()]);
        }
        $categoryRegistryIdsPerEntity['message'] = $registry->getId();
    
        // initialisation successful
        return true;
    }
    
    /**
     * Upgrade the MUNewsModule application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables can not be updated
     */
    public function upgrade($oldVersion)
    {
    /*
        $logger = $this->container->get('logger');
    
        // Upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.0.0':
                // do something
                // ...
                // update the database schema
                try {
                    $this->schemaTool->update($this->listEntityClasses());
                } catch (\Exception $exception) {
                    $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
                    $logger->error('{app}: Could not update the database tables during the upgrade. Error details: {errorMessage}.', ['app' => 'MUNewsModule', 'errorMessage' => $exception->getMessage()]);
    
                    return false;
                }
        }
    
        // Note there are several helpers available for making migrating your extension from Zikula 1.3 to 1.4 easier.
        // The following convenience methods are each responsible for a single aspect of upgrading to Zikula 1.4.x.
    
        // here is a possible usage example
        // of course 1.2.3 should match the number you used for the last stable 1.3.x module version.
        /* if ($oldVersion = '1.2.3') {
            // rename module for all modvars
            $this->updateModVarsTo14();
            
            // update extension information about this app
            $this->updateExtensionInfoFor14();
            
            // rename existing permission rules
            $this->renamePermissionsFor14();
            
            // rename existing category registries
            $this->renameCategoryRegistriesFor14();
            
            // rename all tables
            $this->renameTablesFor14();
            
            // remove event handler definitions from database
            $this->dropEventHandlersFromDatabase();
            
            // update module name in the hook tables
            $this->updateHookNamesFor14();
            
            // update module name in the workflows table
            $this->updateWorkflowsFor14();
        } * /
    
        // remove obsolete persisted hooks from the database
        //$this->hookApi->uninstallSubscriberHooks($this->bundle->getMetaData());
    */
    
        // update successful
        return true;
    }
    
    /**
     * Renames the module name for variables in the module_vars table.
     */
    protected function updateModVarsTo14()
    {
        $conn = $this->getConnection();
        $conn->update('module_vars', ['modname' => 'MUNewsModule'], ['modname' => 'News']);
    }
    
    /**
     * Renames this application in the core's extensions table.
     */
    protected function updateExtensionInfoFor14()
    {
        $conn = $this->getConnection();
        $conn->update('modules', ['name' => 'MUNewsModule', 'directory' => 'MU/NewsModule'], ['name' => 'News']);
    }
    
    /**
     * Renames all permission rules stored for this app.
     */
    protected function renamePermissionsFor14()
    {
        $conn = $this->getConnection();
        $componentLength = strlen('News') + 1;
    
        $conn->executeQuery("
            UPDATE group_perms
            SET component = CONCAT('MUNewsModule', SUBSTRING(component, $componentLength))
            WHERE component LIKE 'News%';
        ");
    }
    
    /**
     * Renames all category registries stored for this app.
     */
    protected function renameCategoryRegistriesFor14()
    {
        $conn = $this->getConnection();
        $componentLength = strlen('News') + 1;
    
        $conn->executeQuery("
            UPDATE categories_registry
            SET modname = CONCAT('MUNewsModule', SUBSTRING(modname, $componentLength))
            WHERE modname LIKE 'News%';
        ");
    }
    
    /**
     * Renames all (existing) tables of this app.
     */
    protected function renameTablesFor14()
    {
        $conn = $this->getConnection();
    
        $oldPrefix = 'news_';
        $oldPrefixLength = strlen($oldPrefix);
        $newPrefix = 'mu_news_';
    
        $sm = $conn->getSchemaManager();
        $tables = $sm->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            if (substr($tableName, 0, $oldPrefixLength) != $oldPrefix) {
                continue;
            }
    
            $newTableName = str_replace($oldPrefix, $newPrefix, $tableName);
    
            $conn->executeQuery("
                RENAME TABLE $tableName
                TO $newTableName;
            ");
        }
    }
    
    /**
     * Removes event handlers from database as they are now described by service definitions and managed by dependency injection.
     */
    protected function dropEventHandlersFromDatabase()
    {
        \EventUtil::unregisterPersistentModuleHandlers('News');
    }
    
    /**
     * Updates the module name in the hook tables.
     */
    protected function updateHookNamesFor14()
    {
        $conn = $this->getConnection();
    
        $conn->update('hook_area', ['owner' => 'MUNewsModule'], ['owner' => 'News']);
    
        $componentLength = strlen('subscriber.news') + 1;
        $conn->executeQuery("
            UPDATE hook_area
            SET areaname = CONCAT('subscriber.munewsmodule', SUBSTRING(areaname, $componentLength))
            WHERE areaname LIKE 'subscriber.news%';
        ");
    
        $conn->update('hook_binding', ['sowner' => 'MUNewsModule'], ['sowner' => 'News']);
    
        $conn->update('hook_runtime', ['sowner' => 'MUNewsModule'], ['sowner' => 'News']);
    
        $componentLength = strlen('news') + 1;
        $conn->executeQuery("
            UPDATE hook_runtime
            SET eventname = CONCAT('munewsmodule', SUBSTRING(eventname, $componentLength))
            WHERE eventname LIKE 'news%';
        ");
    
        $conn->update('hook_subscriber', ['owner' => 'MUNewsModule'], ['owner' => 'News']);
    
        $componentLength = strlen('news') + 1;
        $conn->executeQuery("
            UPDATE hook_subscriber
            SET eventname = CONCAT('munewsmodule', SUBSTRING(eventname, $componentLength))
            WHERE eventname LIKE 'news%';
        ");
    }
    
    /**
     * Updates the module name in the workflows table.
     */
    protected function updateWorkflowsFor14()
    {
        $conn = $this->getConnection();
        $conn->update('workflows', ['module' => 'MUNewsModule'], ['module' => 'News']);
        $conn->update('workflows', ['obj_table' => 'MessageEntity'], ['module' => 'MUNewsModule', 'obj_table' => 'message']);
    }
    
    /**
     * Returns connection to the database.
     *
     * @return Connection the current connection
     */
    protected function getConnection()
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
    
        return $entityManager->getConnection();
    }
    
    /**
     * Uninstall MUNewsModule.
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables or stored workflows can not be removed
     */
    public function uninstall()
    {
        $logger = $this->container->get('logger');
    
        try {
            $this->schemaTool->drop($this->listEntityClasses());
        } catch (\Exception $exception) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
            $logger->error('{app}: Could not remove the database tables during uninstallation. Error details: {errorMessage}.', ['app' => 'MUNewsModule', 'errorMessage' => $exception->getMessage()]);
    
            return false;
        }
    
        // remove all module vars
        $this->delVars();
    
        // remove category registry entries
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $registries = $this->container->get('zikula_categories_module.category_registry_repository')->findBy(['modname' => 'MUNewsModule']);
        foreach ($registries as $registry) {
            $entityManager->remove($registry);
        }
        $entityManager->flush();
    
        // remind user about upload folders not being deleted
        $uploadPath = $this->container->getParameter('datadir') . '/MUNewsModule/';
        $this->addFlash('status', $this->__f('The upload directories at "%path%" can be removed manually.', ['%path%' => $uploadPath]));
    
        // uninstallation successful
        return true;
    }
    
    /**
     * Build array with all entity classes for MUNewsModule.
     *
     * @return array list of class names
     */
    protected function listEntityClasses()
    {
        $classNames = [];
        $classNames[] = 'MU\NewsModule\Entity\MessageEntity';
        $classNames[] = 'MU\NewsModule\Entity\MessageTranslationEntity';
        $classNames[] = 'MU\NewsModule\Entity\MessageAttributeEntity';
        $classNames[] = 'MU\NewsModule\Entity\MessageCategoryEntity';
    
        return $classNames;
    }
}
